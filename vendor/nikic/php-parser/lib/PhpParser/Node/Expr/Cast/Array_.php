<?php

declare (strict_types=1);
namespace ConfigTransformer2021080510\PhpParser\Node\Expr\Cast;

use ConfigTransformer2021080510\PhpParser\Node\Expr\Cast;
class Array_ extends \ConfigTransformer2021080510\PhpParser\Node\Expr\Cast
{
    public function getType() : string
    {
        return 'Expr_Cast_Array';
    }
}
