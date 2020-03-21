<?php

declare(strict_types=1);

namespace PhpInvest\Process\Git;

use PhpInvest\Process\AbstractProcess;
use Symfony\Component\Process\Process;

final class LogProcess extends AbstractProcess
{
    public const VALUE_AUTHOR = '%an';
    public const VALUE_AUTHOR_EMAIL = '%ae';
    public const VALUE_DATE = '%ad';
    public const VALUE_HASH = '%H';
    public const VALUE_SUBJECT = '%s';

    private const FORMAT = [
        self::VALUE_DATE,
        self::VALUE_AUTHOR,
        self::VALUE_AUTHOR_EMAIL,
        self::VALUE_HASH,
        self::VALUE_SUBJECT,
    ];
    private const SEPARATOR = '|#|';

    private string $directory;
    private array $log;

    private function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->log = [];
    }

    public function get(string $value): ?string
    {
        return $this->log[$value] ?? null;
    }

    public function getDate(): \DateTimeImmutable
    {
        $date = $this->log[self::VALUE_DATE];

        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s O', $date);
    }

    public static function init(string $directory): LogProcess
    {
        return new static($directory);
    }

    protected function getCallback(): callable
    {
        return function ($type, $buffer) {
            if (Process::OUT === $type && empty($this->log)) {
                $log = \explode(self::SEPARATOR, $buffer);
                $this->log = array_combine(self::FORMAT, $log);
            }
        };
    }

    protected function getCommand(): array
    {
        $format = sprintf('--format=%s', implode(self::SEPARATOR, self::FORMAT));

        return ['git', 'log', '-1', $format, '--date=iso'];
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
