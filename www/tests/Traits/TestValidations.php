<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidations
{
    protected function assertInvalidationStoreAction(array $data, string $rule, array $ruleParams = [])
    {
        $response = $this->json('POST', $this->route('store'), $data);
        $fields = array_keys($data);
        $this->assertsInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationUpdateAction(array $data, string $rule, array $ruleParams = [])
    {
        $response = $this->json('PUT', $this->route('update', ['category' => $this->category->id]), $data);
        $fields = array_keys($data);
        $this->assertsInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertsInvalidationFields(TestResponse $response, array $fields, string $rule, array $ruleParams = [])
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                trans("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams),
            ]);
        }
    }


}
