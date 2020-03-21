<?php

declare(strict_types=1);

namespace PhpInvest\Service\Project;

use PhpInvest\Entity\Project;
use PhpInvest\Invest\Collection\ProjectCollection;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class ProjectChoice
{
    public const DESCRIPTION = 'Project name';

    public const NAME = 'project';
    private ProjectService $projectService;
    private QuestionHelper $questionHelper;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->questionHelper = new QuestionHelper();
    }

    public function interact(InputInterface $input, OutputInterface $output): ProjectCollection
    {
        $projects = $this->projectService->getAll();

        if ($projects->isEmpty()) {
            throw new \Exception('no projects');
        }

        $this->setArgument($projects, $input, $output);

        $argument = $input->getArgument(self::NAME);

        if ('all' !== $argument) {
            return $projects->filter(fn (Project $project) => $project->getName() === $argument);
        }

        return $projects;
    }

    private function setArgument(ProjectCollection $projects, InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument(self::NAME)) {
            return;
        }

        if (1 === $projects->count()) {
            $firstProject = $projects->first();
            $input->setArgument(self::NAME, $firstProject ? $firstProject->getName() : null);

            return;
        }

        $question = new ChoiceQuestion('Please select an project', ['all', ...$projects->getNames()], 0);
        $input->setArgument(self::NAME, $this->questionHelper->ask($input, $output, $question));
    }
}
