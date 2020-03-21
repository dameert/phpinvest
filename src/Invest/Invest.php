<?php

declare(strict_types=1);

namespace PhpInvest\Invest;

use PhpInvest\Entity\Project;
use PhpInvest\Invest\Git\Checkout;

final class Invest
{
    private Checkout $checkout;
    private Project $project;

    public function __construct(Checkout $checkout, Project $project)
    {
        $this->checkout = $checkout;
        $this->project = $project;
    }

    public function getCheckout(): Checkout
    {
        return $this->checkout;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
