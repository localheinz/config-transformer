<?php

declare (strict_types=1);
namespace ConfigTransformer202108305\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer202108305\PhpParser\Node\Expr\BinaryOp;
class Equal extends \ConfigTransformer202108305\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '==';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_Equal';
    }
}
