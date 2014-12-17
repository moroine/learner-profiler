<?php

namespace Pfe\Bundle\ProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Pfe\Bundle\CoreBundle\Entity\Participant;

/**
 * @Route("/participant")
 */
class ParticipantController extends Controller {

    public function renderParticipantsAction(array $participants, $_format) {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Participant:participants.' . $_format . '.twig', array('participants' => $participants));

        return new Response($content);
    }

    public function renderParticipantAction(Participant $participant, $_format) {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Participant:participant.' . $_format . '.twig', array('participant' => $participant));

        return new Response($content);
    }

    /**
     * @Route(".{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function participantsAction($_format) {
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");

        $participants = $repository->findAll();

        return $this->renderParticipantsAction($participants, $_format);
    }

    /**
     *
     * @Route("/{id}.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     * @ParamConverter("participant", class="PfeCoreBundle:Participant")
     * @Template()
     */
    public function participantAction(Participant $participant, $_format) {
        return $this->renderParticipantAction($participant, $_format);
    }

    /**
     * @Route("/{id}/localisation.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     * @ParamConverter("participant", class="PfeCoreBundle:Participant")
     * @Template()
     */
    public function participantLocalisationAction(Participant $participant, $_format) {
        $localisation = $participant->getHome();

        return $this->forward("PfeProviderBundle:Localisation:renderLocalisation", array(
                "localisation" => $localisation,
                "_format" => $_format
        ));
    }

}