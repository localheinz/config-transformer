<?php

declare (strict_types=1);
namespace ConfigTransformer202108055\Symplify\SymplifyKernel\DependencyInjection\Extension;

use ConfigTransformer202108055\Symfony\Component\Config\FileLocator;
use ConfigTransformer202108055\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer202108055\Symfony\Component\DependencyInjection\Extension\Extension;
use ConfigTransformer202108055\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class SymplifyKernelExtension extends \ConfigTransformer202108055\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function load($configs, $containerBuilder) : void
    {
        $phpFileLoader = new \ConfigTransformer202108055\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \ConfigTransformer202108055\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('common-config.php');
    }
}
