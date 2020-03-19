<?php

declare(strict_types=1);

namespace PhpInvest\Command;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class Interactor
{
    private InputInterface $input;
    private OutputInterface $output;
    private QuestionHelper $questionHelper;

    public function __construct(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $questionHelper;
    }

    public function chooseArgument(string $argument, string $questionText, callable $choicesCallback): void
    {
        if (null !== $this->input->getArgument($argument)) {
            return;
        }

        $choices = $choicesCallback();

        if (empty($choices)) {
            return;
        }

        if (1 === \count($choices)) {
            $this->input->setArgument($argument, $choices[0]);

            return;
        }

        $question = new ChoiceQuestion($questionText, $choices, 0);
        $this->input->setArgument($argument, $this->questionHelper->ask($this->input, $this->output, $question));
    }
}
