<?php


namespace App\Tests\functional\Application\EventListener;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Application\EventListener\MessageStatusUpdatedListener;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\RabbitMq\Producer\History\HistoryMessageProducer;
use App\Tests\FunctionalTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Symfony\Component\EventDispatcher\EventDispatcher;

class MessageStatusUpdatedListenerCheckCest
{
    public function checkEventListener(FunctionalTester $I): void
    {
        $message = new NotifyMessage(
            type: NotifyTypeEnum::EMAIL->value,
            message: ['test' => 'message'],
            status: NotifyStatusEnum::IN_QUEUE->value
        );

        $producer = Stub::make(HistoryMessageProducer::class, [
            'publish' => Expected::once(),
        ]);

        $dispatcher = new EventDispatcher();

        $listener = new MessageStatusUpdatedListener($producer);
        $dispatcher->addListener('onMessageStatusUpdatedEvent', [$listener, 'onMessageStatusUpdatedEvent']);

        $event = new MessageStatusUpdatedEvent($message, NotifyStatusEnum::DONE->value, 'some text');
        $dispatcher->dispatch($event, 'onMessageStatusUpdatedEvent');

        $I->assertInstanceOf(NotifyMessage::class, $event->getMessage());
        $I->assertEquals(NotifyStatusEnum::DONE->value, $event->getNewStatus());
    }
}