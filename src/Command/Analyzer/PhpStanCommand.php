<?php

declare(strict_types=1);

namespace PhpInvest\Command\Analyzer;

use PhpInvest\Command\Command;
use PhpInvest\Exception\InvestException;
use PhpInvest\Invest\Collection\ProjectCollection;
use PhpInvest\Service\Analyzer\PhpStanAnalyzer;
use PhpInvest\Service\Project\ProjectChoice;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PhpStanCommand extends Command
{
    public const NAME = 'pi:a:phpstan';

    private PhpStanAnalyzer $phpStanAnalyzer;
    private ProjectChoice $projectChoice;
    private ProjectCollection $projects;

    public function __construct(PhpStanAnalyzer $phpStanAnalyzer, ProjectChoice $projectChoice)
    {
        parent::__construct(self::NAME);
        $this->phpStanAnalyzer = $phpStanAnalyzer;
        $this->projectChoice = $projectChoice;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Run PHPStan analyzer')
            ->addArgument(ProjectChoice::NAME, InputArgument::REQUIRED, ProjectChoice::DESCRIPTION)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('PHPStan analyzer');

        foreach ($this->projects as $project) {
            try {
                $this->io->section($project->getName());

                $this->phpStanAnalyzer->analyze($project);
            } catch (InvestException $e) {
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
