<?php

declare (strict_types=1);
namespace ConfigTransformer2022010310\Symplify\Astral\NodeValue\NodeValueResolver;

use ConfigTransformer2022010310\PhpParser\Node\Expr;
use ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst;
use ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst\Dir;
use ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst\File;
use ConfigTransformer2022010310\Symplify\Astral\Contract\NodeValueResolver\NodeValueResolverInterface;
/**
 * @see \Symplify\Astral\Tests\NodeValue\NodeValueResolverTest
 *
 * @implements NodeValueResolverInterface<MagicConst>
 */
final class MagicConstValueResolver implements \ConfigTransformer2022010310\Symplify\Astral\Contract\NodeValueResolver\NodeValueResolverInterface
{
    public function getType() : string
    {
        return \ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst::class;
    }
    /**
     * @param MagicConst $expr
     */
    public function resolve(\ConfigTransformer2022010310\PhpParser\Node\Expr $expr, string $currentFilePath) : ?string
    {
        if ($expr instanceof \ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst\Dir) {
            return \dirname($currentFilePath);
        }
        if ($expr instanceof \ConfigTransformer2022010310\PhpParser\Node\Scalar\MagicConst\File) {
            return $currentFilePath;
        }
        return null;
    }
}
