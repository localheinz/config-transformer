<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202204182\Symfony\Component\Console;

use ConfigTransformer202204182\Symfony\Component\Console\Command\Command;
use ConfigTransformer202204182\Symfony\Component\Console\Input\InputInterface;
use ConfigTransformer202204182\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class SingleCommandApplication extends \ConfigTransformer202204182\Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    private $version = 'UNKNOWN';
    /**
     * @var bool
     */
    private $autoExit = \true;
    /**
     * @var bool
     */
    private $running = \false;
    /**
     * @return $this
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
        return $this;
    }
    /**
     * @final
     *
     * @return $this
     */
    public function setAutoExit(bool $autoExit)
    {
        $this->autoExit = $autoExit;
        return $this;
    }
    public function run(\ConfigTransformer202204182\Symfony\Component\Console\Input\InputInterface $input = null, \ConfigTransformer202204182\Symfony\Component\Console\Output\OutputInterface $output = null) : int
    {
        if ($this->running) {
            return parent::run($input, $output);
        }
        // We use the command name as the application name
        $application = new \ConfigTransformer202204182\Symfony\Component\Console\Application($this->getName() ?: 'UNKNOWN', $this->version);
        $application->setAutoExit($this->autoExit);
        // Fix the usage of the command displayed with "--help"
        $this->setName($_SERVER['argv'][0]);
        $application->add($this);
        $application->setDefaultCommand($this->getName(), \true);
        $this->running = \true;
        try {
            $ret = $application->run($input, $output);
        } finally {
            $this->running = \false;
        }
        return $ret ?? 1;
    }
}
