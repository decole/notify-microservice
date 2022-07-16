<?php


namespace App\Application\Service\Factory;


use App\Application\Service\ExtendedInputInterface;

interface ValidationFactoryInterface
{
    public function getCriteria(ExtendedInputInterface $input): ValidationCriteriaInterface;
}