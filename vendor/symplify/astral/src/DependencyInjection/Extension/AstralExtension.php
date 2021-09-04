<?php

declare (strict_types=1);
namespace ConfigTransformer202109040\Symplify\Astral\DependencyInjection\Extension;

use ConfigTransformer202109040\Symfony\Component\Config\FileLocator;
use ConfigTransformer202109040\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer202109040\Symfony\Component\DependencyInjection\Extension\Extension;
use ConfigTransformer202109040\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class AstralExtension extends \ConfigTransformer202109040\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function load($configs, $containerBuilder) : void
    {
        $phpFileLoader = new \ConfigTransformer202109040\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \ConfigTransformer202109040\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('config.php');
    }
}
