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
    public function indexAction()
    {
        return array(
            "routing" => array("provider" => $this->get('router')->generate('pfe_provider_endpoint', array("_format" => 'json')))
        );
    }

}
