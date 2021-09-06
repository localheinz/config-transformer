<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202109060\Symfony\Component\HttpKernel\Debug;

use ConfigTransformer202109060\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher as BaseTraceableEventDispatcher;
use ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents;
/**
 * Collects some data about event listeners.
 *
 * This event dispatcher delegates the dispatching to another one.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TraceableEventDispatcher extends \ConfigTransformer202109060\Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher
{
    /**
     * {@inheritdoc}
     * @param object $event
     * @param string $eventName
     */
    protected function beforeDispatch($eventName, $event)
    {
        switch ($eventName) {
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::REQUEST:
                $event->getRequest()->attributes->set('_stopwatch_token', \substr(\hash('sha256', \uniqid(\mt_rand(), \true)), 0, 6));
                $this->stopwatch->openSection();
                break;
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::VIEW:
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::RESPONSE:
                // stop only if a controller has been executed
                if ($this->stopwatch->isStarted('controller')) {
                    $this->stopwatch->stop('controller');
                }
                break;
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::TERMINATE:
                $sectionId = $event->getRequest()->attributes->get('_stopwatch_token');
                if (null === $sectionId) {
                    break;
                }
                // There is a very special case when using built-in AppCache class as kernel wrapper, in the case
                // of an ESI request leading to a `stale` response [B]  inside a `fresh` cached response [A].
                // In this case, `$token` contains the [B] debug token, but the  open `stopwatch` section ID
                // is equal to the [A] debug token. Trying to reopen section with the [B] token throws an exception
                // which must be caught.
                try {
                    $this->stopwatch->openSection($sectionId);
                } catch (\LogicException $e) {
                }
                break;
        }
    }
    /**
     * {@inheritdoc}
     * @param object $event
     * @param string $eventName
     */
    protected function afterDispatch($eventName, $event)
    {
        switch ($eventName) {
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::CONTROLLER_ARGUMENTS:
                $this->stopwatch->start('controller', 'section');
                break;
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::RESPONSE:
                $sectionId = $event->getRequest()->attributes->get('_stopwatch_token');
                if (null === $sectionId) {
                    break;
                }
                $this->stopwatch->stopSection($sectionId);
                break;
            case \ConfigTransformer202109060\Symfony\Component\HttpKernel\KernelEvents::TERMINATE:
                // In the special case described in the `preDispatch` method above, the `$token` section
                // does not exist, then closing it throws an exception which must be caught.
                $sectionId = $event->getRequest()->attributes->get('_stopwatch_token');
                if (null === $sectionId) {
                    break;
                }
                try {
                    $this->stopwatch->stopSection($sectionId);
                } catch (\LogicException $e) {
                }
                break;
        }
    }
}
