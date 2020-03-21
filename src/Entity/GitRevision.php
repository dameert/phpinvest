<?php

declare(strict_types=1);

namespace PhpInvest\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class GitRevision
{
    private string $author;
    private string $authorEmail;
    private string $branch;
    private \DateTimeImmutable $commitDate;
    private string $hash;
    private UuidInterface $id;
    private Project $project;

    public function __construct(
        string $author,
        string $authorEmail,
        string $branch,
        \DateTimeImmutable $commitDate,
        string $hash,
        Project $project
    ) {
        $this->author = $author;
        $this->authorEmail = $authorEmail;
        $this->branch = $branch;
        $this->commitDate = $commitDate;
        $this->hash = $hash;
        $this->id = Uuid::uuid4();
        $this->project = $project;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }
}
