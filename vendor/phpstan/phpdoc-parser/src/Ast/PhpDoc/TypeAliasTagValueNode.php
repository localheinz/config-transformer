<?php

declare (strict_types=1);
namespace ConfigTransformer202203032\PHPStan\PhpDocParser\Ast\PhpDoc;

use ConfigTransformer202203032\PHPStan\PhpDocParser\Ast\NodeAttributes;
use ConfigTransformer202203032\PHPStan\PhpDocParser\Ast\Type\TypeNode;
class TypeAliasTagValueNode implements \ConfigTransformer202203032\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string */
    public $alias;
    /** @var TypeNode */
    public $type;
    public function __construct(string $alias, \ConfigTransformer202203032\PHPStan\PhpDocParser\Ast\Type\TypeNode $type)
    {
        $this->alias = $alias;
        $this->type = $type;
    }
    public function __toString() : string
    {
        return \trim("{$this->alias} {$this->type}");
    }
}
