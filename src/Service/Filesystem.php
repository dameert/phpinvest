<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Entity\Project;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

final class Filesystem
{
    private const CHECKOUT_FOLDER = 'checkout';
    private SymfonyFilesystem $filesystem;
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->filesystem = new SymfonyFilesystem();
        $this->projectDir = $projectDir;
    }

    public function exists(string $directory): bool
    {
        return $this->filesystem->exists($directory);
    }

    public function getCheckoutFolder(Project $project): string
    {
        return implode('/', [
            $this->projectDir,
            self::CHECKOUT_FOLDER,
            $project->getOrganizationName(),
            $project->getRepositoryName(),
        ]);
    }
}
