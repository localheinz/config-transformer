<?php

declare (strict_types=1);
namespace ConfigTransformer202201306\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer202201306\PhpParser\Node\Expr\BinaryOp;
class LogicalAnd extends \ConfigTransformer202201306\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return 'and';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_LogicalAnd';
    }
}
