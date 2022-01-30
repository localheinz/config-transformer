<?php

declare (strict_types=1);
namespace ConfigTransformer202201303\PhpParser\Node;

use ConfigTransformer202201303\PhpParser\NodeAbstract;
/**
 * This is a base class for complex types, including nullable types and union types.
 *
 * It does not provide any shared behavior and exists only for type-checking purposes.
 */
abstract class ComplexType extends \ConfigTransformer202201303\PhpParser\NodeAbstract
{
}
