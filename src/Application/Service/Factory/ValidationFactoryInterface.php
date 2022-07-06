<?php


namespace App\Application\Service\Factory;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;

interface ValidationFactoryInterface
{
    public function getCriteria(MessageInput $input): ValidationCriteriaInterface;
}