<?php

declare(strict_types=1);

namespace PhpInvest\Process\Git;

use PhpInvest\Process\AbstractProcess;

final class CheckoutProcess extends AbstractProcess
{
    private string $branch;
    private string $directory;

    private function __construct(string $branch, string $directory)
    {
        $this->branch = $branch;
        $this->directory = $directory;
    }

    public static function init(string $branch, string $directory): CheckoutProcess
    {
        return new static($branch, $directory);
    }

    protected function getCommand(): array
    {
        return ['git', 'checkout', $this->branch];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
