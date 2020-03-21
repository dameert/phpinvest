<?php

declare(strict_types=1);

namespace PhpInvest\Command\Project;

use PhpInvest\Command\Command;
use PhpInvest\Command\Git\CloneCommand;
use PhpInvest\Exception\Project\AlreadyExistsException;
use PhpInvest\Invest\Git\Url;
use PhpInvest\Service\Project\ProjectChoice;
use PhpInvest\Service\Project\ProjectFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddCommand extends Command
{
    public const NAME = 'pi:project:add';

    private const ARG_NAME = 'name';
    private const ARG_URL = 'url';
    private const OPTION_CLONE = 'clone';

    private ProjectFactory $projectFactory;

    public function __construct(ProjectFactory $projectFactory)
    {
        parent::__construct(self::NAME);
        $this->projectFactory = $projectFactory;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add project')
            ->addArgument(self::ARG_NAME, InputArgument::REQUIRED, 'project name')
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'git https url')
            ->addOption(self::OPTION_CLONE, 'cl', InputOption::VALUE_NONE, 'clone project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Add project');

        $url = new Url($input->getArgument(self::ARG_URL));

        try {
            $project = $this->projectFactory->create($url, $input->getArgument(self::ARG_NAME));
            $this->io->success(sprintf('Add new project %s', $project));
        } catch (AlreadyExistsException $e) {
            $this->io->warning($e->getMessage());
            $project = $e->getProject();
        }

        if ($input->getOption(self::OPTION_CLONE)) {
            return $this->runCommand($output, CloneCommand::NAME, [ProjectChoice::NAME => $project->getName()]);
        }

        return 1;
    }
}
