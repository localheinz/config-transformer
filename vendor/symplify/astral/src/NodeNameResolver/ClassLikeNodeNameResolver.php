<?php

declare (strict_types=1);
namespace ConfigTransformer202204162\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202204162\PhpParser\Node;
use ConfigTransformer202204162\PhpParser\Node\Stmt\ClassLike;
use ConfigTransformer202204162\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ClassLikeNodeNameResolver implements \ConfigTransformer202204162\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202204162\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202204162\PhpParser\Node\Stmt\ClassLike;
    }
    /**
     * @param ClassLike $node
     */
    public function resolve(\ConfigTransformer202204162\PhpParser\Node $node) : ?string
    {
        if (\property_exists($node, 'namespacedName')) {
            return (string) $node->namespacedName;
        }
        if ($node->name === null) {
            return null;
        }
        return (string) $node->name;
    }
}
