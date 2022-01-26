<?php

declare (strict_types=1);
namespace ConfigTransformer2022012610\Symplify\Astral\NodeNameResolver;

use ConfigTransformer2022012610\PhpParser\Node;
use ConfigTransformer2022012610\PhpParser\Node\Stmt\ClassLike;
use ConfigTransformer2022012610\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ClassLikeNodeNameResolver implements \ConfigTransformer2022012610\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer2022012610\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer2022012610\PhpParser\Node\Stmt\ClassLike;
    }
    /**
     * @param ClassLike $node
     */
    public function resolve(\ConfigTransformer2022012610\PhpParser\Node $node) : ?string
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
