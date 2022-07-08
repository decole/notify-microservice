<?php


namespace App\Infrastructure\RabbitMq\Consumer\Email;


use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\Exception\NotFoundEntityException;
use App\Infrastructure\Sender\Email\EmailSender;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Throwable;

final class EmailConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly EmailSender $sender,
        private readonly NotifyMessageService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(AMQPMessage $msg): int|bool
    {
        try {
            $decoded = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $id = $decoded['messageId'];
            $message = $this->service->find($id);

            if ($message === null) {
                // todo For SenderService
                // add to HistoryMessageQueue - message is error with exception text

                throw new NotFoundEntityException('notify message not found');
            }

            $this->sender->send($message);

            // todo For SenderService
            // add to HistoryMessageQueue - message is done

            return ConsumerInterface::MSG_ACK;
        } catch (Throwable $throwable) {
            $this->logger->error("Exception: {$throwable->getMessage()}", [
                'decoded' => $decoded,
                'exception' => $throwable
            ]);

            // todo For SenderService
            // add to HistoryMessageQueue - message is error with exception text
        }

        return ConsumerInterface::MSG_ACK;
    }
}