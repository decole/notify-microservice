<?php


namespace App\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationCriteriaInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class TelegramValidationCriteria implements ValidationCriteriaInterface
{
    private const REQUIRE_FIELDS = [
        'type',
        'userId',
        'message',
    ];

    public function __construct(private readonly MessageInput $input)
    {
    }

    public function validate(ConstraintViolationList $list): void
    {
        foreach (self::REQUIRE_FIELDS as $field) {
            if (($this->input->$field ?? null) === null) {
                $list->add(new ConstraintViolation(
                    message: 'field is required!',
                    messageTemplate: null,
                    parameters: [$field],
                    root: $field,
                    propertyPath: $field,
                    invalidValue: $field
                ));
            }
        }

        if (mb_strlen($this->input->userId) < 8) {
            $list->add(new ConstraintViolation(
                message: "userId can be greater then 8 symbols",
                messageTemplate: null,
                parameters: ['userId'],
                root: 'userId',
                propertyPath: 'userId',
                invalidValue: 'userId'
            ));
        }

        if (filter_var($this->input->userId, FILTER_VALIDATE_INT) === false) {
            $list->add(new ConstraintViolation(
                message: "userId can be integer",
                messageTemplate: null,
                parameters: ['userId'],
                root: 'userId',
                propertyPath: 'userId',
                invalidValue: 'userId'
            ));
        }
    }
}