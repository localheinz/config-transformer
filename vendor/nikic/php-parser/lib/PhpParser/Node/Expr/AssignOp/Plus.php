<?php

declare (strict_types=1);
namespace ConfigTransformer202109136\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202109136\PhpParser\Node\Expr\AssignOp;
class Plus extends \ConfigTransformer202109136\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_Plus';
    }
}
