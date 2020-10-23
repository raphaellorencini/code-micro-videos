<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidations
{
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
