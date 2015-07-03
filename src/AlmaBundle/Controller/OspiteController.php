<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\Conto;
use AlmaBundle\Entity\Ospite;
use AlmaBundle\Form\DataClass\OspiteVoceSpesa;
use AlmaBundle\Form\OspiteType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Ospite controller.
 *
 * @Route("/ospite")
 */
class OspiteController extends BaseController
{

    /**
     * Lists all Ospite entities.
     *
     * @Route("/", name="ospite")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:Ospite')->findAll();
        $formOspiteVoceSpesa = $this->createOspiteVoceSpesaForm(new OspiteVoceSpesa());

        $tipiVociSpesa = $em->getRepository('AlmaBundle:TipoVoceSpesa')->findAll();
        $serializer = $this->container->get('jms_serializer');
        $tipiVociSpesaJson = $serializer->serialize($tipiVociSpesa, 'json');

        return array(
            'entities' => $entities,
            'voce_spesa_form'=>$formOspiteVoceSpesa->createView(),
            'tipi_voci_spesa' =>$tipiVociSpesaJson
        );
    }
    /**
     * Creates a new Ospite entity.
     *
     * @Route("/", name="ospite_create")
     * @Method("POST")
     * @Template("AlmaBundle:Ospite:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Ospite();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $conto = new Conto();
            $conto->setPersona($entity);
            $conto->setDataApertura(new \DateTime());
            $conto->setStato("APERTO");
            $em->persist($conto);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ospite_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Ospite entity.
     *
     * @param Ospite $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ospite $entity)
    {
        $form = $this->createForm(new OspiteType(), $entity, array(
            'action' => $this->generateUrl('ospite_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Inserisci'));

        return $form;
    }

    /**
     * Displays a form to create a new Ospite entity.
     *
     * @Route("/new", name="ospite_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ospite();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Ospite entity.
     *
     * @Route("/{id}", name="ospite_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Ospite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospite entity.');
        }

        //trovo tutti i conti della persona
        $conti = $em->getRepository('AlmaBundle:Conto')->findByPersona($id);

        return array(
            'entity'      => $entity,
            'conti' => $conti,
            'backUrl' => $this->generateUrl('ospite')
        );
    }

    /**
     * Displays a form to edit an existing Ospite entity.
     *
     * @Route("/{id}/edit", name="ospite_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Ospite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospite entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Ospite entity.
    *
    * @param Ospite $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ospite $entity)
    {
        $form = $this->createForm(new OspiteType(), $entity, array(
            'action' => $this->generateUrl('ospite_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
    /**
     * Edits an existing Ospite entity.
     *
     * @Route("/{id}", name="ospite_update")
     * @Method("PUT")
     * @Template("AlmaBundle:Ospite:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Ospite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospite entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ospite_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
    /**
     * Deletes a Ospite entity.
     *
     * @Route("/{id}/delete", name="ospite_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Ospite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospite entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ospite'));
    }

}
