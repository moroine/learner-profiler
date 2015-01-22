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
     * @Route("/custom.{_format}", defaults={"_format"="json"})
     * @Method({"GET", "POST"})
     */
    public function participantsCustomAction(\Symfony\Component\HttpFoundation\Request $request, $_format)
    {
        $repository = $this->getDoctrine()->getRepository("PfeCoreBundle:Participant");

        $operation = $request->request->get("operation", "list");
        $group = $request->request->get("group", null);
        $filters = $request->request->get("filters", array());
        $legends = $request->request->get("legends", array());

        $results = $repository->findByCustomQuery($operation, $group, $filters);

        foreach ($results as $key => $result) {
            $results[$key]["fillKey"] = $this->getLegend($legends, $results[$key]["entry"]);
        }

        $engine = $this->container->get('templating');
        $content = $engine->render('PfeProviderBundle:Main:raw.' . $_format . '.twig', array('content' => $results, "config" => $this->get('router')->generate('pfe_provider_participant_participantscustom')));

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

    private function getLegend($legends, $value)
    {
        $v = (float) $value;

        foreach ($legends as $legend) {
            if (empty($legend["name"])) {
                continue;
            }
            $min = (is_null($legend["min"])) ? NULL : (float) $legend["min"];
            $max = (is_null($legend["max"])) ? NULL : (float) $legend["max"];

            if ($min !== NULL && $v < $min) {
                continue;
            }

            if ($max !== NULL && $v > $max) {
                continue;
            }

            return $legend["name"];
        }
        return null;
    }

}
