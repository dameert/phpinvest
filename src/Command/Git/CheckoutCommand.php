<?php

declare(strict_types=1);

namespace PhpInvest\Command\Git;

use PhpInvest\Command\Command;
use PhpInvest\Exception\Git\CheckoutNotFoundException;
use PhpInvest\Invest\Collection\ProjectCollection;
use PhpInvest\Service\Git\GitService;
use PhpInvest\Service\Project\ProjectChoice;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckoutCommand extends Command
{
    public const NAME = 'pi:git:checkout';
    public const OPTION_BRANCH = 'branch';

    private GitService $gitService;
    private ProjectChoice $projectChoice;
    private ProjectCollection $projects;

    public function __construct(GitService $gitService, ProjectChoice $projectChoice)
    {
        parent::__construct(self::NAME);
        $this->gitService = $gitService;
        $this->projectChoice = $projectChoice;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Git checkout branch')
            ->addArgument(ProjectChoice::NAME, InputArgument::REQUIRED, ProjectChoice::DESCRIPTION)
            ->addOption(self::OPTION_BRANCH, 'b', InputOption::VALUE_REQUIRED, 'Branch name', 'master')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Checkout branch for project(s)');

        $branch = $input->getOption(self::OPTION_BRANCH);

        foreach ($this->projects as $project) {
            try {
                $this->io->section($project->getName());

                $checkout = $this->gitService->getCheckout($project);
                $this->gitService->checkout($checkout, $branch);

                $this->io->success(sprintf('Checkout branch %s for project %s', $branch, (string) $project));
            } catch (CheckoutNotFoundException $e) {
                $this->io->error($e->getMessage());
            }
        }

        return 1;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->projects = $this->projectChoice->interact($input, $output);
    }
}
