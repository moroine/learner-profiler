<?php

namespace Pfe\Bundle\GeonamesApiBundle\GeonamesApi;

use Pfe\Bundle\GeonamesApiBundle\Entity\GeoLocation;
use Doctrine\ORM\EntityManager;

class GeonamesApi
{

    /**
     *
     * @var \Buzz\Browser
     */
    private $buzz;

    /**
     * @var EntityManager
     */
    private $doctrine;
    private $endpoint;
    private $username;

    /**
     *
     * @param string $code isoAlpha2 format
     * @return GeoLocation
     */
    public function searchByCountryCode($code)
    {
        if (empty($code)) {
            return null;
        }

        $isoAlpha2 = strtoupper($code);

        $location = $this->doctrine->getRepository("PfeGeonamesApiBundle:GeoLocation")->findOneBy(array('isoAlpha2' => $isoAlpha2));

        if (!$location) {
            $request = $this->endpoint . 'country=' . urlencode($isoAlpha2) . '&username=' . $this->username;

            $this->buzz->get($request, array('Content-type: application/json'));

            $response = json_decode($this->buzz->getLastResponse()->getContent());

            if (!$response || !$response->geonames) {
                return null;
            }
            $geoname = $response->geonames[0];
            $location = new GeoLocation();
            $location->setCapital($geoname->capital);
            $location->setContinent($geoname->continentName);
            $location->setCountry($geoname->countryName);
            $location->setFips($geoname->fipsCode);
            $location->setIsoAlpha2($geoname->countryCode);
            $location->setIsoAlpha3($geoname->isoAlpha3);

            $this->doctrine->persist($location);
        }

        return $location;
    }

    public function setConfig($config)
    {
        $this->endpoint = $config['endpoint'];
        $this->username = $config['username'];
    }

    public function setBuzz(\Buzz\Browser $buzz)
    {
        $this->buzz = $buzz;
    }

    public function setDoctrine(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

}
