<?php

declare(strict_types=1);

namespace PhpInvest\Command;

use PhpInvest\Model\GitUrl;
use PhpInvest\Service\ProjectService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AddProjectCommand extends Command
{
    private ProjectService $projectService;

    private const ARG_URL = 'url';

    public function __construct(ProjectService $gitProjectService)
    {
        parent::__construct('pi:project:add');

        $this->projectService = $gitProjectService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add project')
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'git https url')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $url = $input->getArgument(self::ARG_URL);
        $project = $this->projectService->createFromGitUrl(new GitUrl($url));

        $io->success(sprintf('Add new project %s', $project));

        return 1;
    }
}
