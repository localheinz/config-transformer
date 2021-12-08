<?php

declare (strict_types=1);
namespace ConfigTransformer2021120810\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer2021120810\PhpParser\Node\Expr\BinaryOp;
class SmallerOrEqual extends \ConfigTransformer2021120810\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '<=';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_SmallerOrEqual';
    }
}
