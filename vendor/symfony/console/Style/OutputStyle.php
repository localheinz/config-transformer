<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202202202\Symfony\Component\Console\Style;

use ConfigTransformer202202202\Symfony\Component\Console\Formatter\OutputFormatterInterface;
use ConfigTransformer202202202\Symfony\Component\Console\Helper\ProgressBar;
use ConfigTransformer202202202\Symfony\Component\Console\Output\ConsoleOutputInterface;
use ConfigTransformer202202202\Symfony\Component\Console\Output\OutputInterface;
/**
 * Decorates output to add console style guide helpers.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class OutputStyle implements \ConfigTransformer202202202\Symfony\Component\Console\Output\OutputInterface, \ConfigTransformer202202202\Symfony\Component\Console\Style\StyleInterface
{
    private $output;
    public function __construct(\ConfigTransformer202202202\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }
    /**
     * {@inheritdoc}
     */
    public function newLine(int $count = 1)
    {
        $this->output->write(\str_repeat(\PHP_EOL, $count));
    }
    public function createProgressBar(int $max = 0) : \ConfigTransformer202202202\Symfony\Component\Console\Helper\ProgressBar
    {
        return new \ConfigTransformer202202202\Symfony\Component\Console\Helper\ProgressBar($this->output, $max);
    }
    /**
     * {@inheritdoc}
     * @param mixed[]|string $messages
     */
    public function write($messages, bool $newline = \false, int $type = self::OUTPUT_NORMAL)
    {
        $this->output->write($messages, $newline, $type);
    }
    /**
     * {@inheritdoc}
     * @param mixed[]|string $messages
     */
    public function writeln($messages, int $type = self::OUTPUT_NORMAL)
    {
        $this->output->writeln($messages, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function setVerbosity(int $level)
    {
        $this->output->setVerbosity($level);
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity() : int
    {
        return $this->output->getVerbosity();
    }
    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated)
    {
        $this->output->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated() : bool
    {
        return $this->output->isDecorated();
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\ConfigTransformer202202202\Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter)
    {
        $this->output->setFormatter($formatter);
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter() : \ConfigTransformer202202202\Symfony\Component\Console\Formatter\OutputFormatterInterface
    {
        return $this->output->getFormatter();
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet() : bool
    {
        return $this->output->isQuiet();
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose() : bool
    {
        return $this->output->isVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose() : bool
    {
        return $this->output->isVeryVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug() : bool
    {
        return $this->output->isDebug();
    }
    protected function getErrorOutput()
    {
        if (!$this->output instanceof \ConfigTransformer202202202\Symfony\Component\Console\Output\ConsoleOutputInterface) {
            return $this->output;
        }
        return $this->output->getErrorOutput();
    }
}
