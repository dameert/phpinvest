<?php

declare(strict_types=1);

namespace PhpInvest\Model\Runner;

final class Output
{
    private array $lines;

    public function __construct(\Generator $output)
    {
        foreach ($output as $out) {
            $this->lines[] = trim($out);
        }
    }

    public function __toString(): string
    {
        return implode(' ', $this->lines);
    }
}
