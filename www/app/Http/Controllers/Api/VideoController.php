<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    private $rules;

    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:'.implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id',
            'genres_id' => 'required|array|exists:genres,id',
        ];
    }

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $self = $this;
        $obj = \DB::transaction(function() use ($request, $validatedData, $self){
            /** @var Video $obj */
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($request, $obj);
            return $obj;
        });
        $obj->refresh();

        return $obj;
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $self = $this;
        $obj = \DB::transaction(function() use ($request, $validatedData, $self, $obj){
            /** @var Video $obj */
            $obj->update($validatedData);
            $self->handleRelations($request, $obj);
            return $obj;
        });

        return $obj;
    }

    protected function handleRelations(Request $request, $obj)
    {
        $obj->categories()->sync($request->get('categories_id'));
        $obj->genres()->sync($request->get('genres_id'));
    }
}