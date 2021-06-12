<?php

declare (strict_types=1);
namespace ConfigTransformer202106123\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202106123\PhpParser\Node;
use ConfigTransformer202106123\PhpParser\Node\Expr\ConstFetch;
use ConfigTransformer202106123\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ConstFetchNodeNameResolver implements \ConfigTransformer202106123\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202106123\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202106123\PhpParser\Node\Expr\ConstFetch;
    }
    /**
     * @param ConstFetch $node
     */
    public function resolve(\ConfigTransformer202106123\PhpParser\Node $node) : ?string
    {
        // convention to save uppercase and lowercase functions for each name
        return $node->name->toLowerString();
    }
}
