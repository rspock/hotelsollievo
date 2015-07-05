<?php

namespace AlmaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AlmaBundle\Entity\Ricevuta;

/**
 * Ricevuta controller.
 *
 * @Route("/ricevuta")
 */
class RicevutaController extends Controller
{

    /**
     * Lists all Ricevuta entities.
     *
     * @Route("/", name="ricevuta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:Ricevuta')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Ricevuta entity.
     *
     * @Route("/{id}", name="ricevuta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Ricevuta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ricevuta entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
