<?php


namespace App\Application\EventListener;


use App\Application\Presenter\Api\ErrorPresenter;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $presenter = new ErrorPresenter($exception);

        $event->setResponse($presenter->present());
    }
}
