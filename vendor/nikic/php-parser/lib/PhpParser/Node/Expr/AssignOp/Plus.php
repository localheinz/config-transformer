<?php

declare (strict_types=1);
namespace ConfigTransformer20220612\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer20220612\PhpParser\Node\Expr\AssignOp;
class Plus extends AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_Plus';
    }
}
