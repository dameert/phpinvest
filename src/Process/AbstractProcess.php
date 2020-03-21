<?php

declare(strict_types=1);

namespace PhpInvest\Process;

use Symfony\Component\Process\Process;

abstract class AbstractProcess
{
    public function run(): self
    {
        $process = new Process($this->getCommand(), $this->getDirectory(), null, null, $this->getTimeout());
        $process->disableOutput();
        $process->run($this->getCallback());

        return $this;
    }

    protected function getCallback(): callable
    {
        return static function ($type, $buffer) {
            echo $buffer;
        };
    }

    abstract protected function getCommand(): array;

    protected function getDirectory(): ?string
    {
        return null;
    }

    protected function getTimeout(): float
    {
        return 60.0;
    }
}
