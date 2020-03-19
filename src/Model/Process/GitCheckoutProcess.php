<?php

declare(strict_types=1);

namespace PhpInvest\Model\Process;

final class GitCheckoutProcess extends AbstractProcess
{
    private string $branch;
    private string $directory;

    public function __construct(string $branch, string $directory)
    {
        $this->branch = $branch;
        $this->directory = $directory;
    }

    protected function getCommand(): array
    {
        return ['git', 'checkout', '--track', sprintf('origin/%s', $this->branch)];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
