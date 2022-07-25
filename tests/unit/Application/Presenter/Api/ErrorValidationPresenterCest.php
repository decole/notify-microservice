<?php


namespace App\Tests\unit\Application\Presenter\Api;


use App\Application\Presenter\Api\ErrorValidationPresenter;
use App\Tests\UnitTester;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ErrorValidationPresenterCest
{
    public function present(UnitTester $I): void
    {
        $errors = $this->getValidationError();

        $presenter = new ErrorValidationPresenter($errors);
        $result = $presenter->present();

        $json = '{"error":{"email":"This value is not a valid email address."}}';

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function negativePresent(UnitTester $I): void
    {
        try {
            new ErrorValidationPresenter(null);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Application\Presenter\Api\ErrorValidationPresenter::__construct(): Argument #1 ($errors) must be of type Symfony\Component\Validator\ConstraintViolationListInterface, null given, called in /var/www/tests/unit/Application/Presenter/Api/ErrorValidationPresenterCest.php'
        ));
    }

    private function getValidationError(): ConstraintViolationList
    {
        $violation = new ConstraintViolation(
            message: 'This value is not a valid email address.',
            messageTemplate: null,
            parameters: [],
            root: null,
            propertyPath: 'email',
            invalidValue: 'This value is not a valid email address.',
            plural: null,
            code: 'bd79c0ab-ddba-46cc-a703-a7a4b08de310',
            constraint: null,
            cause: null
        );

        return new ConstraintViolationList([$violation]);
    }
}