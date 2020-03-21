<?php

declare(strict_types=1);

namespace PhpInvest\Exception\Project;

use PhpInvest\Entity\Project;
use PhpInvest\Exception\InvestException;

final class AlreadyExistsException extends InvestException
{
    private Project $project;

    public function __construct(Project $project)
    {
        parent::__construct(sprintf('Project %s already exists ...', $project));
        $this->project = $project;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
