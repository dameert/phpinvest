<?php

declare(strict_types=1);

namespace PhpInvest\Invest\Git;

final class Checkout
{
    private string $author;
    private string $authorEmail;
    private string $branch;
    private \DateTimeImmutable $commitDate;
    private string $directory;
    private string $hash;

    public function __construct(
        string $author,
        string $authorEmail,
        string $branch,
        \DateTimeImmutable $commitDate,
        string $directory,
        string $hash
    ) {
        $this->author = $author;
        $this->authorEmail = $authorEmail;
        $this->branch = $branch;
        $this->commitDate = $commitDate;
        $this->directory = $directory;
        $this->hash = $hash;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
