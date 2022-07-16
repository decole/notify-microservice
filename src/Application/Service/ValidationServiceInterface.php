<?php


namespace App\Application\Service;


use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationServiceInterface
{
    public function validate(InputInterface $input): ConstraintViolationListInterface;
}