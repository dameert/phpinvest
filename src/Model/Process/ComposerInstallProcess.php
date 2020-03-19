<?php

declare(strict_types=1);

namespace PhpInvest\Model\Process;

final class ComposerInstallProcess extends AbstractProcess
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    protected function getCommand(): array
    {
        return ['composer', 'install'];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
