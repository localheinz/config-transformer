<?php

declare (strict_types=1);
namespace ConfigTransformer202201274\PhpParser;

interface NodeTraverserInterface
{
    /**
     * Adds a visitor.
     *
     * @param NodeVisitor $visitor Visitor to add
     */
    public function addVisitor(\ConfigTransformer202201274\PhpParser\NodeVisitor $visitor);
    /**
     * Removes an added visitor.
     *
     * @param NodeVisitor $visitor
     */
    public function removeVisitor(\ConfigTransformer202201274\PhpParser\NodeVisitor $visitor);
    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return Node[] Traversed array of nodes
     */
    public function traverse(array $nodes) : array;
}
