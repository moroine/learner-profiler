<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use \Symfony\Component\Console\Output\OutputInterface;
use \Doctrine\DBAL\Connection;
use \Doctrine\ORM\EntityManager;

/**
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
abstract class AbstractCollectorComposant
{

    /**
     * To be overwrited, contain the entityclassname for static output
     * @var string
     */
    protected static $ENTITY_CLASS_NAME = "Abstract";

    /**
     * Get the sql query to retrieve datas
     */
    abstract protected function getQuery();

    abstract protected function getOneQuery();

    /**
     * Get the entity from data
     */
    abstract protected function hydrateEntity($data);

    /**
     * Threshold to launch automatic doctrine flush
     * @var type
     */
    protected static $FLUSH_THRESHOLD = 50000;

    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     *
     * @var string
     */
    protected $entityClassname;

    /**
     *
     * @var array
     */
    protected $stats;

    /**
     * @var integer
     */
    protected $n_managed;

    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     *
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $statement;

    /**
     *
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine, Connection $connection)
    {
        $this->doctrine = $doctrine;
        $this->stats = array("created" => 0, "updated" => 0);
        $this->n_managed = 0;
        $this->connection = $connection;
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }
    }

    /**
     * Collect From Sql Database
     *
     * @param OutputInterface $output
     * @param integer|null $mooc_theme_id
     * @return int status
     */
    public function collectBySql(OutputInterface $output, $mooc_theme_id = null)
    {
        if (!$this->isConnected()) {
            $output->writeln("<error>Unable to use the collector connection, please ensure configuration</error>");

            return 0;
        }
        if ($mooc_theme_id !== null) {
            $query = $this->getQuery('theme_mooc_id');
            $this->statement = $this->connection->prepare($query);
            $this->statement->bindValue("theme_mooc_id", intval($mooc_theme_id));
        } else {
            $query = $this->getQuery();
            $this->statement = $this->connection->prepare($query);
        }

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query:\n\t" . $query);
        }

        while ($data = $this->nextRow()) {
            $entity = $this->hydrateEntity($data);

            $this->doctrine->persist($entity);

            if ($this->n_managed > self::$FLUSH_THRESHOLD) {
                $this->flushEntities();
            }
        }

        $this->flushEntities();

        return 1;
    }

    /**
     * Collect One From Sql Database
     *
     * @param OutputInterface $output
     * @param type $mooc_id
     * @return int
     */
    public function collectOneBySql(OutputInterface $output, $mooc_id)
    {
        if (!$this->isConnected()) {
            $output->writeln("<error>Unable to use the collector connection, please ensure configuration</error>");

            return 0;
        }

        if ($mooc_id < 0) {
            $output->writeln('<error>Wrong mooc_id</error>');

            return 0;
        }

        $query = $this->getOneQuery($mooc_id);
        $this->statement = $this->connection->prepare($query);
        $this->statement->bindValue("mooc_id", $mooc_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query:\n\t" . $query);
        }

        $data = $this->nextRow();
        if (!$data) {
            $output->writeln("<error>Unable to find to selected entity:\n\t" . $query);

            return 0;
        }

        $entity = $this->hydrateEntity($data);

        $this->doctrine->persist($entity);

        $this->flushEntities();

        return 1;
    }

    /**
     * Flush & clear all entities store by doctrine
     */
    public function flushEntities()
    {
        $this->doctrine->flush();
        $this->doctrine->clear();
        $this->n_managed = 0;
    }

    /**
     * Get next row Data, null if not found
     *
     * @return array|null
     */
    protected function nextRow()
    {
        $data = $this->statement->fetch();

        if ($data) {
            return $data;
        }

        return null;
    }

    public function isConnected()
    {
        return $this->connection->isConnected();
    }

    /**
     *
     * @param OutputInterface $output
     * @param string $path
     * @return string
     */
    private function getFilePath(OutputInterface $output, $path)
    {
        /**
         * @var \Symfony\Component\Filesystem\Filesystem
         */
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        if (!$fs->isAbsolutePath($path)) {
            $path = getcwd() . "/" . $path;
        }

        if (!$fs->exists($path)) {
            $output->writeln("<error>Unable to find the file " . $path . "</error>");

            return null;
        }

        if (!is_file($path)) {
            $output->writeln("<error>" . $path . " is not a file</error>");

            return null;
        }

        if (!is_readable($path)) {
            $output->writeln("<error>Unable to read " . $path . "\n\tCheck your permissions</error>");

            return null;
        }

        return $path;
    }

    public function getStats()
    {
        $stats = static::$ENTITY_CLASS_NAME . ":\n\tcreated: " . $this->stats['created'] . "\n\tupdated: " . $this->stats['updated'];

        return $stats;
    }

}
