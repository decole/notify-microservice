<?php


namespace App\Application\Service\Factory;


use Symfony\Component\Validator\ConstraintViolationList;

interface ValidationCriteriaInterface
{
    public function validate(ConstraintViolationList $list): void;
}