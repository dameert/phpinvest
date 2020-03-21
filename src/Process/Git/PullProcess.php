<?php

declare(strict_types=1);

namespace PhpInvest\Process\Git;

use PhpInvest\Process\AbstractProcess;

final class PullProcess extends AbstractProcess
{
    private string $directory;

    private function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public static function init(string $directory): PullProcess
    {
        return new static($directory);
    }

    protected function getCommand(): array
    {
        return ['git', 'pull'];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
