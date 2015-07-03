<?php

namespace AlmaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AlmaBundle\Entity\Tariffa;
use AlmaBundle\Form\TariffaType;

/**
 * Tariffa controller.
 *
 * @Route("/tariffa")
 */
class TariffaController extends Controller
{

    /**
     * Lists all Tariffa entities.
     *
     * @Route("/", name="tariffa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:Tariffa')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Tariffa entity.
     *
     * @Route("/", name="tariffa_create")
     * @Method("POST")
     * @Template("AlmaBundle:Tariffa:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tariffa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tariffa_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tariffa entity.
     *
     * @param Tariffa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tariffa $entity)
    {
        $form = $this->createForm(new TariffaType(), $entity, array(
            'action' => $this->generateUrl('tariffa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tariffa entity.
     *
     * @Route("/new", name="tariffa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tariffa();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tariffa entity.
     *
     * @Route("/{id}", name="tariffa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Tariffa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tariffa entity.');
        }


        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Tariffa entity.
     *
     * @Route("/{id}/edit", name="tariffa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Tariffa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tariffa entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Tariffa entity.
    *
    * @param Tariffa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tariffa $entity)
    {
        $form = $this->createForm(new TariffaType(), $entity, array(
            'action' => $this->generateUrl('tariffa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tariffa entity.
     *
     * @Route("/{id}", name="tariffa_update")
     * @Method("PUT")
     * @Template("AlmaBundle:Tariffa:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Tariffa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tariffa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tariffa'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a TipoVoceSpesa entity.
     *
     * @Route("/{id}/delete", name="tariffa_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Tariffa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoVoceSpesa entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('tariffa'));
    }

}
