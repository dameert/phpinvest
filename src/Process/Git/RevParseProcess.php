<?php

declare(strict_types=1);

namespace PhpInvest\Process\Git;

use PhpInvest\Process\AbstractProcess;
use Symfony\Component\Process\Process;

final class RevParseProcess extends AbstractProcess
{
    private string $directory;
    private array $output;

    private function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->output = [];
    }

    public function __toString()
    {
        return implode(' ', $this->output);
    }

    public static function init(string $directory): RevParseProcess
    {
        return new static($directory);
    }

    protected function getCallback(): callable
    {
        return function ($type, $buffer) {
            if (Process::OUT === $type) {
                $this->output[] = trim($buffer);
            }
        };
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
