<?php

declare (strict_types=1);
namespace ConfigTransformer202204182\PHPStan\PhpDocParser\Ast\PhpDoc;

use ConfigTransformer202204182\PHPStan\PhpDocParser\Ast\NodeAttributes;
use ConfigTransformer202204182\PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function trim;
class PropertyTagValueNode implements \ConfigTransformer202204182\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var TypeNode */
    public $type;
    /** @var string */
    public $propertyName;
    /** @var string (may be empty) */
    public $description;
    public function __construct(\ConfigTransformer202204182\PHPStan\PhpDocParser\Ast\Type\TypeNode $type, string $propertyName, string $description)
    {
        $this->type = $type;
        $this->propertyName = $propertyName;
        $this->description = $description;
    }
    public function __toString() : string
    {
        return \trim("{$this->type} {$this->propertyName} {$this->description}");
    }
}
