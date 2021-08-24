<?php

declare (strict_types=1);
namespace ConfigTransformer202108241\Symplify\ComposerJsonManipulator\Bundle;

use ConfigTransformer202108241\Symfony\Component\HttpKernel\Bundle\Bundle;
use ConfigTransformer202108241\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends \ConfigTransformer202108241\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\ConfigTransformer202108241\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \ConfigTransformer202108241\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension();
    }
}
