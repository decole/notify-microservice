<?php


namespace App\Tests\unit\Application\Presenter\Api\CheckStatus;


use App\Application\Presenter\Api\CheckStatus\CheckStatusNotifyPresenter;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckStatusNotifyPresenterCest
{
    public function present(UnitTester $I): void
    {
        $notify = $this->getNotify();
        $presenter = new CheckStatusNotifyPresenter($notify);
        $result = $presenter->present();

        $json = json_encode(
            [
                'id' => $notify->getId()->toString(),
                'status' => 'in queue',
                'lastModifiedStatusByUTC' => $notify->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
            JSON_THROW_ON_ERROR
        );

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function negativePresent(UnitTester $I): void
    {
        try {
            new CheckStatusNotifyPresenter(null);
        } catch (\Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Application\Presenter\Api\CheckStatus\CheckStatusNotifyPresenter::__construct(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/unit/Application/Presenter/Api/CheckStatus/CheckStatusNotifyPresenterCest.php'
        ));
    }

    private function getNotify(): NotifyMessage
    {
        return new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
    }
}