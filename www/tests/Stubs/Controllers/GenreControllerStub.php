<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Resources\GenreResource;
use Tests\Stubs\Models\GenreStub;

class GenreControllerStub extends BasicCrudController
{

    protected function resource()
    {
        return GenreResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }

    protected function model()
    {
        return GenreStub::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
