<?php

declare (strict_types=1);
namespace ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\Type;

use ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\NodeAttributes;
class UnionTypeNode implements \ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\Type\TypeNode
{
    use NodeAttributes;
    /** @var TypeNode[] */
    public $types;
    public function __construct(array $types)
    {
        $this->types = $types;
    }
    public function __toString() : string
    {
        return '(' . \implode(' | ', $this->types) . ')';
    }
}
