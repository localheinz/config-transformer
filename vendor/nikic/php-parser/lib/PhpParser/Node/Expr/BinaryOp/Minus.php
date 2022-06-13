<?php

declare (strict_types=1);
namespace ConfigTransformer202206\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer202206\PhpParser\Node\Expr\BinaryOp;
class Minus extends BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '-';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_Minus';
    }
}
