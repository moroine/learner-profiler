<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Localisation;

/**
 * Localisation controller.
 *
 * @Route("/localisation")
 */
class LocalisationController extends Controller
{

    /**
     * Lists all Localisation entities.
     *
     * @Route("/", name="localisation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Localisation')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Localisation entity.
     *
     * @Route("/{id}", name="localisation_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Localisation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Localisation entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
