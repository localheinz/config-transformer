<?php

declare (strict_types=1);
namespace ConfigTransformer2021112310\Symplify\ConfigTransformer\ValueObject;

use ConfigTransformer2021112310\Symplify\ConfigTransformer\Enum\Format;
final class Configuration
{
    /**
     * @var string[]
     */
    private $sources;
    /**
     * @var bool
     */
    private $isDryRun;
    /**
     * @param string[] $sources
     */
    public function __construct(array $sources, bool $isDryRun)
    {
        $this->sources = $sources;
        $this->isDryRun = $isDryRun;
    }
    /**
     * @return string[]
     */
    public function getSources() : array
    {
        return $this->sources;
    }
    public function isDryRun() : bool
    {
        return $this->isDryRun;
    }
    /**
     * @return string[]
     */
    public function getInputSuffixes() : array
    {
        return [\ConfigTransformer2021112310\Symplify\ConfigTransformer\Enum\Format::YAML, \ConfigTransformer2021112310\Symplify\ConfigTransformer\Enum\Format::YML, \ConfigTransformer2021112310\Symplify\ConfigTransformer\Enum\Format::XML];
    }
}
