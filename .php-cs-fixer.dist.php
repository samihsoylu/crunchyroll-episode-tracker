<?php

declare(strict_types=1);

$rules = [
    '@PSR12' => true,
    'declare_strict_types' => true,
    'no_unused_imports' => true,
    'concat_space' => ['spacing' => 'one'],
    'phpdoc_align' => ['align' => 'left'],
    'class_attributes_separation' => [
        'elements' => ['method' => 'one', 'property' => 'only_if_meta', 'trait_import' => 'one']
    ],
    'blank_line_before_statement' => true,
    'date_time_immutable' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'ordered_imports' => true,
    'strict_param' => true,
    'trailing_comma_in_multiline' => true,
];

$config = new PhpCsFixer\Config();
return $config
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)  // Changed this to true
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude(['vendor', 'var'])
        ->in(__DIR__)
    );
