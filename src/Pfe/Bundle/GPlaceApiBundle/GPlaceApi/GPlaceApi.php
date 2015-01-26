<?php

namespace Pfe\Bundle\GPlaceApiBundle\GPlaceApi;

class GPlaceApi
{

    /**
     *
     * @var \Buzz\Browser
     */
    private $buzz;
    private $endpoint;
    private $key;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function search($city, $state)
    {
        $place = $this->findPlace($city, $state);

        if ($place !== null) {
            return $place;
        }

        $place = $this->searchLocality($city, $state);

        if ($place !== null) {
            return $place;
        }

        return $this->searchState($state);
    }

    /**
     *
     * @param string $city
     * @param string $state
     * @return \Pfe\Bundle\GPlaceApiBundle\Entity\Place
     */
    public function searchLocality($city, $state)
    {
        if (empty($city)) {
            return null;
        }

        $city = strtolower($city);
        $state = strtolower($state);

        if ($city === $state) {
            $state = null;
        }

        $place = $this->findPlace($city, $state);
        if ($place !== null) {
            return $place;
        }

        if (empty($state)) {
            $query = rawurlencode($city);
        } else {
            $query = rawurlencode($city) . '+' . rawurlencode($state);
        }

        $types = 'political';
        $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);

        if (count($gplaces) === 0) {
            $types = 'postal_code';
            $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);

            if (count($gplaces) === 1) {
                $query = urlencode($gplaces[0]->formatted_address);
                $types = 'political';
                $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);
            }
        }

        if (count($gplaces) === 0) {
            return null;
        }

        $gplace = $gplaces[0];

        $place = $this->em->getRepository("PfeGPlaceApiBundle:Place")->findOneBy(array("place_id" => $gplace->place_id));
        if ($place !== null) {
            return $place;
        }

        $place = new \Pfe\Bundle\GPlaceApiBundle\Entity\Place();

        $place->setAddress($gplace->formatted_address);
        $place->setLatitude($gplace->geometry->location->lat);
        $place->setLongitude($gplace->geometry->location->lng);
        $place->setPlaceId($gplace->place_id);

        $city_name = (in_array("locality", $gplace->types)) ? strtolower($gplace->name) : strtolower($city);
        $country_name = (in_array("country", $gplace->types)) ? strtolower($gplace->name) : strtolower($state);

        $place->setCity($city_name);
        $place->setCountry($country_name);

        $this->em->persist($place);

        return $place;
    }

    public function searchState($state)
    {
        $place = $this->findPlace(null, $state);
        if ($place !== null) {
            return $place;
        }

        if (empty($state)) {
            return null;
        }

        $query = urlencode($state);
        $types = 'country';
        $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);

        if (count($gplaces) === 0) {
            return null;
        }

        $gplace = $gplaces[0];

        $place = $this->em->getRepository("PfeGPlaceApiBundle:Place")->findOneBy(array("place_id" => $gplace->place_id));
        if ($place !== null) {
            return $place;
        }

        $place = new \Pfe\Bundle\GPlaceApiBundle\Entity\Place();

        $place->setAddress($gplace->formatted_address);
        $place->setLatitude($gplace->geometry->location->lat);
        $place->setLongitude($gplace->geometry->location->lng);
        $place->setPlaceId($gplace->place_id);

        $country_name = (in_array("country", $gplace->types)) ? strtolower($gplace->name) : strtolower($state);

        $place->setCountry($country_name);

        $this->em->persist($place);

        return $place;
    }

    /**
     *
     * @param string $city
     * @param string $state
     * @return \Pfe\Bundle\GPlaceApiBundle\Entity\Place
     */
    private function findPlace($city, $state)
    {
        $c = (empty($city)) ? null : strtolower($city);
        $s = (empty($state)) ? null : strtolower($state);

        return $this->em->getRepository("PfeGPlaceApiBundle:Place")->findOneBy(array("city" => $c, "country" => $s));
    }

    private function request($url)
    {
        try {
            $this->buzz->get($url, array('Content-type: application/json'));
        } catch (Buzz\Exception\RequestException $e) {
            return null;
        }

        $response = json_decode($this->buzz->getLastResponse()->getContent());

        $status = $response->status; //TODO: check status

        return $response->results;
    }

    public function setDoctrine($doctrine)
    {
        $this->em = $doctrine;
    }

    public function setConfig($config)
    {
        $this->endpoint = $config['endpoint'];
        $this->key = $config['key'];
    }

    public function setBuzz(\Buzz\Browser $buzz)
    {
        $this->buzz = $buzz;
    }

}
