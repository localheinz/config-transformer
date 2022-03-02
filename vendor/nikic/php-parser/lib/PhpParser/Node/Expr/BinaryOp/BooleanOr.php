<?php

declare (strict_types=1);
namespace ConfigTransformer202203029\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer202203029\PhpParser\Node\Expr\BinaryOp;
class BooleanOr extends \ConfigTransformer202203029\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '||';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_BooleanOr';
    }
}
