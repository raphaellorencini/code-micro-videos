<?php

namespace Tests\Traits;

use Illuminate\Http\UploadedFile;

trait TestUploads
{
    protected function assertInvalidationFile($field, $extension, $maxSize, $rule, $ruleParams = [])
    {
        $routes = [
            [
                'method' => 'POST',
                'route' => $this->route('store'),
            ],
            [
                'method' => 'PUT',
                'route' => $this->route('update', $this->routeUpdateParam),
            ],
        ];

        foreach ($routes as $route) {
            $file = UploadedFile::fake()->create("{$field}.1{$extension}");
            $response = $this->json($route['method'], $route['route'], [
                $field => $file,
            ]);
            $this->assertsInvalidationFields($response, [$field], $rule, $ruleParams);

            $file = UploadedFile::fake()->create("{$field}.{$extension}")->size($maxSize + 10);
            $response = $this->json($route['method'], $route['route'], [
                $field => $file,
            ]);
            $this->assertsInvalidationFields($response, [$field], 'max.file', ['max' => $maxSize]);
        }
    }
}
