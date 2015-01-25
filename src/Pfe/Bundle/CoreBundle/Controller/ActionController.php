<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Action;

/**
 * Action controller.
 *
 * @Route("/action")
 */
class ActionController extends Controller
{

    /**
     * Lists all Action entities.
     *
     * @Route("/", name="action")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Action')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Action entity.
     *
     * @Route("/{id}", name="action_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Action')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Action entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
