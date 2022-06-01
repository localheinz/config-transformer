<?php

declare (strict_types=1);
namespace ConfigTransformer202206019\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202206019\PhpParser\Node;
use ConfigTransformer202206019\PhpParser\Node\Expr;
use ConfigTransformer202206019\PhpParser\Node\Expr\FuncCall;
use ConfigTransformer202206019\Symplify\Astral\Contract\NodeNameResolverInterface;
final class FuncCallNodeNameResolver implements \ConfigTransformer202206019\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202206019\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202206019\PhpParser\Node\Expr\FuncCall;
    }
    /**
     * @param FuncCall $node
     */
    public function resolve(\ConfigTransformer202206019\PhpParser\Node $node) : ?string
    {
        if ($node->name instanceof \ConfigTransformer202206019\PhpParser\Node\Expr) {
            return null;
        }
        return (string) $node->name;
    }
}
