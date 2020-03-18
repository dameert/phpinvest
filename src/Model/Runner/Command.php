<?php

declare(strict_types=1);

namespace PhpInvest\Model\Runner;

use Symfony\Component\Process\Process;

final class Command
{
    private array $cmd;
    private ?string $directory;
    private float $timeout = 60.0;

    private function __construct()
    {
    }

    public function __invoke(): Process
    {
        return new Process($this->cmd, $this->directory, null, null, $this->timeout);
    }

    public static function make(array $cmd, string $directory = null, float $timeout = 60.0): self
    {
        $command = new static;
        $command->cmd = $cmd;
        $command->directory = $directory;
        $command->timeout = $timeout;

        return $command;
    }
}
