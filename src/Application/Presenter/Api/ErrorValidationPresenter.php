<?php


namespace App\Application\Presenter\Api;


use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ErrorValidationPresenter extends AbstractPresenter
{
    protected const SERVER_HTTP_CODE = 400;

    public function __construct(private readonly ConstraintViolationListInterface $errors)
    {
    }

    public function getResult(): array
    {
        return [
            'error' => $this->getErrorMessage(),
        ];
    }

    private function getErrorMessage(): array
    {
        $errorList = [];

        foreach ($this->errors as $error) {
            assert($error instanceof ConstraintViolationInterface);

            $errorList[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorList;
    }
}