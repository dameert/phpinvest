<?php

declare(strict_types=1);

namespace PhpInvest\Command\Git;

use PhpInvest\Command\Interactor;
use PhpInvest\Entity\Project;
use PhpInvest\Exception\Git\AlreadyClonedException;
use PhpInvest\Service\GitService;
use PhpInvest\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CloneCommand extends Command
{
    public const NAME = 'pi:git:clone';
    public const ARG_ORGANIZATION = 'organization';
    public const ARG_REPOSITORY = 'repository';
    public const OPTION_BRANCH = 'branch';
    private GitService $gitService;
    private ProjectService $projectService;

    public function __construct(GitService $gitService, ProjectService $gitProjectService)
    {
        parent::__construct(self::NAME);

        $this->gitService = $gitService;
        $this->projectService = $gitProjectService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Git clone project')
            ->addArgument(self::ARG_ORGANIZATION, InputArgument::REQUIRED, 'Organization name')
            ->addArgument(self::ARG_REPOSITORY, InputArgument::OPTIONAL, 'Repository name')
            ->addOption(self::OPTION_BRANCH, 'b', InputOption::VALUE_REQUIRED, 'Branch name', 'master')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Clone project(s)');

        $branch = $input->getOption(self::OPTION_BRANCH);

        foreach ($this->getProjects($input) as $project) {
            $io->note(sprintf('Cloning branch %s from %s', $branch, (string) $project));

            try {
                $this->gitService->clone($project, $branch);
            } catch (AlreadyClonedException $e) {
                $io->warning($e->getMessage());
            }
        }

        return 1;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $interactHelper = new Interactor($input, $output, $this->getHelper('question'));

        $interactHelper->chooseArgument(
            self::ARG_ORGANIZATION,
            'Please select an organization name',
            fn () => $this->projectService->getAllOrganizationNames()
        );
    }

    /**
     * @return Project[]
     */
    private function getProjects(InputInterface $input): array
    {
        $repositoryName = $input->getArgument(self::ARG_REPOSITORY);

        return $this->projectService->getByNames(
            $input->getArgument(self::ARG_ORGANIZATION),
            'all' !== $repositoryName ? $repositoryName : null
        );
    }
}
