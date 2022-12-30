<?php

namespace App\Http\Requests;

use App\Models\Subcategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSubcategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('subcategory_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:3',
                'required',
            ],
        ];
    }
}
