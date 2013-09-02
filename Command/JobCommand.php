<?php

namespace Rezzza\JobFlowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rezzza\JobFlow\Scheduler\JobScheduler;
use Rezzza\JobFlow\Scheduler\Transport\RabbitMqTransport;
use Rezzza\JobFlow\Scheduler\Transport\PhpTransport;

class JobCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('jobflow:run')
            ->addArgument('id', InputArgument::REQUIRED, 'Job service id')
            ->addOption('transport', 't', InputOption::VALUE_REQUIRED, 'Which transport used', 'php')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $factory = $container->get('job_flow.factory');
        $scheduler = $factory->createJobFlow($input->getOption('transport'));
        $job = $factory->create($input->getArgument('id'));

//$container->get('old_sound_rabbit_mq.job_rpc')
        $scheduler
            ->setJob($job)
            ->init()
            ->run()
        ;
    }
}