<?php

declare(strict_types=1);

namespace PhpInvest\Command\Git;

use PhpInvest\Command\Interactor;
use PhpInvest\Entity\Project;
use PhpInvest\Service\GitService;
use PhpInvest\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CloneCommand extends Command
{
    private const ARG_ORGANIZATION_NAME = 'organization_name';
    private const ARG_REPOSITORY_NAME = 'repository_name';
    private GitService $gitService;
    private ProjectService $projectService;

    public function __construct(GitService $gitService, ProjectService $gitProjectService)
    {
        parent::__construct('pi:git:clone');

        $this->gitService = $gitService;
        $this->projectService = $gitProjectService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Git clone project')
            ->addArgument(self::ARG_ORGANIZATION_NAME, InputArgument::REQUIRED, 'Organization name')
            ->addArgument(self::ARG_REPOSITORY_NAME, InputArgument::OPTIONAL, 'Repository name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->getProjects($input) as $project) {
            $io->section(sprintf('Cloning %s', (string) $project));

            try {
                $this->gitService->clone($project);
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }
        }

        return 1;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $interactHelper = new Interactor($input, $output, $this->getHelper('question'));

        $interactHelper->chooseArgument(
            self::ARG_ORGANIZATION_NAME,
            'Please select an organization name',
            fn () => $this->projectService->getAllOrganizationNames()
        );
    }

    /**
     * @return Project[]
     */
    private function getProjects(InputInterface $input): array
    {
        $repositoryName = $input->getArgument(self::ARG_REPOSITORY_NAME);

        return $this->projectService->getByNames(
            $input->getArgument(self::ARG_ORGANIZATION_NAME),
            'all' !== $repositoryName ? $repositoryName : null
        );
    }
}
