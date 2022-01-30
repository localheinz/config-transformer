<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('ComposerAutoloaderInita971f02b6cd74dbdc2c0a455a3580e9c', false) && !interface_exists('ComposerAutoloaderInita971f02b6cd74dbdc2c0a455a3580e9c', false) && !trait_exists('ComposerAutoloaderInita971f02b6cd74dbdc2c0a455a3580e9c', false)) {
    spl_autoload_call('ConfigTransformer202201303\ComposerAutoloaderInita971f02b6cd74dbdc2c0a455a3580e9c');
}
if (!class_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !interface_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !trait_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false)) {
    spl_autoload_call('ConfigTransformer202201303\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator');
}
if (!class_exists('Normalizer', false) && !interface_exists('Normalizer', false) && !trait_exists('Normalizer', false)) {
    spl_autoload_call('ConfigTransformer202201303\Normalizer');
}
if (!class_exists('ReturnTypeWillChange', false) && !interface_exists('ReturnTypeWillChange', false) && !trait_exists('ReturnTypeWillChange', false)) {
    spl_autoload_call('ConfigTransformer202201303\ReturnTypeWillChange');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('composerRequirea971f02b6cd74dbdc2c0a455a3580e9c')) {
    function composerRequirea971f02b6cd74dbdc2c0a455a3580e9c() {
        return \ConfigTransformer202201303\composerRequirea971f02b6cd74dbdc2c0a455a3580e9c(...func_get_args());
    }
}
if (!function_exists('scanPath')) {
    function scanPath() {
        return \ConfigTransformer202201303\scanPath(...func_get_args());
    }
}
if (!function_exists('lintFile')) {
    function lintFile() {
        return \ConfigTransformer202201303\lintFile(...func_get_args());
    }
}
if (!function_exists('parseArgs')) {
    function parseArgs() {
        return \ConfigTransformer202201303\parseArgs(...func_get_args());
    }
}
if (!function_exists('showHelp')) {
    function showHelp() {
        return \ConfigTransformer202201303\showHelp(...func_get_args());
    }
}
if (!function_exists('formatErrorMessage')) {
    function formatErrorMessage() {
        return \ConfigTransformer202201303\formatErrorMessage(...func_get_args());
    }
}
if (!function_exists('preprocessGrammar')) {
    function preprocessGrammar() {
        return \ConfigTransformer202201303\preprocessGrammar(...func_get_args());
    }
}
if (!function_exists('resolveNodes')) {
    function resolveNodes() {
        return \ConfigTransformer202201303\resolveNodes(...func_get_args());
    }
}
if (!function_exists('resolveMacros')) {
    function resolveMacros() {
        return \ConfigTransformer202201303\resolveMacros(...func_get_args());
    }
}
if (!function_exists('resolveStackAccess')) {
    function resolveStackAccess() {
        return \ConfigTransformer202201303\resolveStackAccess(...func_get_args());
    }
}
if (!function_exists('magicSplit')) {
    function magicSplit() {
        return \ConfigTransformer202201303\magicSplit(...func_get_args());
    }
}
if (!function_exists('assertArgs')) {
    function assertArgs() {
        return \ConfigTransformer202201303\assertArgs(...func_get_args());
    }
}
if (!function_exists('removeTrailingWhitespace')) {
    function removeTrailingWhitespace() {
        return \ConfigTransformer202201303\removeTrailingWhitespace(...func_get_args());
    }
}
if (!function_exists('regex')) {
    function regex() {
        return \ConfigTransformer202201303\regex(...func_get_args());
    }
}
if (!function_exists('execCmd')) {
    function execCmd() {
        return \ConfigTransformer202201303\execCmd(...func_get_args());
    }
}
if (!function_exists('ensureDirExists')) {
    function ensureDirExists() {
        return \ConfigTransformer202201303\ensureDirExists(...func_get_args());
    }
}
if (!function_exists('setproctitle')) {
    function setproctitle() {
        return \ConfigTransformer202201303\setproctitle(...func_get_args());
    }
}
if (!function_exists('array_is_list')) {
    function array_is_list() {
        return \ConfigTransformer202201303\array_is_list(...func_get_args());
    }
}
if (!function_exists('enum_exists')) {
    function enum_exists() {
        return \ConfigTransformer202201303\enum_exists(...func_get_args());
    }
}
if (!function_exists('includeIfExists')) {
    function includeIfExists() {
        return \ConfigTransformer202201303\includeIfExists(...func_get_args());
    }
}

return $loader;
