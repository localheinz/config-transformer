<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202112063\Symfony\Component\Config\Loader;

use ConfigTransformer202112063\Symfony\Component\Config\Exception\LoaderLoadException;
/**
 * Loader is the abstract class used by all built-in loaders.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Loader implements \ConfigTransformer202112063\Symfony\Component\Config\Loader\LoaderInterface
{
    protected $resolver;
    protected $env;
    public function __construct(string $env = null)
    {
        $this->env = $env;
    }
    /**
     * {@inheritdoc}
     */
    public function getResolver() : \ConfigTransformer202112063\Symfony\Component\Config\Loader\LoaderResolverInterface
    {
        return $this->resolver;
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Config\Loader\LoaderResolverInterface $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }
    /**
     * Imports a resource.
     *
     * @return mixed
     * @param mixed $resource
     * @param string|null $type
     */
    public function import($resource, $type = null)
    {
        return $this->resolve($resource, $type)->load($resource, $type);
    }
    /**
     * Finds a loader able to load an imported resource.
     *
     * @throws LoaderLoadException If no loader is found
     * @param mixed $resource
     * @param string|null $type
     */
    public function resolve($resource, $type = null) : \ConfigTransformer202112063\Symfony\Component\Config\Loader\LoaderInterface
    {
        if ($this->supports($resource, $type)) {
            return $this;
        }
        $loader = null === $this->resolver ? \false : $this->resolver->resolve($resource, $type);
        if (\false === $loader) {
            throw new \ConfigTransformer202112063\Symfony\Component\Config\Exception\LoaderLoadException($resource, null, 0, null, $type);
        }
        return $loader;
    }
}
