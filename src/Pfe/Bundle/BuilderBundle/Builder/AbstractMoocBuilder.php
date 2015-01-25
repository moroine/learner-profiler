<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of AbstractMoocBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
abstract class AbstractMoocBuilder
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function getRepository();

    protected static $BATCH_SIZE = 100;
    protected static $BUILDER_NAME = "Abstract";

    /**
     *
     * @var EntityManager
     */
    protected $doctrine;

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
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;

        $this->stats = array('complete' => 0, 'uncomplete' => 0);

        $this->n_managed = 0;
    }

    public function outputStats(OutputInterface $output)
    {
        $output->writeln(" \n<info>" . static::$BUILDER_NAME . ":\n\tcomplete: " . $this->stats['complete'] . ":\n\tuncomplete: " . $this->stats['uncomplete'] . "</info>");
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

    protected function countEntityBatches($criterias)
    {
        $qb = $this->doctrine->createQueryBuilder();
        $classname = $this->getRepository()->getClassName();
        $qb->select('COUNT(e.id)')->from($classname, 'e');
        foreach ($criterias as $field => $value) {
            if (is_null($value)) {
                $qb->andWhere("e." . $field . " IS NULL");
            } else {
                $qb->andWhere("e." . $field . " = :" . $field);
                $qb->setParameter($field, $value);
            }
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    protected function getEntityBatch($criteria, $offset)
    {
        return $this->getRepository()->findBy($criteria, null, static::$BATCH_SIZE, $offset);
    }

}
