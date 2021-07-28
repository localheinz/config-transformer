<?php

declare (strict_types=1);
namespace ConfigTransformer202107289\Symplify\ComposerJsonManipulator\Bundle;

use ConfigTransformer202107289\Symfony\Component\HttpKernel\Bundle\Bundle;
use ConfigTransformer202107289\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \ConfigTransformer202107289\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\ConfigTransformer202107289\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \ConfigTransformer202107289\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
