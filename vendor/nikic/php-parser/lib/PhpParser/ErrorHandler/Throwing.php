<?php

declare (strict_types=1);
namespace ConfigTransformer2022022110\PhpParser\ErrorHandler;

use ConfigTransformer2022022110\PhpParser\Error;
use ConfigTransformer2022022110\PhpParser\ErrorHandler;
/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
class Throwing implements \ConfigTransformer2022022110\PhpParser\ErrorHandler
{
    public function handleError(\ConfigTransformer2022022110\PhpParser\Error $error)
    {
        throw $error;
    }
}
