<?php

declare (strict_types=1);
namespace ConfigTransformer202201274\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202201274\PhpParser\Node;
use ConfigTransformer202201274\PhpParser\Node\Arg;
use ConfigTransformer202201274\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ArgNodeNameResolver implements \ConfigTransformer202201274\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202201274\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202201274\PhpParser\Node\Arg;
    }
    /**
     * @param Arg $node
     */
    public function resolve(\ConfigTransformer202201274\PhpParser\Node $node) : ?string
    {
        if ($node->name === null) {
            return null;
        }
        return (string) $node->name;
    }
}
