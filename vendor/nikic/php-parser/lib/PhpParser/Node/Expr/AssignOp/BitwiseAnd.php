<?php

declare (strict_types=1);
namespace ConfigTransformer202203166\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202203166\PhpParser\Node\Expr\AssignOp;
class BitwiseAnd extends \ConfigTransformer202203166\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseAnd';
    }
}
