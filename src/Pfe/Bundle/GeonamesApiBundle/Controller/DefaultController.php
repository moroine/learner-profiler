<?php

namespace Pfe\Bundle\GeonamesApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Route("/searchByCode/{code}.{_format}")
     * @Template()
     */
    public function searchCountryByCodeAction($code) {
        $gp = $this->container->get('pfe_services.geonames_api');

        $result = $gp->searchByCountryCode($code);

        return array('result' => $result);
    }

}
