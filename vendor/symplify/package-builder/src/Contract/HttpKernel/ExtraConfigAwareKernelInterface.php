<?php

declare (strict_types=1);
namespace ConfigTransformer2021072110\Symplify\PackageBuilder\Contract\HttpKernel;

use ConfigTransformer2021072110\Symfony\Component\HttpKernel\KernelInterface;
use ConfigTransformer2021072110\Symplify\SmartFileSystem\SmartFileInfo;
interface ExtraConfigAwareKernelInterface extends \ConfigTransformer2021072110\Symfony\Component\HttpKernel\KernelInterface
{
    /**
     * @param string[]|SmartFileInfo[] $configs
     */
    public function setConfigs($configs) : void;
}
