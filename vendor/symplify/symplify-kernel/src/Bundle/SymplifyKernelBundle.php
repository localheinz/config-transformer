<?php

declare (strict_types=1);
namespace ConfigTransformer202106281\Symplify\SymplifyKernel\Bundle;

use ConfigTransformer202106281\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer202106281\Symfony\Component\HttpKernel\Bundle\Bundle;
use ConfigTransformer202106281\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use ConfigTransformer202106281\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension;
final class SymplifyKernelBundle extends \ConfigTransformer202106281\Symfony\Component\HttpKernel\Bundle\Bundle
{
    public function build(\ConfigTransformer202106281\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \ConfigTransformer202106281\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass());
    }
    protected function createContainerExtension() : ?\ConfigTransformer202106281\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \ConfigTransformer202106281\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension();
    }
}
