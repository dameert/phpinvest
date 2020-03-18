<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Entity\Project;
use PhpInvest\Model\Runner\Command;

final class GitService
{
    private Filesystem $filesystem;
    private Runner $runner;

    public function __construct(Filesystem $filesystem, Runner $runner)
    {
        $this->filesystem = $filesystem;
        $this->runner = $runner;
    }

    public function clone(Project $project): void
    {
        $directory = $this->filesystem->getCheckoutFolder($project);

        if ($this->filesystem->exists($directory)) {
            throw new \LogicException(sprintf('Directory %s already exists', $directory));
        }

        $this->runner->stream(Command::make(['git', 'clone', $project->getSSH(), $directory]));
        $this->runner->stream(Command::make(['composer', 'install'], $directory));
    }

    public function getBranch(Project $project): ?string
    {
        $directory = $this->filesystem->getCheckoutFolder($project);

        if (!$this->filesystem->exists($directory)) {
            return 'X';
        }

        return (string) $this->runner->run(Command::make(['git', 'rev-parse', '--abbrev-ref', 'HEAD'], $directory));
    }
}
