<?php

namespace Rezzza\JobFlowBundle\Job;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Rezzza\JobFlow\AbstractJobType;
use Rezzza\JobFlow\Io;
use Rezzza\JobFlow\JobBuilder;

class ExampleJob extends AbstractJobType
{
    public function buildJob(JobBuilder $builder, array $options)
    {
        $builder
            ->add(
                'example_extractor', 
                'extractor',
                array(
                    'class' => 'Knp\ETL\Extractor\CsvExtractor'
                )
            )
            ->add(
                'example_transformer', 
                'callback_transformer',
                array(
                    'callback' => function($data, $target) {
                        $target['firstname'] = $data[0];
                        $target['name'] = $data[1];
                        $target['url'] = sprintf('http://www.lequipe.fr/Football/FootballFicheJoueur%s.html', $data[2]);

                        return json_encode($target, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    }
                )
            )
            ->add(
                'example_loader',
                'file_loader'
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'io' => new Io\IoDescriptor(
                new Io\Input('file://'.__DIR__."/../../../../jobflow/examples/fixtures.csv"),
                new Io\Output('file:///'.__DIR__."/../../../../../../temp/result.json")
            )
        ));
    }

    public function getName()
    {
        return 'example';
    }
}