<?php

declare (strict_types=1);
namespace ConfigTransformer202202209\Symplify\SymplifyKernel\Contract;

use ConfigTransformer202202209\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \ConfigTransformer202202209\Psr\Container\ContainerInterface;
    public function getContainer() : \ConfigTransformer202202209\Psr\Container\ContainerInterface;
}
