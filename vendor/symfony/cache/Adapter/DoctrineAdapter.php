<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer2021061810\Symfony\Component\Cache\Adapter;

use ConfigTransformer2021061810\Doctrine\Common\Cache\CacheProvider;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DoctrineAdapter extends \ConfigTransformer2021061810\Symfony\Component\Cache\Adapter\AbstractAdapter
{
    private $provider;
    public function __construct(\ConfigTransformer2021061810\Doctrine\Common\Cache\CacheProvider $provider, string $namespace = '', int $defaultLifetime = 0)
    {
        parent::__construct('', $defaultLifetime);
        $this->provider = $provider;
        $provider->setNamespace($namespace);
    }
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        parent::reset();
        $this->provider->setNamespace($this->provider->getNamespace());
    }
    /**
     * {@inheritdoc}
     */
    protected function doFetch(array $ids)
    {
        $unserializeCallbackHandler = \ini_set('unserialize_callback_func', parent::class . '::handleUnserializeCallback');
        try {
            return $this->provider->fetchMultiple($ids);
        } catch (\Error $e) {
            $trace = $e->getTrace();
            if (isset($trace[0]['function']) && !isset($trace[0]['class'])) {
                switch ($trace[0]['function']) {
                    case 'unserialize':
                    case 'apcu_fetch':
                    case 'apc_fetch':
                        throw new \ErrorException($e->getMessage(), $e->getCode(), \E_ERROR, $e->getFile(), $e->getLine());
                }
            }
            throw $e;
        } finally {
            \ini_set('unserialize_callback_func', $unserializeCallbackHandler);
        }
    }
    /**
     * {@inheritdoc}
     * @param string $id
     */
    protected function doHave($id)
    {
        return $this->provider->contains($id);
    }
    /**
     * {@inheritdoc}
     * @param string $namespace
     */
    protected function doClear($namespace)
    {
        $namespace = $this->provider->getNamespace();
        return isset($namespace[0]) ? $this->provider->deleteAll() : $this->provider->flushAll();
    }
    /**
     * {@inheritdoc}
     */
    protected function doDelete(array $ids)
    {
        $ok = \true;
        foreach ($ids as $id) {
            $ok = $this->provider->delete($id) && $ok;
        }
        return $ok;
    }
    /**
     * {@inheritdoc}
     * @param int $lifetime
     */
    protected function doSave(array $values, $lifetime)
    {
        return $this->provider->saveMultiple($values, $lifetime);
    }
}
