<?php

declare (strict_types=1);
namespace ConfigTransformer2021091810\Symplify\Astral\NodeNameResolver;

use ConfigTransformer2021091810\PhpParser\Node;
use ConfigTransformer2021091810\PhpParser\Node\Identifier;
use ConfigTransformer2021091810\PhpParser\Node\Name;
use ConfigTransformer2021091810\Symplify\Astral\Contract\NodeNameResolverInterface;
final class IdentifierNodeNameResolver implements \ConfigTransformer2021091810\Symplify\Astral\Contract\NodeNameResolverInterface
{
    /**
     * @param \PhpParser\Node $node
     */
    public function match($node) : bool
    {
        if ($node instanceof \ConfigTransformer2021091810\PhpParser\Node\Identifier) {
            return \true;
        }
        return $node instanceof \ConfigTransformer2021091810\PhpParser\Node\Name;
    }
    /**
     * @param Identifier|Name $node
     */
    public function resolve($node) : ?string
    {
        return (string) $node;
    }
}
