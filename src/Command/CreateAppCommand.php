<?php

namespace App\Command;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateAppCommand extends Command
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:app:add')
            ->setDescription('This command create a new app')
            ->setHelp('Run this command to create your app, to track his env versions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();
        $question = new Question("Type here name of an App: \n");
        $name = $helper->ask($input, $output, $question);

        $app = new App();
        $app->setName($name);
        if (!$name) {
            return Command::INVALID;
        }
        $this->em->persist($app);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
