<?php

declare (strict_types=1);
namespace ConfigTransformer202206\PHPStan\PhpDocParser\Ast\ConstExpr;

use ConfigTransformer202206\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprNullNode implements ConstExprNode
{
    use NodeAttributes;
    public function __toString() : string
    {
        return 'null';
    }
}
