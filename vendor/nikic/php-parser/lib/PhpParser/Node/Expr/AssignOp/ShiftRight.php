<?php

declare (strict_types=1);
namespace ConfigTransformer202111308\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202111308\PhpParser\Node\Expr\AssignOp;
class ShiftRight extends \ConfigTransformer202111308\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_ShiftRight';
    }
}
