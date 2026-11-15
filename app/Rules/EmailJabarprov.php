<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailJabarprov implements ValidationRule
{
    /**
     * Validasi bahwa email harus menggunakan domain @jabarprov.go.id atau subdomain resminya.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Format alamat email tidak valid.');
            return;
        }

        // Domain harus jabarprov.go.id atau subdomain resmi dari jabarprov.go.id
        if (! preg_match('/^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9.-]+\.)?jabarprov\.go\.id$/i', $value)) {
            $fail('Hanya email resmi Pemerintah Provinsi Jawa Barat (@jabarprov.go.id atau subdomainnya) yang dapat menggunakan sistem ini.');
        }
    }
}
