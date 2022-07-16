<?php


namespace App\Application\Http\Api\CheckNotifyStatus\Input;


use App\Application\Service\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CheckStatusNotifyInput implements InputInterface
{
    #[Assert\NotNull]
    #[Assert\Uuid]
    public ?string $id = null;
}