<?php

declare (strict_types=1);
namespace ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\Type;

use ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ArrayTypeNode implements \ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\Type\TypeNode
{
    use NodeAttributes;
    /** @var TypeNode */
    public $type;
    public function __construct(\ConfigTransformer202202210\PHPStan\PhpDocParser\Ast\Type\TypeNode $type)
    {
        $this->type = $type;
    }
    public function __toString() : string
    {
        return $this->type . '[]';
    }
}
