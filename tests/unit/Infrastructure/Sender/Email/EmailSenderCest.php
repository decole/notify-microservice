<?php

namespace App\Tests\unit\Infrastructure\Sender\Email;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Email\EmailSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Mailer\MailerInterface;

class EmailSenderCest
{
    private Generator $faker;

    public function setUp()
    {
        $this->faker = Factory::create();
    }
    
    public function positiveSend(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);

        $sender = new EmailSender($mailer, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getNotify());
        } catch (\Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);

        $sender = new EmailSender($mailer, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getWrongNotify());
        } catch (\Throwable $exception) {}

        $I->assertEquals('Undefined array key "email"', $exception->getMessage());
    }

    public function negativeSendWithEmail(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);

        $sender = new EmailSender($mailer, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getWrongNotifyWithEmail());
        } catch (\Throwable $exception) {}

        $I->assertEquals('Undefined array key "message"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::EMAIL_TYPE,
            [
                'email' => $this->faker->email,
                'message' => $this->faker->text,
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::EMAIL_TYPE,
            [
                'test' => 'execute',
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }

    private function getWrongNotifyWithEmail(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyMessage::EMAIL_TYPE,
            [
                'email' => $this->faker->email,
                $this->faker->word => $this->faker->word,
            ],
            NotifyMessage::STATUS_IN_QUEUE
        );
    }
}