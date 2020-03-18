<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use Doctrine\Common\Collections\ArrayCollection;
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

    public function getAll(): ArrayCollection
    {
        return $this->repository->findAll();
    }

    public function getAllOrganizationNames(): array
    {
        return $this->repository->findAllOrganizationNames();
    }

    public function getByNames(string $organizationName, string $repositoryName = null): array
    {
        return $this->repository->findAllByNames($organizationName, $repositoryName);
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
