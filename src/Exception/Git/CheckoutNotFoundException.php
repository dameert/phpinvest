<?php

declare(strict_types=1);

namespace PhpInvest\Exception\Git;

use PhpInvest\Entity\Project;
use PhpInvest\Exception\InvestException;

final class CheckoutNotFoundException extends InvestException
{
    public function __construct(string $directory, Project $project)
    {
        parent::__construct(sprintf('Checkout not found for %s in %s (clone?)', $project, $directory));
    }
}
