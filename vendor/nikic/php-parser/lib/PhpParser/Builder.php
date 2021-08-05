<?php

declare (strict_types=1);
namespace ConfigTransformer2021080510\PhpParser;

interface Builder
{
    /**
     * Returns the built node.
     *
     * @return Node The built node
     */
    public function getNode() : \ConfigTransformer2021080510\PhpParser\Node;
}
