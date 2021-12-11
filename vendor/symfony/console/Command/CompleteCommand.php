<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202112118\Symfony\Component\Console\Command;

use ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionInput;
use ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionSuggestions;
use ConfigTransformer202112118\Symfony\Component\Console\Completion\Output\BashCompletionOutput;
use ConfigTransformer202112118\Symfony\Component\Console\Completion\Output\CompletionOutputInterface;
use ConfigTransformer202112118\Symfony\Component\Console\Exception\CommandNotFoundException;
use ConfigTransformer202112118\Symfony\Component\Console\Exception\ExceptionInterface;
use ConfigTransformer202112118\Symfony\Component\Console\Input\InputInterface;
use ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption;
use ConfigTransformer202112118\Symfony\Component\Console\Output\OutputInterface;
/**
 * Responsible for providing the values to the shell completion.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
final class CompleteCommand extends \ConfigTransformer202112118\Symfony\Component\Console\Command\Command
{
    protected static $defaultName = '|_complete';
    protected static $defaultDescription = 'Internal command to provide shell completion suggestions';
    private $completionOutputs;
    private $isDebug = \false;
    /**
     * @param array<string, class-string<CompletionOutputInterface>> $completionOutputs A list of additional completion outputs, with shell name as key and FQCN as value
     */
    public function __construct(array $completionOutputs = [])
    {
        // must be set before the parent constructor, as the property value is used in configure()
        $this->completionOutputs = $completionOutputs + ['bash' => \ConfigTransformer202112118\Symfony\Component\Console\Completion\Output\BashCompletionOutput::class];
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->addOption('shell', 's', \ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'The shell type ("' . \implode('", "', \array_keys($this->completionOutputs)) . '")')->addOption('input', 'i', \ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED | \ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY, 'An array of input tokens (e.g. COMP_WORDS or argv)')->addOption('current', 'c', \ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'The index of the "input" array that the cursor is in (e.g. COMP_CWORD)')->addOption('symfony', 'S', \ConfigTransformer202112118\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'The version of the completion script');
    }
    protected function initialize(\ConfigTransformer202112118\Symfony\Component\Console\Input\InputInterface $input, \ConfigTransformer202112118\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->isDebug = \filter_var(\getenv('SYMFONY_COMPLETION_DEBUG'), \FILTER_VALIDATE_BOOLEAN);
    }
    protected function execute(\ConfigTransformer202112118\Symfony\Component\Console\Input\InputInterface $input, \ConfigTransformer202112118\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        try {
            // uncomment when a bugfix or BC break has been introduced in the shell completion scripts
            //$version = $input->getOption('symfony');
            //if ($version && version_compare($version, 'x.y', '>=')) {
            //    $message = sprintf('Completion script version is not supported ("%s" given, ">=x.y" required).', $version);
            //    $this->log($message);
            //    $output->writeln($message.' Install the Symfony completion script again by using the "completion" command.');
            //    return 126;
            //}
            $shell = $input->getOption('shell');
            if (!$shell) {
                throw new \RuntimeException('The "--shell" option must be set.');
            }
            if (!($completionOutput = $this->completionOutputs[$shell] ?? \false)) {
                throw new \RuntimeException(\sprintf('Shell completion is not supported for your shell: "%s" (supported: "%s").', $shell, \implode('", "', \array_keys($this->completionOutputs))));
            }
            $completionInput = $this->createCompletionInput($input);
            $suggestions = new \ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionSuggestions();
            $this->log(['', '<comment>' . \date('Y-m-d H:i:s') . '</>', '<info>Input:</> <comment>("|" indicates the cursor position)</>', '  ' . (string) $completionInput, '<info>Command:</>', '  ' . (string) \implode(' ', $_SERVER['argv']), '<info>Messages:</>']);
            $command = $this->findCommand($completionInput, $output);
            if (null === $command) {
                $this->log('  No command found, completing using the Application class.');
                $this->getApplication()->complete($completionInput, $suggestions);
            } elseif ($completionInput->mustSuggestArgumentValuesFor('command') && $command->getName() !== $completionInput->getCompletionValue()) {
                $this->log('  No command found, completing using the Application class.');
                // expand shortcut names ("cache:cl<TAB>") into their full name ("cache:clear")
                $suggestions->suggestValue($command->getName());
            } else {
                $command->mergeApplicationDefinition();
                $completionInput->bind($command->getDefinition());
                if (\ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionInput::TYPE_OPTION_NAME === $completionInput->getCompletionType()) {
                    $this->log('  Completing option names for the <comment>' . \get_class($command instanceof \ConfigTransformer202112118\Symfony\Component\Console\Command\LazyCommand ? $command->getCommand() : $command) . '</> command.');
                    $suggestions->suggestOptions($command->getDefinition()->getOptions());
                } else {
                    $this->log(['  Completing using the <comment>' . \get_class($command instanceof \ConfigTransformer202112118\Symfony\Component\Console\Command\LazyCommand ? $command->getCommand() : $command) . '</> class.', '  Completing <comment>' . $completionInput->getCompletionType() . '</> for <comment>' . $completionInput->getCompletionName() . '</>']);
                    if (null !== ($compval = $completionInput->getCompletionValue())) {
                        $this->log('  Current value: <comment>' . $compval . '</>');
                    }
                    $command->complete($completionInput, $suggestions);
                }
            }
            /** @var CompletionOutputInterface $completionOutput */
            $completionOutput = new $completionOutput();
            $this->log('<info>Suggestions:</>');
            if ($options = $suggestions->getOptionSuggestions()) {
                $this->log('  --' . \implode(' --', \array_map(function ($o) {
                    return $o->getName();
                }, $options)));
            } elseif ($values = $suggestions->getValueSuggestions()) {
                $this->log('  ' . \implode(' ', $values));
            } else {
                $this->log('  <comment>No suggestions were provided</>');
            }
            $completionOutput->write($suggestions, $output);
        } catch (\Throwable $e) {
            $this->log(['<error>Error!</error>', (string) $e]);
            if ($output->isDebug()) {
                throw $e;
            }
            return self::FAILURE;
        }
        return self::SUCCESS;
    }
    private function createCompletionInput(\ConfigTransformer202112118\Symfony\Component\Console\Input\InputInterface $input) : \ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionInput
    {
        $currentIndex = $input->getOption('current');
        if (!$currentIndex || !\ctype_digit($currentIndex)) {
            throw new \RuntimeException('The "--current" option must be set and it must be an integer.');
        }
        $completionInput = \ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionInput::fromTokens($input->getOption('input'), (int) $currentIndex);
        try {
            $completionInput->bind($this->getApplication()->getDefinition());
        } catch (\ConfigTransformer202112118\Symfony\Component\Console\Exception\ExceptionInterface $e) {
        }
        return $completionInput;
    }
    private function findCommand(\ConfigTransformer202112118\Symfony\Component\Console\Completion\CompletionInput $completionInput, \ConfigTransformer202112118\Symfony\Component\Console\Output\OutputInterface $output) : ?\ConfigTransformer202112118\Symfony\Component\Console\Command\Command
    {
        try {
            $inputName = $completionInput->getFirstArgument();
            if (null === $inputName) {
                return null;
            }
            return $this->getApplication()->find($inputName);
        } catch (\ConfigTransformer202112118\Symfony\Component\Console\Exception\CommandNotFoundException $e) {
        }
        return null;
    }
    private function log($messages) : void
    {
        if (!$this->isDebug) {
            return;
        }
        $commandName = \basename($_SERVER['argv'][0]);
        \file_put_contents(\sys_get_temp_dir() . '/sf_' . $commandName . '.log', \implode(\PHP_EOL, (array) $messages) . \PHP_EOL, \FILE_APPEND);
    }
}
