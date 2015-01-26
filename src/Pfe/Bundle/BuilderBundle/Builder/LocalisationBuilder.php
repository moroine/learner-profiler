<?php

namespace Pfe\Bundle\BuilderBundle\Builder;

use Symfony\Component\Console\Output\OutputInterface;
use Pfe\Bundle\CoreBundle\Entity\Localisation;
use Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi;
use Pfe\Bundle\GeonamesApiBundle\GeonamesApi\GeonamesApi;
use Pfe\Bundle\FreeGeoIpBundle\FreeGeoIp\FreeGeoIpApi;

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

    /**
     *
     * @var FreeGeoIpApi
     */
    private $freegeoipapi;

    public function __construct(\Doctrine\ORM\EntityManager $doctrine, GeonamesApi $geonameapi, GPlaceApi $gplaceapi, FreeGeoIpApi $freegeoipapi)
    {
        parent::__construct($doctrine);

        $this->gplaceapi = $gplaceapi;
        $this->geonameapi = $geonameapi;
        $this->freegeoipapi = $freegeoipapi;
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
        $geolocation = $this->geonameapi->searchByCountryCode($countryCode);

        if (!$geolocation) {
            return null;
        }

        $countryName = $geolocation->getCountry();
        $cityName = (empty($city)) ? null : strtolower($city);

        $place = $this->gplaceapi->search($cityName, $countryName);

        $localisation = $this->getRepository()->findOneBy(array("g_place_id" => $place->getPlaceId()));

        if ($localisation) {
            return $localisation;
        }
        $localisation = new Localisation($countryName, $cityName);

        $localisation->setFips($geolocation->getFips());
        $localisation->setIsoAlpha2($geolocation->getIsoAlpha2());
        $localisation->setIsoAlpha3($geolocation->getIsoAlpha3());
        $localisation->setLatitude($place->getLatitude());
        $localisation->setLongitude($place->getLongitude());
        $localisation->setG_place_id($place->getPlaceId());

        $this->stats++;
        $this->doctrine->persist($localisation);
        $this->doctrine->flush();
//        $this->doctrine->detach($localisation);

        return $localisation;
    }

    /**
     *
     * @param OutputInterface $output
     * @param string $ip
     * @return Localisation
     */
    public function getLocalisationFromIp(OutputInterface $output, $ip)
    {
        $iplocation = $this->freegeoipapi->find($ip);

        if (!$iplocation) {
            return null;
        }

        return $this->getLocalisation($output, $iplocation->getIsoAlpha2(), $iplocation->getCity());
    }

    public function getStats()
    {
        return $this->stats;
    }

    protected function getRepository()
    {
        return $this->doctrine->getRepository("PfeCoreBundle:Localisation");
    }

}
