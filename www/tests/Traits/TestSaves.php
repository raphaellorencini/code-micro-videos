<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore($sendData, $testData)
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->route('store'), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: {$response->content()}");
        }
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testData + ['id' => $response->json('id')]);
    }
}
