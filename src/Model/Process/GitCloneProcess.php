<?php

declare(strict_types=1);

namespace PhpInvest\Model\Process;

use PhpInvest\Entity\Project;

final class GitCloneProcess extends AbstractProcess
{
    private string $branch;
    private string $directory;
    private Project $project;

    public function __construct(string $branch, string $directory, Project $project)
    {
        $this->branch = $branch;
        $this->directory = $directory;
        $this->project = $project;
    }

    protected function getCommand(): array
    {
        return ['git', 'clone', '-b', $this->branch, $this->project->getSSH(), $this->directory];
    }
}
