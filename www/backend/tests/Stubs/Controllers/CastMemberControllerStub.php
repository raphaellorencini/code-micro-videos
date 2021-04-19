<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Resources\CastMemberResource;
use Tests\Stubs\Models\CastMemberStub;

class CastMemberControllerStub extends BasicCrudController
{
    protected function resource()
    {
        return CastMemberResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }

    protected function model()
    {
        return CastMemberStub::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
        ];
    }
}
