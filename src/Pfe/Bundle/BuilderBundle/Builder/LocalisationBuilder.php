<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Symfony\Component\Console\Output\OutputInterface;
use \Pfe\Bundle\CoreBundle\Entity\Localisation;
use Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi;
use Pfe\Bundle\GeonamesApiBundle\GeonamesApi\GeonamesApi;

/**
 * Description of ParticipantBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class LocalisationBuilder extends AbstractMoocBuilder
{

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

    public function __construct(\Doctrine\ORM\EntityManager $doctrine, GeonamesApi $geonameapi, GPlaceApi $gplaceapi)
    {
        parent::__construct($doctrine);

        $this->gplaceapi = $gplaceapi;
        $this->geonameapi = $geonameapi;
    }

    /**
     *
     * @param OutputInterface $output
     * @param string $countryCode
     * @param string $city
     * @return Localisation
     */
    public function getLocalisation(OutputInterface $output, $countryCode, $city)
    {
        $countryInfos = (empty($countryCode)) ? null : $this->getCountryInformation($output, $countryCode);

        $countryName = (empty($countryInfos)) ? null : strtolower($countryInfos->countryName);
        $cityName = (empty($city)) ? null : strtolower($city);

        if ($countryName === null) {
            return null;
        }

        $localisation = $this->getRepository()->findOneBy(array("state" => $countryName, "city" => $cityName));

        if (empty($localisation)) {
            $localisation = new Localisation($countryName, $cityName);
        }

        if ($localisation->getG_place_id() === null) {
            $place = $this->gplaceapi->search($cityName, $countryName);

            $localisation->setLatitude($place->getLatitude());
            $localisation->setLongitude($place->getLongitude());
            $localisation->setG_place_id($place->getPlaceId());

            $loc = $this->getRepository()->findOneBy(array("g_place_id" => $localisation->getG_place_id()));

            if ($loc !== null) {
                return $loc; // Already in Database
            }

            if (!empty($countryInfos->fipsCode)) {
                $localisation->setFips($countryInfos->fipsCode);
            }
            if (!empty($countryInfos->countryCode)) {
                $localisation->setIsoAlpha2($countryInfos->countryCode);
            }
            if (!empty($countryInfos->isoAlpha3)) {
                $localisation->setIsoAlpha3($countryInfos->isoAlpha3);
            }

            $this->doctrine->persist($localisation);
            $this->doctrine->flush();
            $this->stats++;

            return $localisation;
        }

        return $localisation;
    }

    public function getStats()
    {
        return $this->stats;
    }

    private function getCountryInformation(OutputInterface $output, $countryCode)
    {
        $results = $this->geonameapi->searchByCountryCode($countryCode);

        if (!is_array($results) || count($results) === 0) {
            $output->writeln("<error>No country found for code " . $countryCode . "</error>");

            return null;
        }

        if (count($results) > 1) {
            $output->writeln("<error>" . count($results) . " country found for code " . $countryCode . "</error>");
        }

        return $results[0];
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Localisation");
    }

}
