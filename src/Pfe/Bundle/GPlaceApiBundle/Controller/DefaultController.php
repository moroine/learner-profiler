<?php

namespace Pfe\Bundle\GPlaceApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Route("/searchLocality/{state}/{city}.{_format}")
     * @Template()
     */
    public function searchLocalityAction($city, $state) {
        $gp = $this->container->get('pfe_services.g_place_api');

        $result = $gp->searchLocality($city, $state);

        var_dump($result);
        die();

        return array('result' => $result);
    }

}
