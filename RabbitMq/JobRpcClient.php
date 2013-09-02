<?php

namespace Rezzza\JobFlowBundle\RabbitMq;

use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Message\AMQPMessage;

class JobRpcClient extends RpcClient
{
    public function processMessage(AMQPMessage $msg)
    {
        $body = unserialize($msg->body);
        
        if (false === $body) {
            echo $msg->body.PHP_EOL; exit;
        }

        $this->replies[$msg->get('correlation_id')] = $body;

        if (null === $body) {
            throw new \Exception('bouh');
        }

        echo 'Executed : '.$body->context->getStep().PHP_EOL;

        if ($body->context->hasNext()) {
            echo 'Try to execute '.$body->context->next['name'].PHP_EOL;
            $name = $body->context->getMessageName();
            $this->addRequest(serialize($body), 'job', $name);
        }

        return true;
    }
}