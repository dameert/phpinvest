<?php

declare(strict_types=1);

namespace PhpInvest\Service\Invest;

use PhpInvest\Entity\Project;
use PhpInvest\Invest\Invest;
use PhpInvest\Service\Git\GitService;

final class InvestFactory
{
    private GitService $gitService;

    public function __construct(GitService $gitService)
    {
        $this->gitService = $gitService;
    }

    public function createByProject(Project $project): Invest
    {
        $checkout = $this->gitService->getCheckout($project);

        return new Invest($checkout, $project);
    }
}
