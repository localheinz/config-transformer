<?php

declare (strict_types=1);
namespace ConfigTransformer202204179\Symplify\Astral\NodeTraverser;

use ConfigTransformer202204179\PhpParser\Node;
use ConfigTransformer202204179\PhpParser\NodeTraverser;
use ConfigTransformer202204179\Symplify\Astral\NodeVisitor\CallableNodeVisitor;
/**
 * @api
 */
final class SimpleCallableNodeTraverser
{
    /**
     * @param callable(Node $node): (int|Node|null) $callable
     * @param mixed[]|\PhpParser\Node|null $nodes
     */
    public function traverseNodesWithCallable($nodes, callable $callable) : void
    {
        if ($nodes === null) {
            return;
        }
        if ($nodes === []) {
            return;
        }
        if (!\is_array($nodes)) {
            $nodes = [$nodes];
        }
        $nodeTraverser = new \ConfigTransformer202204179\PhpParser\NodeTraverser();
        $callableNodeVisitor = new \ConfigTransformer202204179\Symplify\Astral\NodeVisitor\CallableNodeVisitor($callable);
        $nodeTraverser->addVisitor($callableNodeVisitor);
        $nodeTraverser->traverse($nodes);
    }
}
