<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomQuestionCommand extends Command
{
    protected static $defaultName = 'app:random-question';

    private $logger;
public function __construct(LoggerInterface $logger)
{
    $this->logger=$logger;

    parent::__construct();
}

    protected function configure():
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('yourname', InputArgument::OPTIONAL, 'Your name')
            ->addOption('caps', null, InputOption::VALUE_NONE, 'Make it uppercase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $yourname = $input->getArgument('yourname');

        if ($yourname) {
            $io->note(sprintf('Hello, %s', $yourname));
        }

        $questions=[
            'How do I figure out my Kibbe body type?',
            'Where can you buy high quality clothes for a low price?',
            'Can I wear black to a wedding?',
            'Is it true that you should never combine red and pink?',
            'What should I wear for a first date at the park?',
            'How can I make an informal look seem slightly more formal?',
            'What do you think of the concept of capsule wardrobes?',
        ];

        $question=$questions[array_rand($questions)];

        if ($input->getOption('caps')) {
            $question=strtoupper($question);
        }

        $this->logger->info('Loading question: '.$question);

        $io->success($question);

        return 0;
    }
}
