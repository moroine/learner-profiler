<?php

namespace Pfe\Bundle\GPlaceApiBundle\GPlaceApi;

class GPlaceApi {

    private $buzz;
    private $endpoint;
    private $key;

    public function searchLocality($city, $state) {

        $query = urlencode($city) . '+' . urlencode($state);
        $types = 'political';

        $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);

        if (count($gplaces) === 0) {
            $types = 'postal_code';
            $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);
        }

        return $gplaces;
    }

    public function searchState($state) {
        $query = urlencode($state);
        $types = 'country';

        $gplaces = $this->request($this->endpoint . '/json?query=' . $query . '&types=' . $types . '&key=' . $this->key);


        return $gplaces;
    }

    private function request($url) {
        $this->buzz->get($url, array('Content-type: application/json'));

        $response = json_decode($this->buzz->getLastResponse()->getContent());

        $status = $response->status; //TODO: check status

        return $response->results;
    }

    public function setConfig($config) {
        $this->endpoint = $config['endpoint'];
        $this->key = $config['key'];
    }

    public function setBuzz(\Buzz\Browser $buzz) {
        $this->buzz = $buzz;
    }

}
