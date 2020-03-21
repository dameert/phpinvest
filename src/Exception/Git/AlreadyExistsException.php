<?php

declare(strict_types=1);

namespace PhpInvest\Exception\Git;

use PhpInvest\Entity\Project;
use PhpInvest\Exception\InvestException;
use PhpInvest\Invest\Git\Checkout;

final class AlreadyExistsException extends InvestException
{
    public function __construct(Checkout $checkout, Project $project)
    {
        parent::__construct(sprintf('Project %s already on branch %s', $project->getName(), $checkout->getBranch()));
    }
}
