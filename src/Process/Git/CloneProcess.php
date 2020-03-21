<?php

declare(strict_types=1);

namespace PhpInvest\Process\Git;

use PhpInvest\Entity\Project;
use PhpInvest\Process\AbstractProcess;

final class CloneProcess extends AbstractProcess
{
    private string $branch;
    private string $directory;
    private Project $project;

    private function __construct(string $branch, string $directory, Project $project)
    {
        $this->branch = $branch;
        $this->directory = $directory;
        $this->project = $project;
    }

    public static function init(string $branch, string $directory, Project $project): CloneProcess
    {
        return new static($branch, $directory, $project);
    }

    protected function getCommand(): array
    {
        return ['git', 'clone', '-b', $this->branch, $this->project->getSsh(), $this->directory];
    }
}
