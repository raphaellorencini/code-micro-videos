<?php

namespace Tests\Traits;

use App\Models\Traits\UploadFiles;
use Illuminate\Http\UploadedFile;

trait TestUploads
{
    protected function assertInvalidationFile($field, $extension, $maxSize, $rule, $ruleParams = [])
    {
        $routes = [
            [
                'method' => 'POST',
                'route' => route('api.videos.store'),
            ],
            [
                'method' => 'PUT',
                'route' => route('api.videos.update', $this->routeUpdateParam),
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

    protected function assertFilesExistsInStorage($model, array $files)
    {
        /** @var UploadFiles $model */
        foreach ($files as $file) {
            \Storage::assertExists($model->relativeFilePath($file->hashName()));
        }
    }
}
