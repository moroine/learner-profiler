<?php

namespace Pfe\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\CoreBundle\Entity\Theme;

/**
 * Theme controller.
 *
 * @Route("/theme")
 */
class ThemeController extends Controller
{

    /**
     * Lists all Theme entities.
     *
     * @Route("/", name="theme")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PfeCoreBundle:Theme')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @Route("/{id}", name="theme_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PfeCoreBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
