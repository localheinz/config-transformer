<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202202248\Symfony\Component\Console\Descriptor;

use ConfigTransformer202202248\Symfony\Component\Console\Output\OutputInterface;
/**
 * Descriptor interface.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface DescriptorInterface
{
    /**
     * @param object $object
     */
    public function describe(\ConfigTransformer202202248\Symfony\Component\Console\Output\OutputInterface $output, $object, array $options = []);
}
