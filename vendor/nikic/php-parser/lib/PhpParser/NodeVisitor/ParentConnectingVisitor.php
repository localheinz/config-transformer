<?php

declare (strict_types=1);
namespace ConfigTransformer2021082310\PhpParser\NodeVisitor;

use function array_pop;
use function count;
use ConfigTransformer2021082310\PhpParser\Node;
use ConfigTransformer2021082310\PhpParser\NodeVisitorAbstract;
/**
 * Visitor that connects a child node to its parent node.
 *
 * On the child node, the parent node can be accessed through
 * <code>$node->getAttribute('parent')</code>.
 */
final class ParentConnectingVisitor extends \ConfigTransformer2021082310\PhpParser\NodeVisitorAbstract
{
    /**
     * @var Node[]
     */
    private $stack = [];
    /**
     * @param mixed[] $nodes
     */
    public function beforeTraverse($nodes)
    {
        $this->stack = [];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function enterNode($node)
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[\count($this->stack) - 1]);
        }
        $this->stack[] = $node;
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function leaveNode($node)
    {
        \array_pop($this->stack);
    }
}
