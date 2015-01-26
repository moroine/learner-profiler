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
            "config" => $this->get('router')->generate('pfe_provider_participant_participantscustom', array("_format" => 'json'))
        );
    }

}
