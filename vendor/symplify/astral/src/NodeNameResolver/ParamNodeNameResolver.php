<?php

declare (strict_types=1);
namespace ConfigTransformer202204161\Symplify\Astral\NodeNameResolver;

use ConfigTransformer202204161\PhpParser\Node;
use ConfigTransformer202204161\PhpParser\Node\Expr;
use ConfigTransformer202204161\PhpParser\Node\Param;
use ConfigTransformer202204161\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ParamNodeNameResolver implements \ConfigTransformer202204161\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\ConfigTransformer202204161\PhpParser\Node $node) : bool
    {
        return $node instanceof \ConfigTransformer202204161\PhpParser\Node\Param;
    }
    /**
     * @param Param $node
     */
    public function resolve(\ConfigTransformer202204161\PhpParser\Node $node) : ?string
    {
        $paramName = $node->var->name;
        if ($paramName instanceof \ConfigTransformer202204161\PhpParser\Node\Expr) {
            return null;
        }
        return $paramName;
    }
}
