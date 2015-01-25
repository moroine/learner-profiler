<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Pfe\Bundle\CoreBundle\Entity\Participant;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi;
use Pfe\Bundle\GeonamesApiBundle\GeonamesApi\GeonamesApi;

/**
 * Description of ParticipantBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ParticipantBuilder extends AbstractMoocBuilder
{

    protected static $BUILDER_NAME = "Participant";

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
        $this->buildLocalisations($output);
    }

    public function buildLocalisations(OutputInterface $output)
    {
        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
        $criteria = array('home' => null);
        $max = $this->countEntityBatches($criteria);
        if (!$max) {
            $output->writeln("<info>>>>Participants<<< All Localisations are built</info>");
            return;
        }
        $output->writeln("<info>>>>Participants<<< Building Localisations</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $participants Participant[] */
        while ($participants = $this->getEntityBatch($criteria, $this->stats['uncomplete'])) {
            foreach ($participants as $participant) {
                $this->buildLocalisation($output, $participant);
                $progress->advance();
            }
            $this->flushEntities();
        }

        $progress->finish();
        $this->outputStats($output);
    }

    protected function buildLocalisation(OutputInterface $output, Participant $participant)
    {
        $countryCode = $participant->getCountryCode();
        $city = $participant->getCity();
        $localisation = $this->localisationBuilder->getLocalisation($output, $countryCode, $city);

        if ($localisation) {
            $localisation = $this->doctrine->merge($localisation);
            if (!$this->doctrine->contains($localisation)) {
                $output->writeln("<error>Localisation Not managed </error>");
            }
            $participant->setHome($localisation);
            $this->doctrine->merge($participant);
            $this->stats['complete'] ++;
        } else {
            $this->stats['uncomplete'] ++;
        }
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Participant");
    }

}
