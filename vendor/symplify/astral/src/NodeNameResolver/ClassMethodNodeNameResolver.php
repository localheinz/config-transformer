<?php

declare (strict_types=1);
namespace ConfigTransformer202204161\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202204161\PhpParser\Node;
use ConfigTransformer202204161\PhpParser\Node\Stmt\ClassMethod;
use ConfigTransformer202204161\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ClassMethodNodeNameResolver implements \ConfigTransformer202204161\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202204161\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202204161\PhpParser\Node\Stmt\ClassMethod;
    }
    /**
     * @param ClassMethod $node
     */
    public function resolve(\ConfigTransformer202204161\PhpParser\Node $node) : ?string
    {
        return $node->name->toString();
    }
}
