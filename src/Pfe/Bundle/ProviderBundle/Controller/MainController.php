<?php

namespace Pfe\Bundle\ProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{

    /**
     * @Route("/", name="pfe_provider_endpoint", defaults={"_format"="json"})
     * @Method({"POST"})
     */
    public function indexAction(Request $request, $_format)
    {
        if (!$request->getContent()) {
            $data = array("status" => "Invalid Request");
        } else {
            $visualisation = json_decode($request->getContent());
            $data = $this->getVisualisationData($visualisation);
        }

        $engine = $this->container->get('templating');
        $response = $engine->render('PfeProviderBundle:Main:raw.' . $_format . '.twig', array('content' => $data));

        return new Response($response);
    }

    private function getVisualisationData($visualisation)
    {
        switch ($visualisation->datatype) {
            case 'action':
                $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Action");
                break;
            case 'participant':
                $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");
                break;
        }

        return $repository->getTraceData($visualisation->trace);
    }

}
