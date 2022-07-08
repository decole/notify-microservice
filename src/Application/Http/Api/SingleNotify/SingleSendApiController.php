<?php


namespace App\Application\Http\Api\SingleNotify;


use App\Application\Exception\UnSupportHttpParamsException;
use App\Application\Factory\ProducerFactory\NotifyProducerFactory;
use App\Application\Http\Api\SingleNotify\Service\SingleSendApiService;
use App\Application\Presenter\Api\ErrorPresenter;
use App\Application\Presenter\Api\ErrorValidationPresenter;
use App\Application\Presenter\Api\SingleNotify\SingleSendNotifyApiPresenter;
use App\Application\Service\ValidationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class SingleSendApiController extends AbstractController
{
    public function __construct(
        private readonly SingleSendApiService $apiService,
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

            $input = $this->apiService->createInputDto($request);

            $errors = $this->validation->validate($input);

            if (count($errors) !== 0) {
                return (new ErrorValidationPresenter($errors))->present();
            }

            $dto = $this->apiService->createMessageDto($input);

            $message = $this->service->create($dto);

            $this->producerFactory
                ->createProducer(type: $message->getType())
                ->publish($this->apiService->getPublishQueueMessage($message));
        } catch (Throwable $exception) {
            return (new ErrorPresenter($exception))->present();
        }

        return (new SingleSendNotifyApiPresenter($message))->present();
    }
}