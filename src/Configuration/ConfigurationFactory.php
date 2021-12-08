<?php

declare (strict_types=1);
namespace ConfigTransformer2021120810\Symplify\ConfigTransformer\Configuration;

use ConfigTransformer2021120810\Symfony\Component\Console\Input\InputInterface;
use ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Configuration;
use ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Option;
final class ConfigurationFactory
{
    public function createFromInput(\ConfigTransformer2021120810\Symfony\Component\Console\Input\InputInterface $input) : \ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Configuration
    {
        $source = (array) $input->getArgument(\ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Option::SOURCES);
        $isDryRun = \boolval($input->getOption(\ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Option::DRY_RUN));
        return new \ConfigTransformer2021120810\Symplify\ConfigTransformer\ValueObject\Configuration($source, $isDryRun);
    }
}
