<?php

declare (strict_types=1);
namespace ConfigTransformer202203132\PHPStan\PhpDocParser\Ast\PhpDoc;

use ConfigTransformer202203132\PHPStan\PhpDocParser\Ast\NodeAttributes;
class PhpDocTextNode implements \ConfigTransformer202203132\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocChildNode
{
    use NodeAttributes;
    /** @var string */
    public $text;
    public function __construct(string $text)
    {
        $this->text = $text;
    }
    public function __toString() : string
    {
        return $this->text;
    }
}
