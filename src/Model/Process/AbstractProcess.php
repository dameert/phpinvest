<?php

declare(strict_types=1);

namespace PhpInvest\Model\Process;

use Symfony\Component\Process\Process;

abstract class AbstractProcess
{
    public function __invoke()
    {
        return new Process($this->getCommand(), $this->getDirectory(), null, null, $this->getTimeout());
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
