<?php


namespace App\Tests\unit\Application\Presenter\Api\SingleNotify;


use App\Application\Presenter\Api\SingleNotify\SingleSendNotifyApiPresenter;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class SingleSendNotifyApiPresenterCest
{
    public function presentByEmail(UnitTester $I): void
    {
        $notify = $this->getNotify(NotifyMessage::EMAIL_TYPE);
        $presenter = new SingleSendNotifyApiPresenter($notify);
        $result = $presenter->present();

        $json = json_encode(
            [
                'status' => 'in queue',
                'notifyId' => $notify->getId()->toString(),
            ],
            JSON_THROW_ON_ERROR
        );

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function presentByTelegram(UnitTester $I): void
    {
        $notify = $this->getNotify(NotifyMessage::TELEGRAM_TYPE);
        $presenter = new SingleSendNotifyApiPresenter($notify);
        $result = $presenter->present();

        $json = json_encode(
            [
                'status' => 'in queue',
                'notifyId' => $notify->getId()->toString(),
            ],
            JSON_THROW_ON_ERROR
        );

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function presentByVkontakte(UnitTester $I): void
    {
        $notify = $this->getNotify(NotifyMessage::VKONTAKTE_TYPE);
        $presenter = new SingleSendNotifyApiPresenter($notify);
        $result = $presenter->present();

        $json = json_encode(
            [
                'status' => 'in queue',
                'notifyId' => $notify->getId()->toString(),
            ],
            JSON_THROW_ON_ERROR
        );

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function presentBySms(UnitTester $I): void
    {
        $notify = $this->getNotify(NotifyMessage::SMS_TYPE);
        $presenter = new SingleSendNotifyApiPresenter($notify);
        $result = $presenter->present();

        $json = json_encode(
            [
                'status' => 'in queue',
                'notifyId' => $notify->getId()->toString(),
            ],
            JSON_THROW_ON_ERROR
        );

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function negativePresent(UnitTester $I): void
    {
        try {
            new SingleSendNotifyApiPresenter(null);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Application\Presenter\Api\SingleNotify\SingleSendNotifyApiPresenter::__construct(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/unit/Application/Presenter/Api/SingleNotify/SingleSendNotifyApiPresenterCest.php'
        ));
    }

    private function getNotify(string $type): NotifyMessage
    {
        return new NotifyMessage($type, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
    }
}