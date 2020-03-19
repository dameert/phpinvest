<?php

declare(strict_types=1);

namespace PhpInvest\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

final class CommandRunner
{
    public static function run(Application $application, OutputInterface $output, string $name, array $args): int
    {
        return $application->find($name)->run(new ArrayInput($args), $output);
    }
}
