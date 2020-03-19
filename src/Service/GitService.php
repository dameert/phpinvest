<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Entity\Project;
use PhpInvest\Exception\Git\AlreadyClonedException;
use PhpInvest\Model\Process\ComposerInstallProcess;
use PhpInvest\Model\Process\GitCheckoutProcess;
use PhpInvest\Model\Process\GitCloneProcess;
use PhpInvest\Model\Process\GitRevParseProcess;

final class GitService
{
    private Filesystem $filesystem;
    private Process $process;

    public function __construct(Filesystem $filesystem, Process $process)
    {
        $this->filesystem = $filesystem;
        $this->process = $process;
    }

    public function clone(Project $project, string $branch): void
    {
        $directory = $this->filesystem->getCheckoutFolder($project);
        $currentBranch = $this->getBranch($project);

        if ($branch === $currentBranch) {
            throw new AlreadyClonedException($project, $branch);
        }

        if (null !== $currentBranch) {
            $this->process->stream(new GitCheckoutProcess($branch, $directory));

            return;
        }

        $this->process->stream(new GitCloneProcess($branch, $directory, $project));
        $this->process->stream(new ComposerInstallProcess($directory));
    }

    public function getBranch(Project $project): ?string
    {
        $directory = $this->filesystem->getCheckoutFolder($project);

        if (!$this->filesystem->exists($directory)) {
            return null;
        }

        return (string) $this->process->run(new GitRevParseProcess($directory));
    }
}
