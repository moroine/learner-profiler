<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Module;

/**
 * Module controller.
 *
 * @Route("/module")
 */
class ModuleController extends Controller
{

    /**
     * Lists all Module entities.
     *
     * @Route("/", name="module")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Module')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Module entity.
     *
     * @Route("/{id}", name="module_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Module')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Module entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
