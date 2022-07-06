<?php


namespace App\Application\Http\Api\SingleNotify;


use App\Application\Exception\UnSupportHttpParamsException;
use App\Application\Factory\ProducerFactory\NotifyProducerFactory;
use App\Application\Helper\StringHelper;
use App\Application\Http\Api\ApiResponse;
use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class SingleSendApiController extends AbstractController
{
    private const SUCCESS_STATUS = 'in queue';

    public function __construct(
        private readonly NotifyMessageService $service,
        private readonly ValidationService $validation,
        private readonly NotifyProducerFactory $producerFactory,
    ) {
    }

    #[Route('/send', methods: ['POST'])]
    public function send(Request $request): JsonResponse
    {
        try {
            if (!$request->isMethod('post') || $request->getContentType() !== 'json') {
                throw new UnSupportHttpParamsException('Expected json body');
            }

            $input = $this->createInputDto($request);

            $errors = $this->validation->validate($input);

            if (count($errors) !== 0) {
                // todo add presenter
                return ApiResponse::validationError([
                    'error' => $this->getErrorMessage($errors),
                ]);
            }

            $dto = $this->createMessageDto($input);

            $message = $this->service->create($dto);

            $this->producerFactory
                ->createProducer(type: $message->getType())
                ->addToQueue(notifyId: $message->getId());
        } catch (Throwable $exception) {
            // todo add presenter
            return ApiResponse::error([
                'error' => $exception->getMessage(),
            ]);
        }

        // todo add presenter
        return ApiResponse::success([
            'status' => self::SUCCESS_STATUS,
            'notifyId' => $message->getId(),
        ]);
    }

    /**
     * @throws JsonException
     */
    private function createInputDto(Request $request): MessageInput
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $input = new MessageInput();

        if ($data === null) {
            return $input;
        }

        foreach ($data as $param => $value) {
            if (property_exists($input, $param)) {
                $input->$param = StringHelper::sanitize($value);
            }
        }

        return $input;
    }

    private function createMessageDto(MessageInput $input): MessageDto
    {
        return new MessageDto($input->type, $input->toArray());
    }

    public function getErrorMessage(ConstraintViolationListInterface $errors): array
    {
        $errorList = [];

        foreach ($errors as $error) {
            assert($error instanceof ConstraintViolationInterface);

            $errorList[$error->getRoot()] = $error->getMessage();
        }

        return $errorList;
    }
}