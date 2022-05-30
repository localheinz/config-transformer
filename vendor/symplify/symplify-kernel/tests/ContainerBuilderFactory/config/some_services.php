<?php

declare (strict_types=1);
namespace ConfigTransformer202205302;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use ConfigTransformer202205302\Symplify\SmartFileSystem\SmartFileSystem;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\ConfigTransformer202205302\Symplify\SmartFileSystem\SmartFileSystem::class);
};
