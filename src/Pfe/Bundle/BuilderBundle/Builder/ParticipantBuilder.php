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
     * @var GeonamesApi
     */
    private $geonameapi;

    /**
     *
     * @var GPlaceApi
     */
    private $gplaceapi;

    /**
     *
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine, GeonamesApi $geonameapi, GPlaceApi $gplaceapi)
    {
        parent::__construct($doctrine);

        $this->geonameapi = $geonameapi;
        $this->gplaceapi = $gplaceapi;
    }

    public function build(OutputInterface $output)
    {
        $this->localisationBuilder = new LocalisationBuilder($this->doctrine, $this->geonameapi, $this->gplaceapi);

        $this->buildLocalisations($output);
    }

    public function buildLocalisations(OutputInterface $output)
    {
        $offset = 0;
        $criteria = array('home' => null);
        $max = $this->countEntityBatches($criteria);
        if ($max === 0) {
            $output->writeln("<info>All Localisations are built</info>");
            return;
        }
        $output->writeln("<info>Building Localisations</info>");
        $progress = new ProgressBar($output, $max);
        $progress->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:3s%/%estimated:-3s% %memory:6s%');
        $progress->start();

        /* @var $participants Participant[] */
        while ($participants = $this->getEntityBatch($criteria, $offset)) {
            foreach ($participants as $key => $participant) {
                $countryCode = $participant->getCountryCode();
                $city = $participant->getCity();
                $localisation = $this->localisationBuilder->getLocalisation($output, $countryCode, $city);
                if ($localisation) {
                    $participant->setHome($localisation);
                    $this->stats['complete'] ++;
                } else {
                    $this->stats['uncomplete'] ++;
                }
                $progress->advance();
            }
            $offset += static::$BATCH_SIZE;
            $this->flushEntities();
        }

        $this->outputStats($output);
        $progress->finish();

        $this->stats['complete'] = 0;
        $this->stats['uncomplete'] = 0;
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Participant");
    }

}
