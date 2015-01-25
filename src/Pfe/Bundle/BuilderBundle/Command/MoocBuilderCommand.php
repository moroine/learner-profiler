<?php

namespace Pfe\Bundle\BuilderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

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
                ->setName('pfe:builder')
                ->setDescription('Build participants data')
                ->addOption("entity", null, InputOption::VALUE_OPTIONAL, "Specify entity to build")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getOption("entity");
        /**
         * @var \Pfe\Bundle\CollectorBundle\Collector\Builder\ParticipantBuilder $builder
         */
        switch (strtolower($entity)) {
            case 'participant':
                $service = 'pfe.builder.participant';
                break;
            case 'section':
                $service = 'pfe.builder.section';
                break;
            case 'module':
                $service = 'pfe.builder.module';
                break;
            default:
                throw new \Symfony\Component\Yaml\Exception\RuntimeException("Please specify entity option");
        }
        $builder = $this->getContainer()->get($service);

        $builder->build($output);
        $output->writeln(" \n");
    }

}
