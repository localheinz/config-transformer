<?php

declare (strict_types=1);
namespace ConfigTransformer202109135\PhpParser\NodeVisitor;

use ConfigTransformer202109135\PhpParser\Node;
use ConfigTransformer202109135\PhpParser\NodeVisitorAbstract;
/**
 * Visitor cloning all nodes and linking to the original nodes using an attribute.
 *
 * This visitor is required to perform format-preserving pretty prints.
 */
class CloningVisitor extends \ConfigTransformer202109135\PhpParser\NodeVisitorAbstract
{
    /**
     * @param \PhpParser\Node $origNode
     */
    public function enterNode($origNode)
    {
        $node = clone $origNode;
        $node->setAttribute('origNode', $origNode);
        return $node;
    }
}
