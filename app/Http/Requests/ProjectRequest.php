<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name'=>'sometimes|string',
            'description'=>'sometimes|string',
            'type'=>'sometimes|string',
            'start_date'=>'sometimes|date',
            'end_date'=>'sometimes|date|after:start_date',
            'priority'=>'sometimes|in:high,medium,low',
            'status'=>'sometimes|in:0,1',


        ];
    }
}
