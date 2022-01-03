<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace ConfigTransformer2022010310\Nette\Neon\Node;

/** @internal */
final class InlineArrayNode extends \ConfigTransformer2022010310\Nette\Neon\Node\ArrayNode
{
    /** @var string */
    public $bracket;
    public function __construct(string $bracket, int $pos = null)
    {
        $this->bracket = $bracket;
        $this->startPos = $this->endPos = $pos;
    }
    public function toString() : string
    {
        return $this->bracket . \ConfigTransformer2022010310\Nette\Neon\Node\ArrayItemNode::itemsToInlineString($this->items) . ['[' => ']', '{' => '}', '(' => ')'][$this->bracket];
    }
}
