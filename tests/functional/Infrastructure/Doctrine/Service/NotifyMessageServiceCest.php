<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Service;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Tests\FunctionalTester;

// todo  create negative cases
class NotifyMessageServiceCest
{
    private NotifyMessageService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(NotifyMessageService::class);
    }

    public function create(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyMessage::EMAIL_TYPE, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyMessage::EMAIL_TYPE, $savedEntity->getType());
        $I->assertEquals(NotifyMessage::STATUS_IN_QUEUE, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

//    public function find(FunctionalTester $I): void
//    {
//        $cavedEntity = $this->service->find();
//    }
//
//    public function update(FunctionalTester $I): void
//    {
//        $cavedEntity = $this->service->update();
//    }
//
//    public function updateStatus(FunctionalTester $I): void
//    {
//        $cavedEntity = $this->service->updateStatus();
//    }
}