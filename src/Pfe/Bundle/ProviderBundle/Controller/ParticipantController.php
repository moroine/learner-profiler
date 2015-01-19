<?php

namespace Pfe\Bundle\ProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Pfe\Bundle\CoreBundle\Entity\Participant;

/**
 * @Route("/participant")
 */
class ParticipantController extends Controller
{

    public function renderParticipantsAction(array $participants, $_format)
    {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Participant:participants.' . $_format . '.twig', array('participants' => $participants));

        return new Response($content);
    }

    public function renderParticipantAction(Participant $participant, $_format)
    {
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Participant:participant.' . $_format . '.twig', array('participant' => $participant));

        return new Response($content);
    }

    /**
     * @Route(".{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function participantsAction($_format)
    {
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");

        $participants = $repository->findAll();

        return $this->renderParticipantsAction($participants, $_format);
    }

    /**
     * @Route("/count.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function participantsCountAction(\Symfony\Component\HttpFoundation\Request $request, $_format)
    {
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");

        $group = $request->query->has('group') ? $request->query->get('group') : null;

        $results = $repository->count();

        $count = array();
        foreach ($results as $key => $result) {
            if ($result['isoAlpha3'] !== null) {
                $count[$result['isoAlpha3']] = array(
                    "fillKey" => "",
                    "number" => $result['n']
                );
            } else {
                $count["none"] = array(
                    "fillKey" => "",
                    "number" => $result['n']
                );
            }
        }
        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Main:raw.' . $_format . '.twig', array('content' => $count));

        return new Response($content);
    }

    /**
     *
     * @Route("/{id}.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     * @ParamConverter("participant", class="PfeCoreBundle:Participant")
     */
    public function participantAction(Participant $participant, $_format)
    {
        return $this->renderParticipantAction($participant, $_format);
    }

    /**
     * @Route("/{id}/localisation.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function participantLocalisationAction(Participant $participant, $_format)
    {
        $localisation = $participant->getHome();

        return $this->forward("PfeProviderBundle:Localisation:renderLocalisation", array(
                    "localisation" => $localisation,
                    "_format" => $_format
        ));
    }

}
