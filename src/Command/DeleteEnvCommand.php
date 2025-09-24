<?php

namespace App\Command;

use App\Repository\EnvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DeleteEnvCommand extends Command
{
    protected $em;
    protected $ep;

    public function __construct(EntityManagerInterface $em, EnvRepository $ep)
    {
        $this->ep = $ep;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:env:remove')
            ->setDescription('This command Delete Env')
            ->setHelp('Run this command to remove your env');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();
        $last_created_env = $this->ep->findOneBy([], [
            'id' => 'DESC',
        ]);
        $question_name = new Question("Type here UUID of your Env:[<info>{$last_created_env->getName()}</info>]:  \n", $last_created_env->getUuid());
        $uuid = $helper->ask($input, $output, $question_name);

        $env = $this->ep->findOneBy(['uuid' => $uuid]);
        $this->em->remove($env);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
