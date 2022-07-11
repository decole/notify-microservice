<?php


namespace App\Application\Http\Api\CheckNotifyStatus\Service;


use App\Application\Helper\StringHelper;
use App\Application\Http\Api\CheckNotifyStatus\Input\CheckStatusNotifyInput;

final class CheckNotifyStatusService
{
    public function createInputDto(string $id): CheckStatusNotifyInput
    {
        $input = new CheckStatusNotifyInput();
        $input->id = StringHelper::sanitize($id);

        return $input;
    }
}