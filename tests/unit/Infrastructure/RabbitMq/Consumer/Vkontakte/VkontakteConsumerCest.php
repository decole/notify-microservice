<?php


namespace App\Tests\unit\Infrastructure\RabbitMq\Consumer\Vkontakte;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Application\EventListener\MessageStatusUpdatedListener;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\RabbitMq\Consumer\Vkontakte\VkontakteConsumer;
use App\Infrastructure\RabbitMq\Producer\History\HistoryMessageProducer;
use App\Infrastructure\Sender\Vkontakte\VkontakteSender;
use App\Tests\UnitTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Faker\Factory;
use Faker\Generator;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

class VkontakteConsumerCest
{
    private NotifyMessageRepository $repository;
    private Generator $faker;

    public function setUp(UnitTester $I): void
    {
        $this->repository = $I->grabService(NotifyMessageRepository::class);
        $this->faker = Factory::create();
    }

    public function positiveExecute(UnitTester $I): void
    {
        [$logger, $service, $sender, $dispatcher] = $this->makeServices($I);

        $this->addEventListener($dispatcher);

        $consumer = new VkontakteConsumer(
            sender: $sender,
            service: $service,
            eventDispatcher: $dispatcher,
            logger: $logger
        );

        $I->assertInstanceOf(VkontakteConsumer::class, $consumer);

        $AmqpMessage = $this->getAmqpMessage();

        $answer = $consumer->execute($AmqpMessage);

        $I->assertEquals($answer, ConsumerInterface::MSG_ACK);
    }

    public function negativeExecute(UnitTester $I): void
    {
        [$logger, $service, $sender, $dispatcher] = $this->makeServices($I);

        $consumer = new VkontakteConsumer(
            sender: $sender,
            service: $service,
            eventDispatcher: $dispatcher,
            logger: $logger
        );

        $I->assertInstanceOf(VkontakteConsumer::class, $consumer);

        $AmqpMessage = $this->getAmqpMessage(false);

        $answer = $consumer->execute($AmqpMessage);

        $I->assertEquals($answer, ConsumerInterface::MSG_REJECT);
    }

    private function getAmqpMessage(bool $save = true): AMQPMessage
    {
        $notify = new NotifyMessage(NotifyMessage::VKONTAKTE_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        if ($save) {
            $this->repository->save($notify);
        }

        $message = [
            'messageId' => $notify->getId()->toString(),
        ];

        return new AMQPMessage(json_encode($message, JSON_THROW_ON_ERROR));
    }

    public function addEventListener(EventDispatcher $dispatcher): void
    {
        $producer = Stub::make(HistoryMessageProducer::class, [
            'publish' => Expected::exactly(3),
        ]);
        $listener = new MessageStatusUpdatedListener($producer);
        $dispatcher->addListener(MessageStatusUpdatedEvent::NAME, [$listener, 'onMessageStatusUpdatedEvent']);
    }

    public function makeServices(UnitTester $I): array
    {
        $logger = $I->grabService(NullLogger::class);
        $service = $I->grabService(NotifyMessageService::class);
        $sender = Stub::makeEmpty(VkontakteSender::class, [
            'send' => Expected::once(),
        ]);
        $dispatcher = new EventDispatcher();

        return [$logger, $service, $sender, $dispatcher];
    }
}