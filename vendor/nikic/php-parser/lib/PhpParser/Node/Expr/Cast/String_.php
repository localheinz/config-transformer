<?php

declare (strict_types=1);
namespace ConfigTransformer202108222\PhpParser\Node\Expr\Cast;

use ConfigTransformer202108222\PhpParser\Node\Expr\Cast;
class String_ extends \ConfigTransformer202108222\PhpParser\Node\Expr\Cast
{
    public function getType() : string
    {
        return 'Expr_Cast_String';
    }
}
