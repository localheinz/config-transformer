<?php

declare (strict_types=1);
namespace ConfigTransformer202206012\Symplify\SymplifyKernel\Contract;

use ConfigTransformer202206012\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \ConfigTransformer202206012\Psr\Container\ContainerInterface;
    public function getContainer() : \ConfigTransformer202206012\Psr\Container\ContainerInterface;
}
