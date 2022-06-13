<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202206\Symfony\Component\Cache\Adapter;

use ConfigTransformer202206\Symfony\Component\Cache\Marshaller\MarshallerInterface;
use ConfigTransformer202206\Symfony\Component\Cache\Traits\RedisClusterProxy;
use ConfigTransformer202206\Symfony\Component\Cache\Traits\RedisProxy;
use ConfigTransformer202206\Symfony\Component\Cache\Traits\RedisTrait;
class RedisAdapter extends AbstractAdapter
{
    use RedisTrait;
    public function __construct(\Redis|\RedisArray|\RedisCluster|\ConfigTransformer202206\Predis\ClientInterface|RedisProxy|RedisClusterProxy $redis, string $namespace = '', int $defaultLifetime = 0, MarshallerInterface $marshaller = null)
    {
        $this->init($redis, $namespace, $defaultLifetime, $marshaller);
    }
}
