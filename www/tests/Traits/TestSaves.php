<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore(array $sendData, array $testDatabase, ?array $testJsonData = null)
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->route('store'), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: {$response->content()}");
        }
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id' => $response->json('id')]);
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')]);
    }
}
