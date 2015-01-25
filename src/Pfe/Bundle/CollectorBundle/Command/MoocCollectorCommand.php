<?php

namespace Pfe\Bundle\CollectorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of MoocCollectorCommand
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocCollectorCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('pfe:collector:moodle')
                ->setDescription('Collect MOOC data from Moodle')
                ->addOption("sql-dump", "md", InputOption::VALUE_OPTIONAL, "Specify the Moodle sql dump file")
                ->addOption('course-id', "id", InputOption::VALUE_OPTIONAL, "Collect only specified theme")
                ->addOption('component', "cmp", InputOption::VALUE_OPTIONAL, "Collect only specified theme")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $course_id = $input->getOption('course-id');
        $sqlDump = $input->getOption('sql-dump');
        $cmp = $input->getOption('component');

        /**
         * @var \Pfe\Bundle\CollectorBundle\Moodle\MoocCollector
         */
        $mooc_collector = $this->getContainer()->get('pfe.collector.moodle');

        if ($sqlDump !== null) {
            if ($mooc_collector->importSqlDb($output, $sqlDump)) {
                return 1;
            }
        }

        if (!empty($cmp)) {
            $mooc_collector->collectComponent($output, $cmp, $course_id);
        } else {
            $mooc_collector->collect($output, $course_id);
        }

        return 0;
    }

}
