<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202112089\Symfony\Component\DependencyInjection\Compiler;

use ConfigTransformer202112089\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer202112089\Symfony\Component\DependencyInjection\Definition;
use ConfigTransformer202112089\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use ConfigTransformer202112089\Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
/**
 * Resolves all parameter placeholders "%somevalue%" to their real values.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ResolveParameterPlaceHoldersPass extends \ConfigTransformer202112089\Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass
{
    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    private $bag;
    /**
     * @var bool
     */
    private $resolveArrays = \true;
    /**
     * @var bool
     */
    private $throwOnResolveException = \true;
    public function __construct(bool $resolveArrays = \true, bool $throwOnResolveException = \true)
    {
        $this->resolveArrays = $resolveArrays;
        $this->throwOnResolveException = $throwOnResolveException;
    }
    /**
     * {@inheritdoc}
     *
     * @throws ParameterNotFoundException
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process($container)
    {
        $this->bag = $container->getParameterBag();
        try {
            parent::process($container);
            $aliases = [];
            foreach ($container->getAliases() as $name => $target) {
                $this->currentId = $name;
                $aliases[$this->bag->resolveValue($name)] = $target;
            }
            $container->setAliases($aliases);
        } catch (\ConfigTransformer202112089\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException $e) {
            $e->setSourceId($this->currentId);
            throw $e;
        }
        $this->bag->resolve();
        unset($this->bag);
    }
    /**
     * @param mixed $value
     * @return mixed
     * @param bool $isRoot
     */
    protected function processValue($value, $isRoot = \false)
    {
        if (\is_string($value)) {
            try {
                $v = $this->bag->resolveValue($value);
            } catch (\ConfigTransformer202112089\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException $e) {
                if ($this->throwOnResolveException) {
                    throw $e;
                }
                $v = null;
                $this->container->getDefinition($this->currentId)->addError($e->getMessage());
            }
            return $this->resolveArrays || !$v || !\is_array($v) ? $v : $value;
        }
        if ($value instanceof \ConfigTransformer202112089\Symfony\Component\DependencyInjection\Definition) {
            $value->setBindings($this->processValue($value->getBindings()));
            $changes = $value->getChanges();
            if (isset($changes['class'])) {
                $value->setClass($this->bag->resolveValue($value->getClass()));
            }
            if (isset($changes['file'])) {
                $value->setFile($this->bag->resolveValue($value->getFile()));
            }
        }
        $value = parent::processValue($value, $isRoot);
        if ($value && \is_array($value)) {
            $value = \array_combine($this->bag->resolveValue(\array_keys($value)), $value);
        }
        return $value;
    }
}
