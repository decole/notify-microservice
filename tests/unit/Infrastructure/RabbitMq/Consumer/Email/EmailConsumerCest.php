<?php


namespace App\Tests\unit\Infrastructure\RabbitMq\Consumer\Email;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Application\EventListener\MessageStatusUpdatedListener;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\RabbitMq\Consumer\Email\EmailConsumer;
use App\Infrastructure\RabbitMq\Producer\History\HistoryMessageProducer;
use App\Infrastructure\Sender\Email\EmailSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EmailConsumerCest
{
    private NotifyMessageRepository $repository;

    public function setUp(UnitTester $I): void
    {
        $this->repository = $I->grabService(NotifyMessageRepository::class);
    }

    public function positiveExecute(UnitTester $I): void
    {
        $AmqpMessage = $this->getAmqpMessage();

        // make consumer
        $logger = $I->grabService(NullLogger::class);
        $service = $I->grabService(NotifyMessageService::class);
        $sender = Stub::makeEmpty(EmailSender::class, [
            'send' => Expected::once(),
        ]);
        $dispatcher = new EventDispatcher();
        $this->addEventListner($dispatcher);

        $consumer = new EmailConsumer(
            sender: $sender,
            service: $service,
            eventDispatcher: $dispatcher,
            logger: $logger
        );

        $I->assertInstanceOf(EmailConsumer::class, $consumer);

        $answer = $consumer->execute($AmqpMessage);

        $I->assertEquals($answer, ConsumerInterface::MSG_ACK);
    }

    public function negativeExecute(UnitTester $I): void
    {
        // make consumer
        $logger = $I->grabService(NullLogger::class);
        $service = $I->grabService(NotifyMessageService::class);
        $sender = Stub::makeEmpty(EmailSender::class, [
            'send' => Expected::once(),
        ]);
        $dispatcher = new EventDispatcher();

        $consumer = new EmailConsumer(
            sender: $sender,
            service: $service,
            eventDispatcher: $dispatcher,
            logger: $logger
        );

        $I->assertInstanceOf(EmailConsumer::class, $consumer);

        $AmqpMessage = $this->getAmqpMessage(false);

        $answer = $consumer->execute($AmqpMessage);

        $I->assertEquals($answer, ConsumerInterface::MSG_REJECT);
    }

    private function getAmqpMessage(bool $save = true): AMQPMessage
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        if ($save) {
            $this->repository->save($notify);
        }

        $message = [
            'messageId' => $notify->getId()->toString(),
        ];

        return new AMQPMessage(json_encode($message, JSON_THROW_ON_ERROR));
    }

    public function addEventListner(EventDispatcher $dispatcher): void
    {
        $producer = Stub::make(HistoryMessageProducer::class, [
            'publish' => Expected::exactly(3),
        ]);
        $listener = new MessageStatusUpdatedListener($producer);
        $dispatcher->addListener(MessageStatusUpdatedEvent::NAME, [$listener, 'onMessageStatusUpdatedEvent']);
    }
}