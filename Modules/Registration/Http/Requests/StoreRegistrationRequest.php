<?php

namespace Modules\Registration\Http\Requests;

use App\Exceptions\HandledException;
use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @throws HandledException
     */
    public function rules(): array
    {
        $el = loadConfigData('entity_length');
        return [
            'nid' => 'required|string|min:10|max:10|unique:registrations,nid', //ToDo: Should also be validated from NID server
            'name' => "required|string|max:{$el['any_title']}",
            'dob' => ['required', new ValidDate(format: 'Y-m-d')],
            'email' => "required|email|max:{$el['email']}",
            'vaccine_center_id' => 'required|exists:vaccine_centers,id,status,1,deleted_at,NULL',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'nid.required' => 'The National ID (NID) field is required.',
            'nid.string' => 'The National ID (NID) must be a valid string.',
            'nid.min' => 'The National ID (NID) must be exactly 10 characters long.',
            'nid.max' => 'The National ID (NID) must be exactly 10 characters long.',
            'nid.unique' => 'This National ID (NID) has already been registered.',

            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name cannot exceed the maximum length allowed.',

            'dob.required' => 'The date of birth is required.',

            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email address cannot exceed the maximum length allowed.',

            'vaccine_center_id.required' => 'Please select a vaccine center.',
            'vaccine_center_id.exists' => 'The selected vaccine center is invalid or inactive.',
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
