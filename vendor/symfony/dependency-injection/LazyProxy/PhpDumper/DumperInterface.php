<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202201085\Symfony\Component\DependencyInjection\LazyProxy\PhpDumper;

use ConfigTransformer202201085\Symfony\Component\DependencyInjection\Definition;
/**
 * Lazy proxy dumper capable of generating the instantiation logic PHP code for proxied services.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
interface DumperInterface
{
    /**
     * Inspects whether the given definitions should produce proxy instantiation logic in the dumped container.
     */
    public function isProxyCandidate(\ConfigTransformer202201085\Symfony\Component\DependencyInjection\Definition $definition) : bool;
    /**
     * Generates the code to be used to instantiate a proxy in the dumped factory code.
     */
    public function getProxyFactoryCode(\ConfigTransformer202201085\Symfony\Component\DependencyInjection\Definition $definition, string $id, string $factoryCode) : string;
    /**
     * Generates the code for the lazy proxy.
     */
    public function getProxyCode(\ConfigTransformer202201085\Symfony\Component\DependencyInjection\Definition $definition) : string;
}
