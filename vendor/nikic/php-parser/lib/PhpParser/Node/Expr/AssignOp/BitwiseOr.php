<?php

declare (strict_types=1);
namespace ConfigTransformer2021072110\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer2021072110\PhpParser\Node\Expr\AssignOp;
class BitwiseOr extends \ConfigTransformer2021072110\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseOr';
    }
}
