<?php

namespace App\Command;

use App\Entity\Env;
use App\Repository\AppRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateEnvCommand extends Command
{
    protected $em;
    protected $ap;

    public function __construct(EntityManagerInterface $em, AppRepository $ap)
    {
        $this->em = $em;
        $this->ap = $ap;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:env:add')
            ->setDescription('This command create a new Env')
            ->setHelp('Run this command to create your env, to track his versions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();
        $last_created_app = $this->ap->findOneBy([], [
            'id' => 'DESC',
        ])->getName();
        $question_name = new Question("Type here name of your Env: \n");
        $name = $helper->ask($input, $output, $question_name);

        if (!$name) {
            $output->writeln('<error>Name was not written </error>');

            return Command::FAILURE;
        }

        $question_app = new Question("Type here name of your Envs App [<info>{$last_created_app}</info>]: \n", $last_created_app);
        $app_name = $helper->ask($input, $output, $question_app);

        $app = $this->ap->findOneBy(['name' => $app_name]);
        if (!$app) {
            $output->writeln('<error>App was not found </error>');

            return Command::FAILURE;
        }

        $env = new Env();
        $env->setName($name);
        $env->setApp($app);

        $this->em->persist($env);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
