<?php

namespace App\Http\Requests;

use App\Services\WhatsAppRegistrationService;
use Illuminate\Foundation\Http\FormRequest;

class WhatsAppRegistrationRequest extends FormRequest
{
    /**
     * Public registration form; abuse is limited by the route throttle instead.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalise the phone number so the numeric-only rule matches what the UI enforces.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'parent' => is_string($this->input('parent')) ? trim($this->input('parent')) : $this->input('parent'),
            'phone' => is_string($this->input('phone')) ? preg_replace('/\D+/', '', $this->input('phone')) : $this->input('phone'),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return WhatsAppRegistrationService::rules();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return WhatsAppRegistrationService::messages();
    }

    /**
     * Send the visitor back to the registration section so validation errors are visible.
     */
    protected function getRedirectUrl(): string
    {
        return route('home').'#daftar';
    }
}

