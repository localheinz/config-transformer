<?php

declare (strict_types=1);
namespace ConfigTransformer202203250\Symplify\PackageBuilder\Console\Input;

use ConfigTransformer202203250\Symfony\Component\Console\Input\ArgvInput;
/**
 * @api
 */
final class StaticInputDetector
{
    public static function isDebug() : bool
    {
        $argvInput = new \ConfigTransformer202203250\Symfony\Component\Console\Input\ArgvInput();
        return $argvInput->hasParameterOption(['--debug', '-v', '-vv', '-vvv']);
    }
}
