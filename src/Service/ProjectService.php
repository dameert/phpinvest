<?php

declare(strict_types=1);

namespace PhpInvest\Service;

use Doctrine\Common\Collections\ArrayCollection;
use PhpInvest\Entity\Project;
use PhpInvest\Exception\Project\AlreadyExistsException;
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

    public function getByName(string $organizationName, string $repositoryName): ?Project
    {
        return $this->repository->findByNames($organizationName, $repositoryName);
    }

    public function getByNames(string $organizationName, string $repositoryName = null): array
    {
        return $this->repository->findAllByNames($organizationName, $repositoryName);
    }

    public function createFromGitUrl(GitUrl $gitUrl): Project
    {
        $existingProject = $this->getByName($gitUrl->getOrganizationName(), $gitUrl->getRepositoryName());

        if ($existingProject) {
            throw new AlreadyExistsException($existingProject);
        }

        $project = new Project(
            $gitUrl->getHost(),
            $gitUrl->getOrganizationName(),
            $gitUrl->getRepositoryName()
        );

        $this->repository->save($project);

        return $project;
    }
}
