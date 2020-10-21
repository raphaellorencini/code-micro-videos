<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreRequest extends FormRequest
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
        $unique = 'unique:genres,name';
        if($this->getMethod() == 'PUT') {
            $id = \Request::instance()->genre->id;
            $unique = "unique:genres,name,{$id}";
        }

        return [
            'name' => "required|{$unique}|max:255",
            'is_active' => 'boolean',
        ];
    }
}
