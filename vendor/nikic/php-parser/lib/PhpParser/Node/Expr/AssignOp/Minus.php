<?php

declare (strict_types=1);
namespace ConfigTransformer2022012510\PhpParser\Node\Expr\AssignOp;

use ConfigTransformer2022012510\PhpParser\Node\Expr\AssignOp;
class Minus extends \ConfigTransformer2022012510\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_Minus';
    }
}
