<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserResumeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'summary' => 'string',

            'experiences' => 'array',
            'experiences.*.company' => 'required|string',
            'experiences.*.position' => 'required|string',
            'experiences.*.started_at' => 'required|date',
            'experiences.*.finished_at' => 'date',
            
            'skills' => 'array',
            'skills.*.title' => 'required|string',
            'skills.*.percent' => 'required|int|between:1,100',

            'education' => 'array',
            'education.*.field' => 'required|string|max:150',
            'education.*.university' => 'required|string|max:200',
            'education.*.started_at' => 'required|date',
            'education.*.finished_at' => 'date',
        ];
    }
}
