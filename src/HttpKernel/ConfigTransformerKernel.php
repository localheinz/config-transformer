<?php

declare (strict_types=1);
namespace ConfigTransformer202111016\Symplify\ConfigTransformer\HttpKernel;

use ConfigTransformer202111016\Psr\Container\ContainerInterface;
use ConfigTransformer202111016\Symfony\Component\DependencyInjection\Container;
use ConfigTransformer202111016\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use ConfigTransformer202111016\Symplify\ConfigTransformer\Exception\ShouldNotHappenException;
use ConfigTransformer202111016\Symplify\PhpConfigPrinter\DependencyInjection\Extension\PhpConfigPrinterExtension;
use ConfigTransformer202111016\Symplify\SymfonyContainerBuilder\ContainerBuilderFactory;
use ConfigTransformer202111016\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use ConfigTransformer202111016\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension;
final class ConfigTransformerKernel implements \ConfigTransformer202111016\Symplify\SymplifyKernel\Contract\LightKernelInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container|null
     */
    private $container = null;
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \ConfigTransformer202111016\Psr\Container\ContainerInterface
    {
        $containerBuilderFactory = new \ConfigTransformer202111016\Symplify\SymfonyContainerBuilder\ContainerBuilderFactory();
        $extensions = [new \ConfigTransformer202111016\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension(), new \ConfigTransformer202111016\Symplify\PhpConfigPrinter\DependencyInjection\Extension\PhpConfigPrinterExtension()];
        $compilerPasses = [new \ConfigTransformer202111016\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass()];
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $containerBuilder = $containerBuilderFactory->create($extensions, $compilerPasses, $configFiles);
        $containerBuilder->compile();
        $this->container = $containerBuilder;
        return $containerBuilder;
    }
    public function getContainer() : \ConfigTransformer202111016\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof \ConfigTransformer202111016\Symfony\Component\DependencyInjection\Container) {
            throw new \ConfigTransformer202111016\Symplify\ConfigTransformer\Exception\ShouldNotHappenException();
        }
        return $this->container;
    }
}
