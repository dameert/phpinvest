<?php

declare(strict_types=1);

namespace PhpInvest\Command\Helper;

use Symfony\Component\Process\Process;

final class ProcessRunner
{
    public static function run(Process $process): void
    {
        $process->run(static function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo $buffer;
            } else {
                echo $buffer;
            }
        });
    }
}
