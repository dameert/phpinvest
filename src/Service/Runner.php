<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Model\Runner\Command;
use PhpInvest\Model\Runner\Output;

final class Runner
{
    public function stream(Command $command): void
    {
        $command()->run(static function ($type, $buffer) {
            echo $buffer;
        });
    }

    public function run(Command $command): Output
    {
        $process = $command();
        $process->run();

        return new Output($process->getIterator());
    }
}
