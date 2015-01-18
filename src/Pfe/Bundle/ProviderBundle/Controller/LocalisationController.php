<?php

namespace Pfe\Bundle\ProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Pfe\Bundle\CoreBundle\Entity\Localisation;

/**
 * @Route("/localisation")
 */
class LocalisationController extends Controller {

    public function renderLocalisationsAction(array $localisations, $_format) {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Localisation:localisations.' . $_format . '.twig', array('localisations' => $localisations));

        return new Response($content);
    }

    public function renderLocalisationAction(Localisation $localisation, $_format) {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Localisation:localisation.' . $_format . '.twig', array('localisation' => $localisation));

        return new Response($content);
    }

    /**
     * @Route(".{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function localisationsAction($_format) {
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Localisation");

        $localisations = $repository->findAll();

        return $this->renderLocalisationsAction($localisations, $_format);
    }

    /**
     * @Route("/{id}.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     * @ParamConverter()
     */
    public function localisationAction(Localisation $localisation, $_format) {
        return $this->renderLocalisationAction($localisation, $_format);
    }

    /**
     * @Route("/{id}/participant.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     * @ParamConverter("localisation", class="PfeCoreBundle:Localisation")
     * @Template()
     */
    public function localisationParticipantsAction(Localisation $localisation, $_format) {
        /* @var Pfe\Bundle\CoreBundle\Entity\ParticipantRepository $repository */
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");

        $participants = $repository->findByHome($localisation);

        return $this->forward("PfeProviderBundle:Participant:renderParticipants", array(
                "participants" => $participants,
                "_format" => $_format
        ));
    }

}
