<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidChatWidget implements ValidationRule
{
    private ?string $provider;

    public function __construct(?string $provider = null)
    {
        $this->provider = $provider;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Validate based on provider
        match ($this->provider) {
            'whatsapp' => $this->validateWhatsApp($value, $fail),
            'smartsupp' => $this->validateSmartSupp($value, $fail),
            default => $fail('Please select a valid chat provider.')
        };
    }

    /**
     * Validate WhatsApp widget code
     */
    private function validateWhatsApp(string $code, Closure $fail): void
    {
        // Check for common WhatsApp widget patterns
        $patterns = [
            'wa.me',           // WhatsApp API link
            'whatsapp.com',    // Official WhatsApp
            'whatsapp.net',    // WhatsApp CDN
            'elfsight',        // Elfsight WhatsApp widget
            'chat widget',     // Generic widget
            'whatsapp-widget',
            'whatsapp-chat',
        ];

        $hasPattern = false;
        foreach ($patterns as $pattern) {
            if (stripos($code, $pattern) !== false) {
                $hasPattern = true;
                break;
            }
        }

        if (!$hasPattern) {
            $fail('The widget code does not appear to be a valid WhatsApp widget. Please use code from WhatsApp Business API or a trusted widget provider.');
            return;
        }

        // Security: Check for potentially dangerous content
        if ($this->containsDangerousCode($code)) {
            $fail('The widget code contains potentially dangerous content.');
        }
    }

    /**
     * Validate SmartSupp widget code
     */
    private function validateSmartSupp(string $code, Closure $fail): void
    {
        // Check for SmartSupp specific patterns
        $patterns = [
            'smartsupp',
            'smartsuppchat',
            '_smartsupp',
        ];

        $hasPattern = false;
        foreach ($patterns as $pattern) {
            if (stripos($code, $pattern) !== false) {
                $hasPattern = true;
                break;
            }
        }

        if (!$hasPattern) {
            $fail('The widget code does not appear to be a valid SmartSupp widget. Please copy the embed code from your SmartSupp dashboard.');
            return;
        }

        // Security: Check for potentially dangerous content
        if ($this->containsDangerousCode($code)) {
            $fail('The widget code contains potentially dangerous content.');
        }
    }

    /**
     * Check for potentially dangerous code
     */
    private function containsDangerousCode(string $code): bool
    {
        $dangerous = [
            'eval(',
            'document.write',
            'innerHTML',
            'outerHTML',
            'appendChild(',
            'insertBefore(',
            'setAttribute("on',
            'onerror=',
            'onload=',
            '<script>',
            '<script ',
            '<iframe',
            '<object',
            '<embed',
            'javascript:',
            'data:text/html',
        ];

        foreach ($dangerous as $pattern) {
            if (stripos($code, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
