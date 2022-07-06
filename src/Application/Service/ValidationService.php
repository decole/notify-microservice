<?php


namespace App\Application\Service;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidationService implements ValidationServiceInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ValidationFactory $factory,
    ) {
    }

    /**
     * @throws NotFoundEntityException
     */
    public function validate(MessageInput $input): ConstraintViolationListInterface
    {
        $list = $this->validator->validate($input);

        assert($list instanceof ConstraintViolationList);

        if (count($list) === 0) {
            $criteria = $this->factory->getCriteria($input);

            $criteria->validate($list);
        }

        return $list;
    }
}