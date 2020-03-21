<?php

declare(strict_types=1);

namespace PhpInvest\Process;

use Symfony\Component\Process\Process;

final class Output
{
    private \Generator $output;

    public function __construct(\Generator $output)
    {
        $this->output = $output;
    }

    public function __toString(): string
    {
        $stringOutput = '';

        foreach ($this->output as $type => $out) {
            if (Process::OUT === $type) {
                $stringOutput .= sprintf(' %s', $out);
            }
        }

        return trim($stringOutput);
    }
}
