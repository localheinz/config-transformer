<?php

declare (strict_types=1);
namespace ConfigTransformer2021061810\Symplify\Astral\ValueObject\NodeBuilder;

use ConfigTransformer2021061810\PhpParser\Builder\Use_;
use ConfigTransformer2021061810\PhpParser\Node\Stmt\Use_ as UseStmt;
/**
 * Fixed duplicated naming in php-parser and prevents confusion
 */
final class UseBuilder extends \ConfigTransformer2021061810\PhpParser\Builder\Use_
{
    public function __construct($name, int $type = \ConfigTransformer2021061810\PhpParser\Node\Stmt\Use_::TYPE_NORMAL)
    {
        parent::__construct($name, $type);
    }
}
