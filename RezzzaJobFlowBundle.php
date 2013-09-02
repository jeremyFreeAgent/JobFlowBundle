<?php

namespace Rezzza\JobFlowBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rezzza\JobFlowBundle\DependencyInjection\Compiler\JobPass;

class RezzzaJobFlowBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JobPass());
    }
}
