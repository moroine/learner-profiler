<?php

namespace Pfe\Bundle\CollectorBundle\Collector;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of MoocCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocCollector {

    /**
     *
     * @var \Pfe\Bundle\CollectorBundle\Collector\Extractor\MoocExtractor
     */
    private $extractor;

    /**
     *
     * @var \Pfe\Bundle\CollectorBundle\Collector\Builder\MoocBuilder
     */
    private $builder;

    public function __construct(Extractor\MoocExtractor $extrator, Builder\MoocBuilder $builder) {
        $this->extractor = $extrator;
        $this->builder = $builder;
    }

    public function collect(OutputInterface $output, $sqlFile, $course_id) {
        $path = $this->getFilePath($output, $sqlFile);

        if ($path == null) {
            return 0;
        }

        if ($course_id < 0) {
            $output->writeln('<error>Wrong course id</error>');

            return 0;
        }

        if (!$this->extractor->isConnected()) {
            $output->writeln("<error>Unable to use the collector connection, please ensure configuration</error>");

            return 0;
        }

        $output->writeln("<info>Importig sql database ....</info>");
        $output->writeln("<info>This could take a while, please wait ...</info>");
        // $this->extractor->importSqlDb($output, $path);
        $output->writeln("<info>Sql Database succefully imported</info>");

        $output->writeln("<info>Collecting Participant data ...</info>");
        $this->extractor->extractParticipantData($output, $course_id);

        // TODO: MoocTransform with BUILDER LOCALISATION && Just Participant
        $data = $this->extractor->nextRow($output);
        while ($data !== null) {
            $this->builder->buildParticipant($output, $data);

            $data = $this->extractor->nextRow($output);
        }

        $this->builder->saveChanges();
        $output->writeln($this->builder->getStats(), \Symfony\Component\Console\Output\Output::OUTPUT_RAW);
    }

    private function getFilePath($output, $path) {
        /**
         * @var \Symfony\Component\Filesystem\Filesystem
         */
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        if (!$fs->isAbsolutePath($path)) {
            $path = getcwd() . "/" . $path;
        }

        if (!$fs->exists($path)) {
            $output->writeln("<error>Unable to find the SQL dump file\n\tLooking for " . $path . "</error>");

            return null;
        }

        if (!is_file($path)) {
            $output->writeln("<error>This is not a file\n\tLooking for " . $path . "</error>");

            return null;
        }

        return $path;
    }

}
