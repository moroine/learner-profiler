<?php

namespace Pfe\Bundle\AnalyserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $config = array(
            "endpoint" => $request->getBaseUrl() . "/provider"
        );

        return array("config" => $config);
    }

}
