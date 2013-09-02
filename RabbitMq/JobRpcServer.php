<?php

namespace Rezzza\JobFlowBundle\RabbitMq;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Rezzza\JobFlow\Scheduler\JobScheduler;
use Rezzza\JobFlow\Scheduler\Transport\RabbitMqTransport;

class JobRpcServer implements ConsumerInterface
{
    protected $jobFactory;

    protected $rpcClient;

    public function __construct($jobFactory, $rpcClient)
    {
        $this->jobFactory = $jobFactory;
        $this->rpcClient = $rpcClient;
    }

    public function execute(AMQPMessage $msg)
    {
        $jobMsg = unserialize($msg->body);

        $job = $this->jobFactory->create($jobMsg->context->jobId);

        $transport = new RabbitMqTransport($this->rpcClient);

        $scheduler = new JobScheduler($transport);
        //$scheduler->addMessage($jobMsg);

        return $scheduler
            ->setJob($job)
            ->run($jobMsg)
        ;
    }
}