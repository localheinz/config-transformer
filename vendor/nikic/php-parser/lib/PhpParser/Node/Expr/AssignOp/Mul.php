<?php

declare (strict_types=1);
namespace ConfigTransformer202202064\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer202202064\PhpParser\Node\Expr\AssignOp;
class Mul extends \ConfigTransformer202202064\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_Mul';
    }
}
