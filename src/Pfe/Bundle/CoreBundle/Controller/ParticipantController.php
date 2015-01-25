<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Participant;

/**
 * Participant controller.
 *
 * @Route("/participant")
 */
class ParticipantController extends Controller
{

    /**
     * Lists all Participant entities.
     *
     * @Route("/", name="participant")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Participant')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Participant entity.
     *
     * @Route("/{id}", name="participant_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Participant')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participant entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
