<?php

declare (strict_types=1);
namespace ConfigTransformer202205033\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer202205033\PhpParser\Node\Expr\BinaryOp;
class BooleanAnd extends \ConfigTransformer202205033\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '&&';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_BooleanAnd';
    }
}
