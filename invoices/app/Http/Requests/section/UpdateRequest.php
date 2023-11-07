<?php

namespace App\Http\Requests\section;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            [
                'section_name' => 'required|unique:sections',
                'description' => 'required'
            ],
                'section_name.required' => 'اسم القسم مطلوب',
                'section_name.unique' => 'الاسم موجود بالفعل' ,
                'description.required' => 'الملاحظات مطلوبة'
        ];
        
    }
}
