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
class MoocCollectorCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('pfe:collector:mooc')
            ->setDescription('Import sql dump from MOOC')
            ->addArgument('file', InputArgument::REQUIRED, 'Sql dump file required')
            ->addArgument('course_id', InputArgument::REQUIRED, 'Target course is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $path = $this->getFilePath($output, $input->getArgument('file'));

        if ($path == null) {
            return 0;
        }

        $course_id = intval($input->getArgument('course_id'));

        if ($course_id < 0) {
            $output->writeln('<error>Wrong course id</error>');

            return 0;
        }

        $mooc_extrator = $this->getContainer()->get('pfe_collector.extractor');

        if (!$mooc_extrator->isConnected()) {
            $output->writeln("<error>Unable to use the collector connection, please ensure configuration</error>");

            return 0;
        }

        // $mooc_extrator->mysqlImportCmd($output, $path);
        $mooc_extrator->extract($output, $course_id);

        // TODO: MoocTransform with BUILDER LOCALISATION && Just Participant
        while ($data = $mooc_extrator->nextRow($output) !== null) {
            $participant = null;
            if ($data['auth'] === 'shibboleth' || ($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] === 'student') {
                $participant = new \Pfe\Bundle\DataBundle\Entity\Etudiant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            } elseif ($data['auth'] === 'email' && $data['enrol'] === 'self' && $data['shortname'] === 'student') {
                $participant = new \Pfe\Bundle\DataBundle\Entity\Apprenant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            } elseif (($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] !== 'student') {
                $participant = new \Pfe\Bundle\DataBundle\Entity\Staff($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            } else {
                $participant = new \Pfe\Bundle\DataBundle\Entity\Participant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            }
        };
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
