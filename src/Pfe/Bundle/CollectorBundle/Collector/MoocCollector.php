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

    public function collect(OutputInterface $output, $course_id) {
        if ($course_id < 0) {
            $output->writeln('<error>Wrong course id</error>');

            return 0;
        }

        if (!$this->extractor->isConnected()) {
            $output->writeln("<error>Unable to use the collector connection, please ensure configuration</error>");

            return 0;
        }

        $modules = $this->collectCourseData($output, $course_id);
        $participants = $this->collectParticipantData($output, $course_id);

        $this->collectActionData($output, $course_id, $modules, $participants);

        $output->writeln($this->builder->getStats(), \Symfony\Component\Console\Output\Output::OUTPUT_RAW);

        return 1;
    }

    public function importSqlDb(OutputInterface $output, $sqlFile) {
        $path = $this->getFilePath($output, $sqlFile);

        if ($path === null) {
            return 0;
        }

        $output->writeln("<info>Importig sql database ....</info>");
        $output->writeln("<info>This could take a while, please wait ...</info>");
        $this->extractor->importSqlDb($output, $path);
        $output->writeln("<info>Sql Database succefully imported</info>");

        return 1;
    }

    private function collectCourseData(OutputInterface $output, $course_id) {
        $output->writeln("<info>Collecting course data,...</info>");
        $this->extractor->extractThemeData($output, $course_id);
        $data = $this->extractor->nextRow($output);
        $theme = $this->builder->buildTheme($output, $data);

        $this->extractor->extractSectionData($output, $course_id);
        $sections = [];
        $data = $this->extractor->nextRow($output);
        while ($data !== null) {
            $section = $this->builder->buildSection($output, $data, $theme);
            $sections[$section->getMoocId()] = $section;

            $data = $this->extractor->nextRow($output);
        }

        $this->extractor->extractModuleData($output, $course_id);
        $modules = [];
        $data = $this->extractor->nextRow($output);
        while ($data !== null) {
            $module = $this->builder->buildModule($output, $data, $sections);
            $modules[$module->getMoocId()] = $module;

            $data = $this->extractor->nextRow($output);
        }

        $this->builder->saveChanges();

        return $modules;
    }

    private function collectParticipantData(OutputInterface $output, $course_id) {
        $output->writeln("<info>Collecting Participant data ...</info>");
        $this->extractor->extractParticipantData($output, $course_id);

        // TODO: Do not remove participants
        $data = $this->extractor->nextRow($output);
        $participants = [];
        while ($data !== null) {
            $participant = $this->builder->buildParticipant($output, $data);
            $participants[$participant->getMoocId()] = $participant;

            $data = $this->extractor->nextRow($output);
        }

        $this->builder->saveChanges();

        return $participants;
    }

    /**
     *
     * @param OutputInterface $output
     * @param integer $course_id
     * @param \Pfe\Bundle\CoreBundle\Entity\Module[] $modules
     * @param \Pfe\Bundle\CoreBundle\Entity\Participant[] $participants
     */
    private function collectActionData(OutputInterface $output, $course_id, $modules, $participants) {
        $output->writeln("<info>Collecting Action data ...</info>");
        $this->extractor->extractActionData($output, $course_id);

        $count = 0;
        $data = $this->extractor->nextRow($output);
        while ($data !== null) {
            $this->builder->buildAction($output, $data, $modules, $participants);

            $data = $this->extractor->nextRow($output);
            $count++;
            if ($count % 20000 === 0) {
                $output->writeln("<info>" . $count . " actions imported</info>");
            }
        }

        $this->builder->saveChanges();
    }

    private function getFilePath(OutputInterface $output, $path) {
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
