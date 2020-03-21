<?php

declare(strict_types=1);

namespace PhpInvest\Process\PhpStan;

use PhpInvest\Process\AbstractProcess;
use Symfony\Component\Process\Process;

final class AnalyseProcess extends AbstractProcess
{
    private string $directory;
    private array $json = [];
    private int $level;

    private function __construct(string $directory, int $level)
    {
        $this->directory = $directory;
        $this->level = $level;
    }

    public function getJson(): array
    {
        return $this->json;
    }

    public static function init(string $directory, int $level): AnalyseProcess
    {
        return new static($directory, $level);
    }

    protected function getCallback(): callable
    {
        return function ($type, $buffer) {
            if (Process::OUT === $type) {
                $this->saveJson($buffer);
            } else {
                echo $buffer;
            }
        };
    }

    protected function getCommand(): array
    {
        return [
            'vendor/bin/phpstan',
            'analyse',
            '--memory-limit=-1',
            '--error-format=json',
            sprintf('--level=%d', $this->level),
            '.'
        ];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }

    private function saveJson(string $buffer): void
    {
        try {
            $this->json = json_decode($buffer, true, 15, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return;
        }
    }
}
