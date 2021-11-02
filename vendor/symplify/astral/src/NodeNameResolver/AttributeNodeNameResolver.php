<?php

declare (strict_types=1);
namespace ConfigTransformer202111026\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202111026\PhpParser\Node;
use ConfigTransformer202111026\PhpParser\Node\Attribute;
use ConfigTransformer202111026\Symplify\Astral\Contract\NodeNameResolverInterface;
final class AttributeNodeNameResolver implements \ConfigTransformer202111026\Symplify\Astral\Contract\NodeNameResolverInterface
{
    /**
     * @param \PhpParser\Node $node
     */
    public function match($node) : bool
    {
        return $node instanceof \ConfigTransformer202111026\PhpParser\Node\Attribute;
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function resolve($node) : ?string
    {
        return $node->name->toString();
    }
}
