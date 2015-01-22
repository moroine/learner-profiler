<?php

namespace Pfe\Bundle\GPlaceApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/locality/{state}/{city}.{_format}")
     * @Template()
     */
    public function searchLocalityAction($city, $state)
    {
        $gp = $this->container->get('pfe_services.g_place_api');

        $result = $gp->searchLocality($city, $state);

        return array('result' => $result);
    }

    /**
     * @Route("/country/{state}.{_format}")
     * @Template()
     */
    public function searchStateAction($state)
    {
        $gp = $this->container->get('pfe_services.g_place_api');

        $result = $gp->searchState($state);

        return array('result' => $result);
    }

}
