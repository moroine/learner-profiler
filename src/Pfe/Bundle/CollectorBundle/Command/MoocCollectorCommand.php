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
                ->setName('pfe:collector:mooc')
                ->setDescription('Import sql dump from MOOC')
                ->addOption("sql-dump", "md", InputOption::VALUE_OPTIONAL, "Specify the sql dump file")
                ->addArgument('course-id', InputArgument::REQUIRED, 'Target course is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $course_id = intval($input->getArgument('course-id'));
        $sqlDump = $input->getOption('sql-dump');

        /**
         * @var \Pfe\Bundle\CollectorBundle\Collector\MoocCollector
         */
        $mooc_collector = $this->getContainer()->get('pfe_collector.mooc');

        if ($sqlDump !== null) {
            if ($mooc_collector->importSqlDb($output, $sqlDump) == 1) {
                return 1;
            }
        }

        $mooc_collector->collect($output, $course_id);
    }

}
