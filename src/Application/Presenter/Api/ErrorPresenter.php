<?php


namespace App\Application\Presenter\Api;


use Throwable;

final class ErrorPresenter extends AbstractPresenter
{
    protected const SERVER_HTTP_CODE = 404;

    public function __construct(private readonly Throwable $exception)
    {
    }

    public function getResult(): array
    {
        return [
            'error' => $this->exception->getMessage(),
        ];
    }
}