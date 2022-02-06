<?php

declare (strict_types=1);
namespace ConfigTransformer202202064\Symplify\EasyTesting\Kernel;

use ConfigTransformer202202064\Psr\Container\ContainerInterface;
use ConfigTransformer202202064\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use ConfigTransformer202202064\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \ConfigTransformer202202064\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \ConfigTransformer202202064\Psr\Container\ContainerInterface
    {
        $configFiles[] = \ConfigTransformer202202064\Symplify\EasyTesting\ValueObject\EasyTestingConfig::FILE_PATH;
        return $this->create($configFiles);
    }
}
