<?php


namespace App\Tests\unit\Infrastructure\Sender\Email;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Sender\Email\EmailSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Symfony\Component\Mailer\MailerInterface;
use Throwable;

class EmailSenderCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }
    
    public function positiveSend(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new EmailSender($mailer, $logger, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals(false, isset($exception));
    }

    public function negativeSend(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new EmailSender($mailer, $logger, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getWrongNotify());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "email"', $exception->getMessage());
    }

    public function negativeSendWithEmail(UnitTester $I): void
    {
        $mailer = Stub::makeEmpty(MailerInterface::class, [
            'send' => Expected::once(),
        ]);
        $logger = $I->grabService(NullLogger::class);
        $sender = new EmailSender($mailer, $logger, $this->faker->text, $this->faker->email);

        try {
            $sender->send($this->getWrongNotifyWithEmail());
        } catch (Throwable $exception) {}

        $I->assertEquals('Undefined array key "message"', $exception->getMessage());
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::EMAIL->value,
            [
                'email' => $this->faker->email,
                'message' => $this->faker->text,
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }

    private function getWrongNotify(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::EMAIL->value,
            [
                'test' => 'execute',
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }

    private function getWrongNotifyWithEmail(): NotifyMessage
    {
        return new NotifyMessage(
            NotifyTypeEnum::EMAIL->value,
            [
                'email' => $this->faker->email,
                $this->faker->word => $this->faker->word,
            ],
            NotifyStatusEnum::IN_QUEUE->value
        );
    }
}