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

    public function execute(AMQPMessage $msg)
    {
        try {
            $decoded = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);

            dump($decoded);

            // For SenderService
            // get MessageId
            $id = '';
            $message = $this->service->find($id);

            if ($message === null) {
                throw new NotFoundEntityException('notify message not found');
            }

            $this->sender->send($message);
        } catch (Throwable $throwable) {
            $this->logger->error("Exception: {$throwable->getMessage()}", [
                'decoded' => $decoded,
                'exception' => $throwable
            ]);

            // For SenderService
            // add to HistoryMessageQueue
        }
    }
}