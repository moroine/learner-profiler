<?php

namespace Pfe\Bundle\CollectorBundle\Collector\Builder;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of AbstractMoocBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
abstract class AbstractMoocBuilder {

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
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine) {
        $this->doctrine = $doctrine;

        $this->stats = array();
        $this->stats['insert'] = 0;
        $this->stats['update'] = 0;
        $this->stats['delete'] = 0;

        $this->n_managed = 0;
    }

    public function saveChanges() {
        $this->doctrine->getManager()->flush();
        $this->doctrine->getManager()->clear();
    }

    public function getStats() {
        return <<<EOT
                {$this->entityClassname}: {$this->stats['insert']} inserted, {$this->stats['update']} updated, {$this->stats['delete']} deleted
EOT;
    }

    protected function findOneBy($criteria) {
        return $this->doctrine->getRepository("PfeCoreBundle:" . $this->entityClassname)->findOneBy($criteria);
    }

    protected function getOneBy($criteria) {
        $entity = $this->findOneBy($criteria);

        if (empty($entity)) {
            $entity = $this->createEntity();
            $this->doctrine->getManager()->persist($entity);
            $this->stats['insert'] ++;
        } else {
            $this->stats['update'] ++;
        }

        return $entity;
    }

    abstract protected function createEntity();
}
