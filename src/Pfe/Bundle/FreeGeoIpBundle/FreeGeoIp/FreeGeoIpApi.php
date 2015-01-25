<?php

namespace Pfe\Bundle\FreeGeoIpBundle\FreeGeoIp;

class FreeGeoIpApi
{

    private $buzz;
    private $endpoint;

    /**
     *
     * @var \Doctrine\ORM\EntityManager $em
     */
    private $em;

    /**
     *
     * @param String $ip
     * @return \Pfe\Bundle\FreeGeoIpBundle\Entity\IpLocation
     */
    public function find($ip)
    {
        $repo = $this->em->getRepository("PfeFreeGeoIpBundle:IpLocation");

        /** @var \Pfe\Bundle\FreeGeoIpBundle\Entity\IpLocation $location */
        $location = $repo->findOneBy(array("ip" => $ip));

        if ($location === null) {
            $request = $this->endpoint . 'json/' . $ip;
            $this->buzz->get($request, array('Content-type: application/json'));
            $response = json_decode($this->buzz->getLastResponse()->getContent());

            $location = new \Pfe\Bundle\FreeGeoIpBundle\Entity\IpLocation();
            if (!$response) {
                return null;
            }
            $location->setIp($response->ip);
            $location->setCountry($response->country_name);
            $location->setIsoAlpha2($response->country_code);
            $location->setCity($response->city);

            $this->em->persist($location);
            $this->em->flush($location);
            $this->em->detach($location);
        }

        return $location;
    }

    public function setDoctrine($doctrine)
    {
        $this->em = $doctrine;
    }

    public function setConfig($config)
    {
        $this->endpoint = $config['endpoint'];
    }

    public function setBuzz(\Buzz\Browser $buzz)
    {
        $this->buzz = $buzz;
    }

}
