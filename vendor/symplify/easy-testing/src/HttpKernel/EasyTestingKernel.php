<?php

declare (strict_types=1);
namespace ConfigTransformer202107081\Symplify\EasyTesting\HttpKernel;

use ConfigTransformer202107081\Symfony\Component\Config\Loader\LoaderInterface;
use ConfigTransformer202107081\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \ConfigTransformer202107081\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    public function registerContainerConfiguration(\ConfigTransformer202107081\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
