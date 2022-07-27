<?php


namespace App\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationCriteriaInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class DiscordValidationCriteria implements ValidationCriteriaInterface
{
    private const REQUIRE_FIELDS = [
        'type',
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
    }
}
