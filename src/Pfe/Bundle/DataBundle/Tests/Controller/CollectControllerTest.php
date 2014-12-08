<?php

namespace Pfe\Bundle\DataBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CollectControllerTest extends WebTestCase {

    public function testLogin() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

    public function testParticipant() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/participant');
    }

}
