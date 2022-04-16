<?php

declare (strict_types=1);
namespace ConfigTransformer202204161\Symplify\Astral\PhpDocParser\PhpDocNodeVisitor;

use ConfigTransformer202204161\PHPStan\PhpDocParser\Ast\Node;
use ConfigTransformer202204161\Symplify\Astral\PhpDocParser\ValueObject\PhpDocAttributeKey;
/**
 * @api
 *
 * Mimics https://github.com/nikic/PHP-Parser/blob/master/lib/PhpParser/NodeVisitor/ParentConnectingVisitor.php
 *
 * @see \Symplify\Astral\Tests\PhpDocParser\PhpDocNodeVisitor\ParentConnectingPhpDocNodeVisitorTest
 */
final class ParentConnectingPhpDocNodeVisitor extends \ConfigTransformer202204161\Symplify\Astral\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor
{
    /**
     * @var Node[]
     */
    private $stack = [];
    public function beforeTraverse(\ConfigTransformer202204161\PHPStan\PhpDocParser\Ast\Node $node) : void
    {
        $this->stack = [$node];
    }
    public function enterNode(\ConfigTransformer202204161\PHPStan\PhpDocParser\Ast\Node $node) : \ConfigTransformer202204161\PHPStan\PhpDocParser\Ast\Node
    {
        if ($this->stack !== []) {
            $parentNode = $this->stack[\count($this->stack) - 1];
            $node->setAttribute(\ConfigTransformer202204161\Symplify\Astral\PhpDocParser\ValueObject\PhpDocAttributeKey::PARENT, $parentNode);
        }
        $this->stack[] = $node;
        return $node;
    }
    /**
     * @return mixed[]|int|\PhpParser\Node|null Replacement node (or special return
     */
    public function leaveNode(\ConfigTransformer202204161\PHPStan\PhpDocParser\Ast\Node $node)
    {
        \array_pop($this->stack);
        return null;
    }
}
