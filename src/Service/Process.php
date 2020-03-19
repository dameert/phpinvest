<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Model\Process\AbstractProcess;
use PhpInvest\Model\Process\Output;

final class Process
{
    public function stream(AbstractProcess $command): void
    {
        $command()->run(static function ($type, $buffer) {
            echo $buffer;
        });
    }

    public function run(AbstractProcess $command): Output
    {
        $process = $command();
        $process->run();

        return new Output($process->getIterator());
    }
}
