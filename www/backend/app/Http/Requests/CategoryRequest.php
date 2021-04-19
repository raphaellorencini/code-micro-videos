<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Validator;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = 'unique:categories,name';
        if($this->getMethod() == 'PUT') {
            $id = \Request::instance()->category->id;
            $unique = "unique:categories,name,{$id}";
        }

        return [
            'name' => "required|{$unique}|max:255",
            'description' => 'nullable',
            'is_active' => 'boolean',
        ];
    }
}
