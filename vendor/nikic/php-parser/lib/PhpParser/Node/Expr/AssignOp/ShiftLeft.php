<?php

declare (strict_types=1);
namespace ConfigTransformer202109063\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202109063\PhpParser\Node\Expr\AssignOp;
class ShiftLeft extends \ConfigTransformer202109063\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_ShiftLeft';
    }
}
