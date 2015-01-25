<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Pfe\Bundle\CoreBundle\Entity\Action;
use Pfe\Bundle\CoreBundle\Entity\Module;
use Pfe\Bundle\CoreBundle\Entity\Participant;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Pfe\Bundle\BuilderBundle\Builder\LocalisationBuilder;

/**
 * Description of ActionBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ActionBuilder extends AbstractMoocBuilder
{

    protected static $BUILDER_NAME = "Action";

    /**
     *
     * @var LocalisationBuilder
     */
    private $localisationBuilder;

    /**
     *
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine, LocalisationBuilder $localisationBuilder)
    {
        parent::__construct($doctrine);

        $this->localisationBuilder = $localisationBuilder;
    }

    public function build(OutputInterface $output)
    {
        $this->buildParticipants($output);
        $this->buildModules($output);
        $this->buildLocalisations($output);
    }

    public function buildLocalisations(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('localisation' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("\n<info>>>>Actions<<< All Localisations are built</info>");
            return;
        }
        $output->writeln("\n<info>>>>Actions<<< Building Localisations</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $actions Action[] */
        while ($actions = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($actions as $key => $action) {
                $ip = $action->getIp();
                $localisation = $this->localisationBuilder->getLocalisationFromIp($output, $ip);
                if ($localisation) {
                    $action->setLocalisation($localisation);
                    $this->doctrine->merge($action);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    public function buildParticipants(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('participant' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("\n<info>>>>Actions<<< All Participants are built</info>");
            return;
        }
        $output->writeln("\n<info>>>>Actions<<< Building Participants</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $actions Action[] */
        while ($actions = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($actions as $key => $action) {
                /* @var $participant Participant */
                $participant = $this->doctrine->getRepository("PfeCoreBundle:Participant")->findOneBy(array("mooc_id" => $action->getMoocUserId()));
                if ($participant) {
                    $action->setParticipant($participant);
                    $this->doctrine->merge($action);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    public function buildModules(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('module' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("<info>>>>Actions<<< All Modules are built</info>");
            return;
        }
        $output->writeln("<info>>>>Actions<<< Building Modules</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $actions Action[] */
        while ($actions = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($actions as $key => $action) {
                /* @var $module Module */
                $module = $this->doctrine->getRepository("PfeCoreBundle:Module")->findOneBy(array("mooc_id" => $action->getMoocModuleId()));
                if ($module) {
                    $action->setCategory($module->getCategory());
                    $action->setModule($module);
                    $this->doctrine->merge($action);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Action");
    }

}
