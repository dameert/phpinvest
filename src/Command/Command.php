<?php

declare(strict_types=1);

namespace PhpInvest\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command extends SymfonyCommand
{
    protected SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function runCommand(OutputInterface $output, string $name, array $args): int
    {
        $application = $this->getApplication();

        return $application ? $application->find($name)->run(new ArrayInput($args), $output) : 0;
    }
}
