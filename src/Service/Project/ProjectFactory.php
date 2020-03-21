<?php

declare(strict_types=1);

namespace PhpInvest\Service\Project;

use PhpInvest\Entity\Project;
use PhpInvest\Exception\Project\AlreadyExistsException;
use PhpInvest\Invest\Git\Url;
use PhpInvest\Repository\ProjectRepository;

final class ProjectFactory
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Url $url, string $name): Project
    {
        $existingProject = $this->repository->findByName($name);

        if ($existingProject) {
            throw new AlreadyExistsException($existingProject);
        }

        $project = new Project(
            $url->getHost(),
            $name,
            $url->getOrganizationName(),
            $url->getRepositoryName()
        );

        $this->repository->save($project);

        return $project;
    }
}
