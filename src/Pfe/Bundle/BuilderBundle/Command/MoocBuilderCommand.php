<?php

namespace Pfe\Bundle\BuilderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of MoocBuilderCommand
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocBuilderCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('pfe:builder:participant')
                ->setDescription('Build participants data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \Pfe\Bundle\CollectorBundle\Collector\Builder\ParticipantBuilder $builder
         */
        $builder = $this->getContainer()->get('pfe.builder.participant');

        $builder->build($output);
    }

}
