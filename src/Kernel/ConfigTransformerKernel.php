<?php

declare (strict_types=1);
namespace ConfigTransformer202112063\Symplify\ConfigTransformer\Kernel;

use ConfigTransformer202112063\Psr\Container\ContainerInterface;
use ConfigTransformer202112063\Symplify\PhpConfigPrinter\ValueObject\PhpConfigPrinterConfig;
use ConfigTransformer202112063\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class ConfigTransformerKernel extends \ConfigTransformer202112063\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \ConfigTransformer202112063\Psr\Container\ContainerInterface
    {
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $configFiles[] = \ConfigTransformer202112063\Symplify\PhpConfigPrinter\ValueObject\PhpConfigPrinterConfig::FILE_PATH;
        return $this->create([], [], $configFiles);
    }
}
