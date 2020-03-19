<?php
declare(strict_types=1);

namespace PhpInvest\Exception\Git;

use PhpInvest\Entity\Project;

final class AlreadyClonedException extends \Exception
{
    public function __construct(Project $project, string $branch)
    {
        parent::__construct(sprintf('Project %s already cloned [branch %s]', $project, $branch));
    }
}
