<?php


namespace App\Infrastructure\Http\Api\CheckNotifyStatus;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\CheckNotifyStatus\Service\CheckNotifyStatusService;
use App\Application\Presenter\Api\CheckStatus\CheckStatusNotifyPresenter;
use App\Application\Presenter\Api\ErrorValidationPresenter;
use App\Application\Service\ValidationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CheckNotifyStatusApiController extends AbstractController
{
    public function __construct(
        private readonly ValidationService $validation,
        private readonly NotifyMessageService $service,
        private readonly CheckNotifyStatusService $apiService,
    ) {
    }

    #[Route('/v1/check-status/{id}', methods: ['GET'])]
    public function status(string $id): JsonResponse
    {
        $input = $this->apiService->createInputDto($id);

        $errors = $this->validation->validate($input);

        if (count($errors) !== 0) {
            return (new ErrorValidationPresenter($errors))->present();
        }

        $notify = $this->service->find($input->id);

        if ($notify === null) {
            throw new NotFoundEntityException("Notify by id {$input->id} not found");
        }

        return (new CheckStatusNotifyPresenter($notify))->present();
    }
}