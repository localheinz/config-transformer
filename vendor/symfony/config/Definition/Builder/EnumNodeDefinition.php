<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202112194\Symfony\Component\Config\Definition\Builder;

use ConfigTransformer202112194\Symfony\Component\Config\Definition\EnumNode;
/**
 * Enum Node Definition.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class EnumNodeDefinition extends \ConfigTransformer202112194\Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
{
    /**
     * @var mixed[]
     */
    private $values;
    /**
     * @return $this
     */
    public function values(array $values)
    {
        $values = \array_unique($values);
        if (empty($values)) {
            throw new \InvalidArgumentException('->values() must be called with at least one value.');
        }
        $this->values = $values;
        return $this;
    }
    /**
     * Instantiate a Node.
     *
     * @throws \RuntimeException
     */
    protected function instantiateNode() : \ConfigTransformer202112194\Symfony\Component\Config\Definition\ScalarNode
    {
        if (!isset($this->values)) {
            throw new \RuntimeException('You must call ->values() on enum nodes.');
        }
        return new \ConfigTransformer202112194\Symfony\Component\Config\Definition\EnumNode($this->name, $this->parent, $this->values, $this->pathSeparator);
    }
}
