<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->append([__FILE__]);

return (new Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP83Migration' => true,
        '@DoctrineAnnotation' => true,

        'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
        'binary_operator_spaces' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_align' => false,
        'operator_linebreak' => false,
        'global_namespace_import' => true,
        'multiline_whitespace_before_semicolons' => true,
        'static_lambda' => true,

        'final_class' => false,
        'final_public_method_for_abstract_class' => true,
        'self_static_accessor' => true,

        'php_unit_strict' => false,
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setCacheFile(__DIR__.'/tools/php-cs-fixer/.php-cs-fixer.cache.php')
    ->setFinder($finder);
