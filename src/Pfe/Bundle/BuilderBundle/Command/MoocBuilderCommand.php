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
                ->addOption("all", null, InputOption::VALUE_NONE, "Specify entity to build")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = strtolower($input->getOption("entity"));

        $entities = array("participant", "section", "module", "action");

        if (!$input->getOption("all") && !in_array($entity, $entities)) {
            $msg = "Please specify an entity or use option --all\n";
            $msg .= "Allowed entities: ";
            $del = "";
            foreach ($entities as $key => $val) {
                $msg .= $del . $val;
                $del = ", ";
            }
            $output->writeln('<error>' . $msg . '</error>');
            return 1;
        }

        if (!$input->getOption("all")) {
            return $this->launchBuilderService($output, $entity);
        }

        foreach ($entities as $key => $entity) {
            $this->launchBuilderService($output, $entity);
        }

        return 0;
    }

    protected function launchBuilderService(OutputInterface $output, $entity)
    {
        /**
         * @var \Pfe\Bundle\CollectorBundle\Collector\Builder\ParticipantBuilder $builder
         */
        $builder = $this->getContainer()->get('pfe.builder.' . $entity);

        $builder->build($output);
        $output->writeln(" \n");

        return 0;
    }

}
