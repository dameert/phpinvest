<?php

declare(strict_types=1);

namespace PhpInvest\Service\Invest;

use PhpInvest\Entity\Project;
use PhpInvest\Invest\Invest;

final class InvestService
{
    private InvestFactory $investigateFactory;

    public function __construct(InvestFactory $investigateFactory)
    {
        $this->investigateFactory = $investigateFactory;
    }

    public function getByProject(Project $project): Invest
    {
        return $this->investigateFactory->createByProject($project);
    }
}
