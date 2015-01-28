<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use Pfe\Bundle\CollectorBundle\Command\MoocCollectorCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use \Doctrine\DBAL\Connection;
use Symfony\Component\Process\Process;

/**
 * Description of MoocCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocCollector
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $doctrine;

    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     *
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(EntityManager $doctrine, Connection $connection)
    {
        $this->doctrine = $doctrine;
        $this->connection = $connection;
    }

    /**
     *
     * @param OutputInterface $output
     * @param integer|null $mooc_theme_id Select only from one theme
     * @return int status
     */
    public function collect(OutputInterface $output, $mooc_theme_id = null)
    {
        $cmp_processes = array();
        $cmps = array('theme', 'section', 'module', 'participant', 'action');

        foreach ($cmps as $key => $cmp) {
            $output->writeln("<info>Fork: Collect " . $cmp . "</info>");
            if ($mooc_theme_id) {
                $cmp_processes[$cmp] = new Process("php app/console pfe:collector:moodle --env=prod --component " . $cmp . " --course-id " . $mooc_theme_id);
            } else {
                $cmp_processes[$cmp] = new Process("php app/console pfe:collector:moodle --env=prod --component " . $cmp);
            }
            $cmp_processes[$cmp]->start();
        }
        foreach ($cmps as $key => $cmp) {
            $output->writeln("<info>Waiting on " . $cmp . "</info>");
            $output->writeln("<info>This could take a while... (updated every 10% - average 1 min for 10 000 elements)</info>");

            while (!$cmp_processes[$cmp]->isTerminated()) {
                $output->write($cmp_processes[$cmp]->getIncrementalOutput());
                $output->write("<error>" . $cmp_processes[$cmp]->getIncrementalErrorOutput() . "</error>");
                usleep(50000);
            }
            $output->write("<error>" . $cmp_processes[$cmp]->getIncrementalErrorOutput() . "</error>");
            $output->write($cmp_processes[$cmp]->getIncrementalOutput());
            if (!$cmp_processes[$cmp]->isSuccessful()) {
                $output->writeln("<error>Error on importing sql database</error>");
                throw new \RuntimeException($cmp_processes[$cmp]->getErrorOutput());
            }
        }

        return 1;
    }

    /**
     *
     * @param OutputInterface $output
     * @param string $component_name
     * @param integer|null $mooc_theme_id Select only from one theme
     * @return int status
     */
    public function collectComponent(OutputInterface $output, $component_name, $mooc_theme_id = null)
    {
        switch ($component_name) {
            case 'theme':
                $cmp = new ThemeCollectorComposant($this->doctrine, $this->connection);
                break;
            case 'section':
                $cmp = new SectionCollectorComposant($this->doctrine, $this->connection);
                break;
            case 'module':
                $cmp = new ModuleCollectorComposant($this->doctrine, $this->connection);
                break;
            case 'participant':
                $cmp = new ParticipantCollectorComposant($this->doctrine, $this->connection);
                break;
            case 'action':
                $cmp = new ActionCollectorComposant($this->doctrine, $this->connection);
                break;
        }

        if ($mooc_theme_id && $component_name === "theme") {
            $cmp->collectOneBySql($output, $mooc_theme_id);
        } else {
            $cmp->collectBySql($output, $mooc_theme_id);
        }

        $output->writeln(" \n\n<info>" . $cmp->getStats() . "</info>\n\n\n", \Symfony\Component\Console\Output\Output::OUTPUT_RAW);

        return 1;
    }

    /**
     * Import Sql Dump File into database
     *
     * @param OutputInterface $output
     * @param string $input_path
     * @return int status
     */
    public function importSqlDump(OutputInterface $output, $input_path)
    {
        $path = $this->getFilePath($output, $input_path);

        if ($path === null) {
            $output->writeln("<info>File not found ....</info>");
            return 0;
        }

        $output->writeln("<info>Importig Moodle sql database ....</info>");
        $output->writeln("<info>This could take a while, please wait ...</info>");

        $username = " -u " . $this->connection->getUsername();
        $password = empty($this->connection->getPassword()) ? "" : " -p" . $this->connection->getPassword();
        $cmd = "mysql" . $username . $password . " " . $this->connection->getDatabase() . " < " . $path;

        $output->writeln(">>>" . $cmd);
        $process = new Process($cmd);
        $process->run(); // Synchronous

        if (!$process->isSuccessful()) {
            $output->writeln("<error>Error on importing sql database</error>");
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output->writeln($process->getOutput());

        $output->writeln("<error>Database successfully imported</error>");
    }

}
