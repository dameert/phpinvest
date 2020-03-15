<?php

declare(strict_types=1);

namespace PhpInvest\Command;

use PhpInvest\Command\Helper\InteractHelper;
use PhpInvest\Command\Helper\ProcessRunner;
use PhpInvest\Entity\Project;
use PhpInvest\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

final class CloneProjectCommand extends Command
{
    private ProjectService $projectService;

    private const ARG_ORGANIZATION_NAME = 'organization_name';
    private const ARG_REPOSITORY_NAME = 'repository_name';

    public function __construct(ProjectService $gitProjectService)
    {
        parent::__construct('pi:project:clone');

        $this->projectService = $gitProjectService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Clone project')
            ->addArgument(self::ARG_ORGANIZATION_NAME, InputArgument::REQUIRED, 'Organization name')
            ->addArgument(self::ARG_REPOSITORY_NAME, InputArgument::OPTIONAL, 'Repository name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fs = new Filesystem();
        $projects = $this->getProjects($input);

        foreach ($projects as $project) {
            $io->section(sprintf('Cloning %s', (string) $project));

            $directory = sprintf('checkout/%s', $project->getRepositoryName());

            if ($fs->exists($directory)) {
                $io->warning(sprintf('Directory "%s" already exists', $directory));
                continue;
            }

            ProcessRunner::run(new Process(['git', 'clone', $project->getSSH(), $directory]));
            ProcessRunner::run(new Process(['composer', 'install'], $directory, null, null, 300.00));
        }

        return 1;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $interactHelper = new InteractHelper($input, $output, $this->getHelper('question'));

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
            $repositoryName !== 'all' ? $repositoryName : null
        );
    }
}
