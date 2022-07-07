<?php


namespace App\Application\Service;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationServiceInterface
{
    public function validate(MessageInput $input): ConstraintViolationListInterface;
}