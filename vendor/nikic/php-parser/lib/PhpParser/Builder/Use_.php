<?php

declare (strict_types=1);
namespace ConfigTransformer2022012510\PhpParser\Builder;

use ConfigTransformer2022012510\PhpParser\Builder;
use ConfigTransformer2022012510\PhpParser\BuilderHelpers;
use ConfigTransformer2022012510\PhpParser\Node;
use ConfigTransformer2022012510\PhpParser\Node\Stmt;
class Use_ implements \ConfigTransformer2022012510\PhpParser\Builder
{
    protected $name;
    protected $type;
    protected $alias = null;
    /**
     * Creates a name use (alias) builder.
     *
     * @param Node\Name|string $name Name of the entity (namespace, class, function, constant) to alias
     * @param int              $type One of the Stmt\Use_::TYPE_* constants
     */
    public function __construct($name, int $type)
    {
        $this->name = \ConfigTransformer2022012510\PhpParser\BuilderHelpers::normalizeName($name);
        $this->type = $type;
    }
    /**
     * Sets alias for used name.
     *
     * @param string $alias Alias to use (last component of full name by default)
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function as(string $alias)
    {
        $this->alias = $alias;
        return $this;
    }
    /**
     * Returns the built node.
     *
     * @return Stmt\Use_ The built node
     */
    public function getNode() : \ConfigTransformer2022012510\PhpParser\Node
    {
        return new \ConfigTransformer2022012510\PhpParser\Node\Stmt\Use_([new \ConfigTransformer2022012510\PhpParser\Node\Stmt\UseUse($this->name, $this->alias)], $this->type);
    }
}
