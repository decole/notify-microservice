<?php


namespace App\Infrastructure\RabbitMq\Consumer;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\Exception\NotFoundEntityException;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

abstract class AbstractNotifyConsumer
{
    public function __construct(
        private readonly NotifyMessageService $service,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws JsonException
     */
    final public function execute(AMQPMessage $msg): int|bool
    {
        $decoded = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $id = $decoded['messageId'];

        $message = $this->service->find($id);

        try {
            if ($message === null) {
                throw new NotFoundEntityException("Notify message with id: {$id} not found");
            }

            $this->eventDispatcher->dispatch(
                new MessageStatusUpdatedEvent($message, NotifyMessage::STATUS_ACTIVE),
                MessageStatusUpdatedEvent::NAME
            );

            $this->getSender()->send($message);

            $this->eventDispatcher->dispatch(
                new MessageStatusUpdatedEvent($message, NotifyMessage::STATUS_DONE),
                MessageStatusUpdatedEvent::NAME
            );
        } catch (Throwable $exception) {
            $this->logger->error("Notify exception by id: {$id}", [
                'decoded' => $decoded,
                'exception' => $exception->getMessage(),
            ]);

            if ($message !== null) {
                $this->eventDispatcher->dispatch(
                    new MessageStatusUpdatedEvent($message, NotifyMessage::STATUS_ERROR, $exception->getMessage()),
                    MessageStatusUpdatedEvent::NAME,
                );
            }

            return ConsumerInterface::MSG_REJECT;
        }

        return ConsumerInterface::MSG_ACK;
    }

    abstract public function getSender(): SenderInterface;
}