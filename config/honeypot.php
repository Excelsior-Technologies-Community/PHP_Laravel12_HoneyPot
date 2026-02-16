<?php

use App\Spam\CustomSpamResponder;

return [

    'enabled' => true, // Enable or disable honeypot protection

    'name_field_name' => 'my_name', // Name of the hidden trap field

    'randomize_name_field_name' => true, // Randomizes the honeypot field name for better security

    'valid_from_timestamp' => true, // Enables time-based spam protection check

    'valid_from_field_name' => 'valid_from', // Hidden field that stores form load timestamp

    'amount_of_seconds' => 5, // Minimum seconds required before form submission is allowed

    'respond_to_spam_with' => CustomSpamResponder::class, // Custom response shown when spam is detected

    'honeypot_fields_required_for_all_forms' => false, // If true, all forms must contain honeypot fields

    'spam_protection' => \Spatie\Honeypot\SpamProtection::class, // Core spam protection logic class

    'with_csp' => false, // Enable only if using Laravel CSP package
];
