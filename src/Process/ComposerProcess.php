<?php

declare(strict_types=1);

namespace PhpInvest\Process;

final class ComposerProcess extends AbstractProcess
{
    private string $action;
    private string $directory;

    private function __construct(string $action, string $directory)
    {
        $this->action = $action;
        $this->directory = $directory;
    }

    public static function install(string $directory): ComposerProcess
    {
        return new static('install', $directory);
    }

    public static function update(string $directory): ComposerProcess
    {
        return new static('update', $directory);
    }

    protected function getCommand(): array
    {
        return ['composer', $this->action];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }

    protected function getTimeout(): float
    {
        return 300.00;
    }
}
