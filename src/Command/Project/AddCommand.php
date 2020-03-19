<?php

declare(strict_types=1);

namespace PhpInvest\Command\Project;

use PhpInvest\Command\CommandRunner;
use PhpInvest\Command\Git\CloneCommand;
use PhpInvest\Exception\Project\AlreadyExistsException;
use PhpInvest\Model\GitUrl;
use PhpInvest\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AddCommand extends Command
{
    public const NAME = 'pi:project:add';
    private const ARG_URL = 'url';
    private const OPTION_CLONE = 'clone';
    private ProjectService $projectService;

    public function __construct(ProjectService $gitProjectService)
    {
        parent::__construct(self::NAME);

        $this->projectService = $gitProjectService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add project')
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'git https url')
            ->addOption(self::OPTION_CLONE, 'cl', InputOption::VALUE_NONE, 'clone project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Add project');

        $url = $input->getArgument(self::ARG_URL);

        try {
            $project = $this->projectService->createFromGitUrl(new GitUrl($url));
            $io->success(sprintf('Add new project %s', $project));
        } catch (AlreadyExistsException $e) {
            $io->comment($e->getMessage());
            $project = $e->getProject();
        }

        if ($input->getOption(self::OPTION_CLONE)) {
            CommandRunner::run($this->getApplication(), $output, CloneCommand::NAME, [
                CloneCommand::ARG_ORGANIZATION => $project->getOrganizationName(),
                CloneCommand::ARG_REPOSITORY => $project->getRepositoryName(),
            ]);
        }

        return 1;
    }
}
