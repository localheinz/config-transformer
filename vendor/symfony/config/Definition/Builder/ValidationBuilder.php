<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202203162\Symfony\Component\Config\Definition\Builder;

/**
 * This class builds validation conditions.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ValidationBuilder
{
    protected $node;
    public $rules = [];
    public function __construct(\ConfigTransformer202203162\Symfony\Component\Config\Definition\Builder\NodeDefinition $node)
    {
        $this->node = $node;
    }
    /**
     * Registers a closure to run as normalization or an expression builder to build it if null is provided.
     *
     * @return $this|\Symfony\Component\Config\Definition\Builder\ExprBuilder
     */
    public function rule(\Closure $closure = null)
    {
        if (null !== $closure) {
            $this->rules[] = $closure;
            return $this;
        }
        return $this->rules[] = new \ConfigTransformer202203162\Symfony\Component\Config\Definition\Builder\ExprBuilder($this->node);
    }
}
