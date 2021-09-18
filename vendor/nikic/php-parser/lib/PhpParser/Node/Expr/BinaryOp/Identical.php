<?php

declare (strict_types=1);
namespace ConfigTransformer2021091810\PhpParser\Node\Expr\BinaryOp;

use ConfigTransformer2021091810\PhpParser\Node\Expr\BinaryOp;
class Identical extends \ConfigTransformer2021091810\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '===';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_Identical';
    }
}
