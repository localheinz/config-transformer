<?php

declare (strict_types=1);
namespace ConfigTransformer202206\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202206\PhpParser\Node\Expr\AssignOp;
class BitwiseOr extends AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseOr';
    }
}
