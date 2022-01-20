<?php

declare (strict_types=1);
namespace ConfigTransformer202201205\Symplify\ConfigTransformer\Configuration;

use ConfigTransformer202201205\Symfony\Component\Console\Input\InputInterface;
use ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Configuration;
use ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Option;
final class ConfigurationFactory
{
    public function createFromInput(\ConfigTransformer202201205\Symfony\Component\Console\Input\InputInterface $input) : \ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Configuration
    {
        $source = (array) $input->getArgument(\ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Option::SOURCES);
        $isDryRun = \boolval($input->getOption(\ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Option::DRY_RUN));
        return new \ConfigTransformer202201205\Symplify\ConfigTransformer\ValueObject\Configuration($source, $isDryRun);
    }
}
