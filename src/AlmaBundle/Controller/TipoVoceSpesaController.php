<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\TipoVoceSpesa;
use AlmaBundle\Form\TipoVoceSpesaType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * TipoVoceSpesa controller.
 *
 * @Route("/tipovocespesa")
 */
class TipoVoceSpesaController extends BaseController
{

    /**
     * Lists all TipoVoceSpesa entities.
     *
     * @Route("/", name="tipovocespesa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:TipoVoceSpesa')->findBy(array("eliminabile"=>true));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoVoceSpesa entity.
     *
     * @Route("/", name="tipovocespesa_create")
     * @Method("POST")
     * @Template("AlmaBundle:TipoVoceSpesa:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoVoceSpesa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipovocespesa_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoVoceSpesa entity.
     *
     * @param TipoVoceSpesa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoVoceSpesa $entity)
    {
        $form = $this->createForm(new TipoVoceSpesaType(), $entity, array(
            'action' => $this->generateUrl('tipovocespesa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Inserisci'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoVoceSpesa entity.
     *
     * @Route("/new", name="tipovocespesa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoVoceSpesa();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoVoceSpesa entity.
     *
     * @Route("/{id}", name="tipovocespesa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoVoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoVoceSpesa entity.');
        }


        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing TipoVoceSpesa entity.
     *
     * @Route("/{id}/edit", name="tipovocespesa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoVoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoVoceSpesa entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a TipoVoceSpesa entity.
    *
    * @param TipoVoceSpesa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoVoceSpesa $entity)
    {
        $form = $this->createForm(new TipoVoceSpesaType(), $entity, array(
            'action' => $this->generateUrl('tipovocespesa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
    /**
     * Edits an existing TipoVoceSpesa entity.
     *
     * @Route("/{id}", name="tipovocespesa_update")
     * @Method("PUT")
     * @Template("AlmaBundle:TipoVoceSpesa:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoVoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoVoceSpesa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipovocespesa'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a TipoVoceSpesa entity.
     *
     * @Route("/{id}/delete", name="tipovocespesa_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:TipoVoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoVoceSpesa entity.');
        }

        if(!$entity->isEliminabile()){
            $this->addErrore("Tipo di voce spesa non eliminabile");
            return $this->redirect($this->generateUrl('tipovocespesa'));
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('tipovocespesa'));
    }

}
