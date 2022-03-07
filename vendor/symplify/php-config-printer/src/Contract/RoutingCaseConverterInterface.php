<?php

declare (strict_types=1);
namespace ConfigTransformer202203075\Symplify\PhpConfigPrinter\Contract;

use ConfigTransformer202203075\PhpParser\Node\Stmt\Expression;
interface RoutingCaseConverterInterface
{
    /**
     * @param mixed $values
     */
    public function match(string $key, $values) : bool;
    /**
     * @param mixed $values
     */
    public function convertToMethodCall(string $key, $values) : \ConfigTransformer202203075\PhpParser\Node\Stmt\Expression;
}
