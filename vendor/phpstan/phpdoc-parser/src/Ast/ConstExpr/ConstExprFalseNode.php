<?php

declare (strict_types=1);
namespace ConfigTransformer20220612\PHPStan\PhpDocParser\Ast\ConstExpr;

use ConfigTransformer20220612\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprFalseNode implements ConstExprNode
{
    use NodeAttributes;
    public function __toString() : string
    {
        return 'false';
    }
}
