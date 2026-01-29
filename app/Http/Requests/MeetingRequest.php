<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
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
                'subject'=>'required|string',
                'date'=>'required|date',
                'start_time'=>'required|date_format:H:i',
                'end_time'=>'required|date_format:H:i|after:start-time',
                'note'=>'nullable|string',
                'users' => 'required|array',
                'users.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required'=>'Subject is required',
            'subject.string'=>'Subject must be a string',
            'date.required'=>'Date is required',
            'date.date'=>'Date must be a valid date',
            'start-time.required'=>'Start time is required',
            'start-time.date_format'=>'Start time must be in the format H:i',
            'end-time.required'=>'End time is required',
            'end-time.date_format'=>'End time must be in the format H:i',
            'end-time.after'=>'End time must be after start time',
            'note.string'=>'Note must be a string',
        ];
    }

    
}
