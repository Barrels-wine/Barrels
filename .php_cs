<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        'non_printable_character' => false,
        'yoda_style' => false,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'heredoc_to_nowdoc' => true,
        'php_unit_strict' => true,
        'php_unit_construct' => true,
        'phpdoc_add_missing_param_annotation' => false,
        'phpdoc_order' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_break_comment' => false,
        'no_extra_consecutive_blank_lines' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'],
        'no_short_echo_tag' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'semicolon_after_instruction' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'void_return' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('Migrations/')
            ->in(__DIR__.'/config')
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
            ->in(__DIR__.'/public')
            
    )
;