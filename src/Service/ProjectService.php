<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use PhpInvest\Entity\Project;
use PhpInvest\Model\GitUrl;
use PhpInvest\Repository\ProjectRepository;

final class ProjectService
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByNames(string $organizationName, string $repositoryName = null): array
    {
        return $this->repository->findAllByNames($organizationName, $repositoryName);
    }

    public function getAllOrganizationNames(): array
    {
        return $this->repository->findAllOrganizationNames();
    }

    public function getAllRepositoryNames(): array
    {
        return $this->repository->findAllRepositoryNames();
    }

    public function createFromGitUrl(GitUrl $gitUrl): Project
    {
        $project = new Project(
            $gitUrl->getHost(),
            $gitUrl->getOrganizationName(),
            $gitUrl->getRepositoryName()
        );

        $this->repository->save($project);

        return $project;
    }

}
