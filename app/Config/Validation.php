<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;
use App\Validation\CustomRules;

class Validation extends BaseConfig
{
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        CustomRules::class, // Your custom rules are correctly included here
    ];

    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public array $defaultRules = [
        'required'           => 'required',
        'alpha'              => 'alpha',
        'alpha_space'        => 'alpha_space',
        'alphanum'           => 'alphanum',
        'alphanum_space'     => 'alphanum_space',
        'decimal'            => 'decimal',
        'differs'            => 'differs',
        'emails'             => 'emails',
        'exact_length'       => 'exact_length',
        'greater_than'       => 'greater_than',
        'in_list'            => 'in_list',
        'integer'            => 'integer',
        'is_natural'         => 'is_natural',
        'is_natural_no_zero' => 'is_natural_no_zero',
        'less_than'          => 'less_than',
        'less_than_equal_to' => 'less_than_equal_to',
        'matches'            => 'matches',
        'max_length'         => 'max_length',
        'min_length'         => 'min_length',
        'not_in_list'        => 'not_in_list',
        'numeric'            => 'numeric',
        'regex_match'        => 'regex_match',
        'valid_email'        => 'valid_email',
        'valid_emails'       => 'valid_emails',
        'valid_ip'           => 'valid_ip',
        'valid_url'          => 'valid_url',
        'valid_date'         => 'valid_date',
        'date_greater_equal' => 'date_greater_than_equal_to', // Alias
        // Map the full rule name if you want to use it directly in rules array
        'date_greater_than_equal_to' => 'date_greater_than_equal_to',
        'check_date_range_overlap' => 'The selected leave period overlaps with an existing leave.',
        // Add the new medical rule here
        'check_medical_date_range_overlap' => 'The selected medical record period overlaps with an existing medical record.',
    ];
}