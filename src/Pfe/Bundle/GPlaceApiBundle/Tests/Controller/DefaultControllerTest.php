<?php

namespace Pfe\Bundle\GPlaceApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

    public function testSearchlocality() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/searchLocality');
    }

}
