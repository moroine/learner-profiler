<?php

namespace Pfe\Bundle\GeonamesApiBundle\GeonamesApi;

class GeonamesApi {

    private $buzz;
    private $endpoint;
    private $username;

    public function searchByCountryCode($code) {

        $request = $this->endpoint . 'country=' . urlencode($code) . '&username=' . $this->username;

        $this->buzz->get($request, array('Content-type: application/json'));

        $response = json_decode($this->buzz->getLastResponse()->getContent());


        return $response->geonames;
    }

    public function setConfig($config) {
        $this->endpoint = $config['endpoint'];
        $this->username = $config['username'];
    }

    public function setBuzz(\Buzz\Browser $buzz) {
        $this->buzz = $buzz;
    }

}
