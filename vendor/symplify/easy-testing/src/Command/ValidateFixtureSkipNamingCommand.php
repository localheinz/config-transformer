<?php

declare (strict_types=1);
namespace ConfigTransformer2021080410\Symplify\EasyTesting\Command;

use ConfigTransformer2021080410\Symfony\Component\Console\Input\InputArgument;
use ConfigTransformer2021080410\Symfony\Component\Console\Input\InputInterface;
use ConfigTransformer2021080410\Symfony\Component\Console\Output\OutputInterface;
use ConfigTransformer2021080410\Symplify\EasyTesting\Finder\FixtureFinder;
use ConfigTransformer2021080410\Symplify\EasyTesting\MissplacedSkipPrefixResolver;
use ConfigTransformer2021080410\Symplify\EasyTesting\ValueObject\Option;
use ConfigTransformer2021080410\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use ConfigTransformer2021080410\Symplify\PackageBuilder\Console\ShellCode;
final class ValidateFixtureSkipNamingCommand extends \ConfigTransformer2021080410\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\EasyTesting\MissplacedSkipPrefixResolver
     */
    private $missplacedSkipPrefixResolver;
    /**
     * @var \Symplify\EasyTesting\Finder\FixtureFinder
     */
    private $fixtureFinder;
    public function __construct(\ConfigTransformer2021080410\Symplify\EasyTesting\MissplacedSkipPrefixResolver $missplacedSkipPrefixResolver, \ConfigTransformer2021080410\Symplify\EasyTesting\Finder\FixtureFinder $fixtureFinder)
    {
        $this->missplacedSkipPrefixResolver = $missplacedSkipPrefixResolver;
        $this->fixtureFinder = $fixtureFinder;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->addArgument(\ConfigTransformer2021080410\Symplify\EasyTesting\ValueObject\Option::SOURCE, \ConfigTransformer2021080410\Symfony\Component\Console\Input\InputArgument::REQUIRED | \ConfigTransformer2021080410\Symfony\Component\Console\Input\InputArgument::IS_ARRAY, 'Paths to analyse');
        $this->setDescription('Check that skipped fixture files (without `-----` separator) have a "skip" prefix');
    }
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute($input, $output) : int
    {
        $source = (array) $input->getArgument(\ConfigTransformer2021080410\Symplify\EasyTesting\ValueObject\Option::SOURCE);
        $fixtureFileInfos = $this->fixtureFinder->find($source);
        $missplacedFixtureFileInfos = $this->missplacedSkipPrefixResolver->resolve($fixtureFileInfos);
        if ($missplacedFixtureFileInfos === []) {
            $message = \sprintf('All %d fixture files have valid names', \count($fixtureFileInfos));
            $this->symfonyStyle->success($message);
            return \ConfigTransformer2021080410\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
        }
        foreach ($missplacedFixtureFileInfos['incorrect_skips'] as $missplacedFixtureFileInfo) {
            $errorMessage = \sprintf('The file "%s" should drop the "skip/keep" prefix', $missplacedFixtureFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($errorMessage);
        }
        foreach ($missplacedFixtureFileInfos['missing_skips'] as $missplacedFixtureFileInfo) {
            $errorMessage = \sprintf('The file "%s" should start with "skip/keep" prefix', $missplacedFixtureFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($errorMessage);
        }
        $countError = \count($missplacedFixtureFileInfos['incorrect_skips']) + \count($missplacedFixtureFileInfos['missing_skips']);
        if ($countError === 0) {
            $message = \sprintf('All %d fixture files have valid names', \count($fixtureFileInfos));
            $this->symfonyStyle->success($message);
            return \ConfigTransformer2021080410\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
        }
        $errorMessage = \sprintf('Found %d test file fixtures with wrong prefix', $countError);
        $this->symfonyStyle->error($errorMessage);
        return \ConfigTransformer2021080410\Symplify\PackageBuilder\Console\ShellCode::ERROR;
    }
}
