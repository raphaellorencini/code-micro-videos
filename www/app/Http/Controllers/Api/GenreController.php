<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{
    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean',
        'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
    ];

    protected function model()
    {
        return Genre::class;
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
            /** @var Genre $obj */
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
            /** @var Genre $obj */
            $obj->update($validatedData);
            $self->handleRelations($request, $obj);
            return $obj;
        });

        return $obj;
    }

    protected function handleRelations(Request $request, $obj)
    {
        $obj->categories()->sync($request->get('categories_id'));
    }
}
