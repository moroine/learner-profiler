<?php

namespace Pfe\Bundle\GPlaceApiBundle\GPlaceApi;

class GPlaceApi {

    private $buzz;
    private $endpoint;
    private $key;

    public function searchLocality($city, $state) {

        $query = urlencode($city) . '+' . urlencode($state);
        $types = 'locality';

        $this->buzz->get($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key, array('Content-type: application/json'));

        $response = json_decode($this->buzz->getLastResponse()->getContent());

        $status = $response->status; //TODO: check status

        $gplaces = $response->results;

        return $gplaces;
    }

    public function searchState($state) {

        $query = urlencode($state);
        $types = 'country';

        $this->buzz->get($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key, array('Content-type: application/json'));

        $response = json_decode($this->buzz->getLastResponse()->getContent());

        $status = $response->status; //TODO: check status

        $gplaces = $response->results;

        return $gplaces;
    }

    public function setConfig($config) {
        $this->endpoint = $config['endpoint'];
        $this->key = $config['key'];
    }

    public function setBuzz(\Buzz\Browser $buzz) {
        $this->buzz = $buzz;
    }

}
