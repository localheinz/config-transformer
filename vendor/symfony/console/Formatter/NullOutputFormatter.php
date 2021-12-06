<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202112063\Symfony\Component\Console\Formatter;

/**
 * @author Tien Xuan Vo <tien.xuan.vo@gmail.com>
 */
final class NullOutputFormatter implements \ConfigTransformer202112063\Symfony\Component\Console\Formatter\OutputFormatterInterface
{
    /**
     * @var \Symfony\Component\Console\Formatter\NullOutputFormatterStyle
     */
    private $style;
    /**
     * {@inheritdoc}
     * @param string|null $message
     */
    public function format($message) : ?string
    {
        return null;
    }
    /**
     * {@inheritdoc}
     * @param string $name
     */
    public function getStyle($name) : \ConfigTransformer202112063\Symfony\Component\Console\Formatter\OutputFormatterStyleInterface
    {
        // to comply with the interface we must return a OutputFormatterStyleInterface
        return $this->style ?? ($this->style = new \ConfigTransformer202112063\Symfony\Component\Console\Formatter\NullOutputFormatterStyle());
    }
    /**
     * {@inheritdoc}
     * @param string $name
     */
    public function hasStyle($name) : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated() : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     * @param bool $decorated
     */
    public function setDecorated($decorated) : void
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     * @param string $name
     * @param \Symfony\Component\Console\Formatter\OutputFormatterStyleInterface $style
     */
    public function setStyle($name, $style) : void
    {
        // do nothing
    }
}
