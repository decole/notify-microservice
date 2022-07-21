<?php


namespace App\Tests\functional\Application\EventListener;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Application\EventListener\MessageStatusUpdatedListener;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\RabbitMq\Producer\History\HistoryMessageProducer;
use App\Tests\FunctionalTester;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Symfony\Component\EventDispatcher\EventDispatcher;

class MessageStatusUpdatedListenerCheckCest
{
    public function withOnlyDateEnd(FunctionalTester $I): void
    {
        $message = new NotifyMessage(
            type: NotifyMessage::EMAIL_TYPE,
            message: ['test' => 'message'],
            status: NotifyMessage::STATUS_IN_QUEUE
        );

        $producer = Stub::make(HistoryMessageProducer::class, [
            'publish' => Expected::once(),
        ]);

        $dispatcher = new EventDispatcher();

        $listener = new MessageStatusUpdatedListener($producer);
        $dispatcher->addListener('onMessageStatusUpdatedEvent', [$listener, 'onMessageStatusUpdatedEvent']);

        $event = new MessageStatusUpdatedEvent($message, NotifyMessage::STATUS_DONE, 'some text');
        $dispatcher->dispatch($event, 'onMessageStatusUpdatedEvent');

        $I->assertInstanceOf(NotifyMessage::class, $event->getMessage());
        $I->assertEquals(NotifyMessage::STATUS_DONE, $event->getNewStatus());
    }
}