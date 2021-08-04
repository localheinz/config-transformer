<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202108045\Symfony\Component\HttpKernel\Bundle;

use ConfigTransformer202108045\Symfony\Component\Console\Application;
use ConfigTransformer202108045\Symfony\Component\DependencyInjection\Container;
use ConfigTransformer202108045\Symfony\Component\DependencyInjection\ContainerAwareTrait;
use ConfigTransformer202108045\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer202108045\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
/**
 * An implementation of BundleInterface that adds a few conventions for DependencyInjection extensions.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Bundle implements \ConfigTransformer202108045\Symfony\Component\HttpKernel\Bundle\BundleInterface
{
    use ContainerAwareTrait;
    protected $name;
    protected $extension;
    protected $path;
    private $namespace;
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
    }
    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
    }
    /**
     * {@inheritdoc}
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build($container)
    {
    }
    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws \LogicException
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();
            if (null !== $extension) {
                if (!$extension instanceof \ConfigTransformer202108045\Symfony\Component\DependencyInjection\Extension\ExtensionInterface) {
                    throw new \LogicException(\sprintf('Extension "%s" must implement Symfony\\Component\\DependencyInjection\\Extension\\ExtensionInterface.', \get_debug_type($extension)));
                }
                // check naming convention
                $basename = \preg_replace('/Bundle$/', '', $this->getName());
                $expectedAlias = \ConfigTransformer202108045\Symfony\Component\DependencyInjection\Container::underscore($basename);
                if ($expectedAlias != $extension->getAlias()) {
                    throw new \LogicException(\sprintf('Users will expect the alias of the default extension of a bundle to be the underscored version of the bundle name ("%s"). You can override "Bundle::getContainerExtension()" if you want to use "%s" or another alias.', $expectedAlias, $extension->getAlias()));
                }
                $this->extension = $extension;
            } else {
                $this->extension = \false;
            }
        }
        return $this->extension ?: null;
    }
    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        if (null === $this->namespace) {
            $this->parseClassName();
        }
        return $this->namespace;
    }
    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        if (null === $this->path) {
            $reflected = new \ReflectionObject($this);
            $this->path = \dirname($reflected->getFileName());
        }
        return $this->path;
    }
    /**
     * Returns the bundle name (the class short name).
     */
    public final function getName() : string
    {
        if (null === $this->name) {
            $this->parseClassName();
        }
        return $this->name;
    }
    /**
     * @param \Symfony\Component\Console\Application $application
     */
    public function registerCommands($application)
    {
    }
    /**
     * Returns the bundle's container extension class.
     *
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        $basename = \preg_replace('/Bundle$/', '', $this->getName());
        return $this->getNamespace() . '\\DependencyInjection\\' . $basename . 'Extension';
    }
    /**
     * Creates the bundle's container extension.
     *
     * @return ExtensionInterface|null
     */
    protected function createContainerExtension()
    {
        return \class_exists($class = $this->getContainerExtensionClass()) ? new $class() : null;
    }
    private function parseClassName()
    {
        $pos = \strrpos(static::class, '\\');
        $this->namespace = \false === $pos ? '' : \substr(static::class, 0, $pos);
        if (null === $this->name) {
            $this->name = \false === $pos ? static::class : \substr(static::class, $pos + 1);
        }
    }
}
