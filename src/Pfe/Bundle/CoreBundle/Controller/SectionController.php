<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Section;

/**
 * Section controller.
 *
 * @Route("/section")
 */
class SectionController extends Controller
{

    /**
     * Lists all Section entities.
     *
     * @Route("/", name="section")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Section')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Section entity.
     *
     * @Route("/{id}", name="section_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Section')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Section entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
