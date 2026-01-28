<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
            'priority'      => ['nullable', 'in:high,medium,low'],
            'status'        => ['nullable', 'in:todo,progress,review,completed'],
            'project_id'    => ['required', 'exists:projects,id'],
            'files'         => ['nullable', 'array'],
            'files.*'       => ['file','mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ];
    }
}
