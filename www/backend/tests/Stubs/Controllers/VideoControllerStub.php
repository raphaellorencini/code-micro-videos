<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Resources\VideoResource;
use Tests\Stubs\Models\VideoStub;

class VideoControllerStub extends BasicCrudController
{

    protected function resource()
    {
        return VideoResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }

    protected function model()
    {
        return VideoStub::class;
    }

    protected function rulesStore()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:'.implode(',', VideoStub::RATING_LIST),
            'duration' => 'required|integer',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:'.implode(',', VideoStub::RATING_LIST),
            'duration' => 'required|integer',
        ];
    }
}
