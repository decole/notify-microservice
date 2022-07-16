<?php


namespace App\Application\Presenter\Api;


use Throwable;

final class ErrorPresenter extends AbstractPresenter
{
    protected const SERVER_HTTP_CODE = 400;

    public function __construct(private readonly Throwable $exception)
    {
    }

    public function getResult(): array
    {
        return [
            'result' => false,
            'error' => 'An error occurred while executing the request',
            'errorText' => $this->exception->getMessage(),
        ];
    }
}