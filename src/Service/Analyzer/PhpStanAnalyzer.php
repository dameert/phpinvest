<?php

declare(strict_types=1);

namespace PhpInvest\Service\Analyzer;

use PhpInvest\Entity\Project;
use PhpInvest\Service\Invest\InvestService;

final class PhpStanAnalyzer
{
    private InvestService $investService;

    public function __construct(InvestService $investService)
    {
        $this->investService = $investService;
    }

    public function analyze(Project $project): void
    {
        $invest = $this->investService->getByProject($project);
        $directory = $invest->getCheckout()->getDirectory();
        $json = AnalyseProcess::init($directory, 7)->run()->getJson();
    }
}
