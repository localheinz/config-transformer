<?php

declare (strict_types=1);
namespace ConfigTransformer2022012510\PhpParser\Node\Expr;

use ConfigTransformer2022012510\PhpParser\Node\Expr;
class Empty_ extends \ConfigTransformer2022012510\PhpParser\Node\Expr
{
    /** @var Expr Expression */
    public $expr;
    /**
     * Constructs an empty() node.
     *
     * @param Expr  $expr       Expression
     * @param array $attributes Additional attributes
     */
    public function __construct(\ConfigTransformer2022012510\PhpParser\Node\Expr $expr, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->expr = $expr;
    }
    public function getSubNodeNames() : array
    {
        return ['expr'];
    }
    public function getType() : string
    {
        return 'Expr_Empty';
    }
}
