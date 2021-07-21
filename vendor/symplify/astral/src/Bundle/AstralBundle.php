<?php

declare (strict_types=1);
namespace ConfigTransformer2021072110\Symplify\Astral\Bundle;

use ConfigTransformer2021072110\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer2021072110\Symfony\Component\HttpKernel\Bundle\Bundle;
use ConfigTransformer2021072110\Symplify\Astral\DependencyInjection\Extension\AstralExtension;
use ConfigTransformer2021072110\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
final class AstralBundle extends \ConfigTransformer2021072110\Symfony\Component\HttpKernel\Bundle\Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function build($containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \ConfigTransformer2021072110\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass());
    }
    protected function createContainerExtension() : ?\ConfigTransformer2021072110\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \ConfigTransformer2021072110\Symplify\Astral\DependencyInjection\Extension\AstralExtension();
    }
}
