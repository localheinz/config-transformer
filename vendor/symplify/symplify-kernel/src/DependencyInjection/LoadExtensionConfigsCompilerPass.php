<?php

declare (strict_types=1);
namespace ConfigTransformer202112118\Symplify\SymplifyKernel\DependencyInjection;

use ConfigTransformer202112118\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use ConfigTransformer202112118\Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Mimics @see \Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass without dependency on
 * symfony/http-kernel
 */
final class LoadExtensionConfigsCompilerPass extends \ConfigTransformer202112118\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass
{
    public function process(\ConfigTransformer202112118\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $extensionNames = \array_keys($containerBuilder->getExtensions());
        foreach ($extensionNames as $extensionName) {
            $containerBuilder->loadFromExtension($extensionName, []);
        }
        parent::process($containerBuilder);
    }
}
