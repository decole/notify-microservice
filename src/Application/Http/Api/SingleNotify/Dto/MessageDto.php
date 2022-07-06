<?php


namespace App\Application\Http\Api\SingleNotify\Dto;


final class MessageDto
{
    public function __construct(
        private readonly string $type,
        private readonly array $message,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMessage(): array
    {
        return $this->message;
    }
}