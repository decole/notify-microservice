<?php


namespace App\Tests\unit\Infrastructure\RabbitMq\Consumer\History;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Repository\NotifyMessage\NotifyMessageRepository;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\RabbitMq\Consumer\History\HistoryMessageConsumer;
use App\Tests\UnitTester;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\NullLogger;

class HistoryMessageConsumerCest
{
    private NotifyMessageRepository $repository;
    private HistoryMessageConsumer $consumer;
    private NotifyMessageService $messageService;
    private HistoryNotificationService $historyService;

    public function setUp(UnitTester $I): void
    {
        $this->repository = $I->grabService(NotifyMessageRepository::class);

        $this->messageService = $I->grabService(NotifyMessageService::class);
        $this->historyService = $I->grabService(HistoryNotificationService::class);
        $logger = $I->grabService(NullLogger::class);

        $this->consumer = new HistoryMessageConsumer(
            historyNotificationService: $this->historyService,
            notifyMessageService: $this->messageService,
            logger: $logger
        );
    }

    public function positiveExecute(UnitTester $I): void
    {
        $message = $this->getAmqpMessage(NotifyMessage::STATUS_DONE);

        $answer = $this->consumer->execute($message);

        $notifyId = json_decode($message->getBody(),true, 512, JSON_THROW_ON_ERROR);

        $notify = $this->messageService->find($notifyId['messageId']);
        $historyRecords = $this->historyService->findByNotifyMessage($notify);

        $I->assertInstanceOf(NotifyMessage::class, $notify);
        $I->assertEquals($answer, ConsumerInterface::MSG_ACK);
        $I->assertEquals(1, count($historyRecords));
    }

    public function negativeExecute(UnitTester $I): void
    {
        $message = $this->getAmqpMessage(NotifyMessage::STATUS_DONE, false);

        $answer = $this->consumer->execute($message);

        $notifyId = json_decode($message->getBody(),true, 512, JSON_THROW_ON_ERROR);

        $notify = $this->messageService->find($notifyId['messageId']);

        $I->assertEquals(null, $notify);
        $I->assertEquals($answer, ConsumerInterface::MSG_REJECT);
    }

    private function getAmqpMessage(int $status, bool $save = true): AMQPMessage
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        if ($save) {
            $this->repository->save($notify);
        }

        $message = [
            'messageId' => $notify->getId()->toString(),
            'status' => $status,
        ];

        return new AMQPMessage(json_encode($message, JSON_THROW_ON_ERROR));
    }
}