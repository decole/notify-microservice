<?php


namespace App\Tests\unit\Application\Presenter\Api\SingleNotify;


use App\Application\Presenter\Api\SingleNotify\SingleSendNotifyApiPresenter;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Symfony\Component\HttpFoundation\JsonResponse;

class SingleSendNotifyApiPresenterCest
{
    public function present(UnitTester $I): void
    {
        $notify = $this->getNotify();
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
        } catch (\Throwable $exception) {}

        $I->assertEquals(
            'App\Application\Presenter\Api\SingleNotify\SingleSendNotifyApiPresenter::__construct(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/unit/Application/Presenter/Api/SingleNotify/SingleSendNotifyApiPresenterCest.php on line 35',
            $exception->getMessage()
        );
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
    }
}