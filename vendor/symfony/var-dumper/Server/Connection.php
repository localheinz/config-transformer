<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202109151\Symfony\Component\VarDumper\Server;

use ConfigTransformer202109151\Symfony\Component\VarDumper\Cloner\Data;
use ConfigTransformer202109151\Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;
/**
 * Forwards serialized Data clones to a server.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class Connection
{
    private $host;
    private $contextProviders;
    private $socket;
    /**
     * @param string                     $host             The server host
     * @param ContextProviderInterface[] $contextProviders Context providers indexed by context name
     */
    public function __construct(string $host, array $contextProviders = [])
    {
        if (\strpos($host, '://') === \false) {
            $host = 'tcp://' . $host;
        }
        $this->host = $host;
        $this->contextProviders = $contextProviders;
    }
    public function getContextProviders() : array
    {
        return $this->contextProviders;
    }
    /**
     * @param \Symfony\Component\VarDumper\Cloner\Data $data
     */
    public function write($data) : bool
    {
        $socketIsFresh = !$this->socket;
        if (!($this->socket = $this->socket ?: $this->createSocket())) {
            return \false;
        }
        $context = ['timestamp' => \microtime(\true)];
        foreach ($this->contextProviders as $name => $provider) {
            $context[$name] = $provider->getContext();
        }
        $context = \array_filter($context);
        $encodedPayload = \base64_encode(\serialize([$data, $context])) . "\n";
        \set_error_handler([self::class, 'nullErrorHandler']);
        try {
            if (-1 !== \stream_socket_sendto($this->socket, $encodedPayload)) {
                return \true;
            }
            if (!$socketIsFresh) {
                \stream_socket_shutdown($this->socket, \STREAM_SHUT_RDWR);
                \fclose($this->socket);
                $this->socket = $this->createSocket();
            }
            if (-1 !== \stream_socket_sendto($this->socket, $encodedPayload)) {
                return \true;
            }
        } finally {
            \restore_error_handler();
        }
        return \false;
    }
    private static function nullErrorHandler(int $t, string $m)
    {
        // no-op
    }
    private function createSocket()
    {
        \set_error_handler([self::class, 'nullErrorHandler']);
        try {
            return \stream_socket_client($this->host, $errno, $errstr, 3, \STREAM_CLIENT_CONNECT | \STREAM_CLIENT_ASYNC_CONNECT);
        } finally {
            \restore_error_handler();
        }
    }
}
