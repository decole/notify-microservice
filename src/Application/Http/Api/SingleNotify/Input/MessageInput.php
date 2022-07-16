<?php


namespace App\Application\Http\Api\SingleNotify\Input;


use App\Application\Service\ExtendedInputInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class MessageInput implements ExtendedInputInterface
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?string $type = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?string $message = null;

    #[Assert\Email]
    public ?string $email = null; // email

    public ?string $userId = null; // telegram, discord, vk

    public ?string $phone = null; // sms

    public ?string $topic = null; // pub/sub

    public function toArray(): array
    {
        $result = [];

        foreach ($this as $field => $value) {
            if ($value !== null) {
                $result[$field] = $value;
            }
        }

        return $result;
    }

    public function getType(): string
    {
        return $this->type;
    }
}