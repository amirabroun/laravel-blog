<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

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
            'experiences.*.started_at' => 'required|date|date_format:Y-m',
            'experiences.*.finished_at' => 'date|date_format:Y-m',

            'skills' => 'array',
            'skills.*.title' => 'required|string',
            'skills.*.percent' => 'required|int|between:1,100',

            'education' => 'array',
            'education.*.field' => 'required|string|max:150',
            'education.*.university' => 'required|string|max:200',
            'education.*.location' => 'string|max:200',
            'education.*.started_at' => 'required|date|date_format:Y-m',
            'education.*.finished_at' => 'date|date_format:Y-m',

            'contact' => 'array',
            'contact.*.title' => 'required|string|max:50',
            'contact.*.link' => 'required',
        ];
    }

    public static function validateContact($data)
    {
        foreach ($data['contact'] as $contact) {
            match ($contact['title']) {
                'email' => static::validate($contact['title'], $contact['link'], 'email'),
                'github' => static::validate($contact['title'], $contact['link'], 'url'),
                'linkedin' => static::validate($contact['title'], $contact['link'], 'url'),
                'phone' => static::validate($contact['title'], $contact['link'], 'numeric'),
                'address' => static::validate($contact['title'], $contact['link'], 'string'),
            };
        }
    }

    private static function validate($title, $link, $rule)
    {
        Validator::make(
            [$title => $link],
            [$title => $rule],
        )->validate();
    }
}