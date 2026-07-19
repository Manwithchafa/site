<?php

namespace App\Http\Requests\Visitors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitorRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::visitorRegistrationRules();
    }

    public static function visitorRegistrationRules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'sex' => ['nullable', Rule::in(['female', 'male'])],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'marital_status' => ['nullable', 'string', 'max:40'],
            'wedding_anniversary' => ['nullable', 'date', 'before_or_equal:today'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'residential_address' => ['nullable', 'string', 'max:500'],
            'business_address' => ['nullable', 'string', 'max:500'],
            'nearest_bus_stop' => ['nullable', 'string', 'max:120'],
            'occupation' => ['nullable', 'string', 'max:120'],
            'invited_by' => ['nullable', 'string', 'max:160'],
            'invited_by_phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'invited_by_name' => ['nullable', 'string', 'max:160'],
            'born_again' => ['boolean'],
            'born_again_when' => ['nullable', 'date', 'before_or_equal:today'],
            'is_baptized' => ['boolean'],
            'wants_membership' => ['boolean'],
            'wants_counsel' => ['boolean'],
            'preferred_visit_date' => ['nullable', 'string', 'max:160'],
            'prayer_request' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return self::visitorRegistrationMessages();
    }

    public static function visitorRegistrationMessages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number.',
            'invited_by_phone.regex' => 'Enter a valid phone number.',
            'sex.in' => 'Select a valid sex.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'wedding_anniversary.before_or_equal' => 'Wedding anniversary must be today or earlier.',
            'born_again_when.before_or_equal' => 'The born again date must be today or earlier.',
        ];
    }
}
