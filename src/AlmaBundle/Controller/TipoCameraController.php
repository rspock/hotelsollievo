<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\TipoCamera;
use AlmaBundle\Form\TipoCameraType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * TipoCamera controller.
 *
 */
class TipoCameraController extends Controller
{

    /**
     * Lists all TipoCamera entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:TipoCamera')->findAll();

        return $this->render('AlmaBundle:TipoCamera:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TipoCamera entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TipoCamera();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipocamera_show', array('id' => $entity->getId())));
        }

        return $this->render('AlmaBundle:TipoCamera:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TipoCamera entity.
     *
     * @param TipoCamera $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoCamera $entity)
    {
        $form = $this->createForm(new TipoCameraType(), $entity, array(
            'action' => $this->generateUrl('tipocamera_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Inserisci'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoCamera entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoCamera();
        $form   = $this->createCreateForm($entity);

        return $this->render('AlmaBundle:TipoCamera:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TipoCamera entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoCamera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCamera entity.');
        }


        return $this->render('AlmaBundle:TipoCamera:show.html.twig', array(
            'entity'      => $entity
        ));
    }

    /**
     * Displays a form to edit an existing TipoCamera entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoCamera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCamera entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('AlmaBundle:TipoCamera:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a TipoCamera entity.
    *
    * @param TipoCamera $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoCamera $entity)
    {
        $form = $this->createForm(new TipoCameraType(), $entity, array(
            'action' => $this->generateUrl('tipocamera_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
    /**
     * Edits an existing TipoCamera entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:TipoCamera')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCamera entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipocamera'));
        }

        return $this->render('AlmaBundle:TipoCamera:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a TipoCamera entity.
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AlmaBundle:TipoCamera')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoCamera entity.');
            }

            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('tipocamera'));
    }

}
