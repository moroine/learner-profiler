<?php

namespace Pfe\Bundle\FreeGeoIpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/search/{ip}", requirements={"ip"="^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$"})
     * @Template()
     */
    public function indexAction($ip)
    {
        $gp = $this->container->get('pfe_services.freegeoip_api');

        $result = $gp->find($ip);

        return array('result' => $result);
    }

}
