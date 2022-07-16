<?php


namespace App\Application\Presenter\Api\Hello;


use App\Application\Presenter\Api\AbstractPresenter;

final class HelloApiPresenter extends AbstractPresenter
{
    public function getResult(): array
    {
        return [
            'hello' => 'world',
        ];
    }
}