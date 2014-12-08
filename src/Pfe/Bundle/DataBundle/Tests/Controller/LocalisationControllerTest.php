<?php

namespace Pfe\Bundle\DataBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocalisationControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testListstates() {
        $client = static::createClient();

        $crawler = $client->request('GET', 'state');
    }

    public function testListcities() {
        $client = static::createClient();

        $crawler = $client->request('GET', 'city');
    }

}
