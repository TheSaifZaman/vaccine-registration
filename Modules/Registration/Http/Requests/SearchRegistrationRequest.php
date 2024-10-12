<?php

namespace Modules\Registration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Registration\Enums\RegistrationStatusEnum;

class SearchRegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nid' => 'required|string|min:10|max:10|exists:registrations,nid',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        $status = RegistrationStatusEnum::NOT_REGISTERED->value;
        return [
            'nid.required' => 'The National ID (NID) field is required.',
            'nid.string' => 'The National ID (NID) must be a valid string.',
            'nid.min' => 'The National ID (NID) must be exactly 10 characters long.',
            'nid.max' => 'The National ID (NID) must be exactly 10 characters long.',
            'nid.exists' => "You are \"{$status}\" with this National ID (NID).",
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
