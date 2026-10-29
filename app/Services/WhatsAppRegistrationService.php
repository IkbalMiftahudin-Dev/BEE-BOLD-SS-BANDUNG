<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

/**
 * Stateless service that turns a validated registration form into a WhatsApp deep link.
 */
class WhatsAppRegistrationService
{
    public function __construct(private readonly string $adminPhoneNumber) {}

    public function adminPhoneNumber(): string
    {
        return $this->adminPhoneNumber;
    }

    /**
     * Single source of truth for registration validation, shared with the FormRequest.
     *
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', "regex:/^[\\pL\\s.'\\-]+$/u"],
            'age' => ['required', 'integer', 'between:7,20'],
            'parent' => ['required', 'string', 'min:2', 'max:100', "regex:/^[\\pL\\s.'\\-]+$/u"],
            'phone' => ['required', 'string', 'regex:/^(?:0|62)8[0-9]{7,12}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap calon pemain wajib diisi.',
            'name.regex' => 'Nama calon pemain hanya boleh berisi huruf, spasi, titik, apostrof, atau tanda hubung.',
            'name.max' => 'Nama calon pemain maksimal 100 karakter.',
            'age.required' => 'Usia wajib diisi.',
            'age.integer' => 'Usia harus berupa angka.',
            'age.between' => 'Usia calon pemain harus antara 7 sampai 20 tahun.',
            'parent.required' => 'Nama orang tua / wali wajib diisi.',
            'parent.regex' => 'Nama orang tua / wali hanya boleh berisi huruf, spasi, titik, apostrof, atau tanda hubung.',
            'parent.max' => 'Nama orang tua / wali maksimal 100 karakter.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp harus berupa angka dan diawali 08 atau 628 (10–15 digit).',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function validateInput(array $data): bool
    {
        return Validator::make($data, static::rules())->passes();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function buildMessageTemplate(array $data): string
    {
        return implode("\n", [
            'Halo Bee Bold, saya ingin mendaftar.',
            'Nama pemain: '.$this->singleLine($data['name'] ?? ''),
            'Usia: '.(int) ($data['age'] ?? 0).' tahun',
            'Orang tua/wali: '.$this->singleLine($data['parent'] ?? ''),
            'WhatsApp: '.preg_replace('/\D+/', '', (string) ($data['phone'] ?? '')),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function generateDeepLinkUrl(array $data): string
    {
        $phone = preg_replace('/\D+/', '', $this->adminPhoneNumber);

        return 'https://wa.me/'.$phone.'?'.http_build_query(
            ['text' => $this->buildMessageTemplate($data)],
            '',
            '&',
            PHP_QUERY_RFC3986,
        );
    }

    /**
     * Collapses control characters so user input cannot forge extra lines in the message.
     */
    private function singleLine(mixed $value): string
    {
        return trim((string) preg_replace('/[\p{C}\s]+/u', ' ', (string) $value));
    }
}

