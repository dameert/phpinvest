<?php

declare(strict_types=1);

namespace PhpInvest\Model\Process;

final class GitRevParseProcess extends AbstractProcess
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    protected function getCommand(): array
    {
        return ['git', 'rev-parse', '--abbrev-ref', 'HEAD'];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
