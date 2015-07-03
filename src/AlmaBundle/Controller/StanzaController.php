<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\Letto;
use AlmaBundle\Entity\Stanza;
use AlmaBundle\Form\StanzaType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Stanza controller.
 *
 * @Route("/stanza")
 */
class StanzaController extends BaseController
{

    /**
     * Lists all Stanza entities.
     *
     * @Route("/", name="stanza")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:Stanza')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Stanza entity.
     *
     * @Route("/", name="stanza_create")
     * @Method("POST")
     * @Template("AlmaBundle:Stanza:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Stanza();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $letti = new ArrayCollection();
            for($i=0; $i<$entity->getTipo()->getLetti();$i++){
                $letto = new Letto();
                $letto->setDescrizione("Stanza " . $entity->getNumero() . " Letto " . $i);
                $letto->setStanza($entity);
                $em->persist($letto);
                $letti->add($letto);
            }
            $entity->setLetti($letti);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stanza_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Stanza entity.
     *
     * @param Stanza $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Stanza $entity)
    {
        $form = $this->createForm(new StanzaType(), $entity, array(
            'action' => $this->generateUrl('stanza_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Inserisci'));

        return $form;
    }

    /**
     * Displays a form to create a new Stanza entity.
     *
     * @Route("/new", name="stanza_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Stanza();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Stanza entity.
     *
     * @Route("/{id}", name="stanza_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Stanza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stanza entity.');
        }


        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Stanza entity.
     *
     * @Route("/{id}/edit", name="stanza_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $this->addErrore("Funzione non supportata");
        return $this->redirect($this->generateUrl('stanza'));
        /*
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Stanza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stanza entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
        */
    }

    /**
    * Creates a form to edit a Stanza entity.
    *
    * @param Stanza $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Stanza $entity)
    {
        $form = $this->createForm(new StanzaType(), $entity, array(
            'action' => $this->generateUrl('stanza_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
    /**
     * Edits an existing Stanza entity.
     *
     * @Route("/{id}", name="stanza_update")
     * @Method("PUT")
     * @Template("AlmaBundle:Stanza:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Stanza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stanza entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->addSucces("Modifica effettuata con successo");
            return $this->redirect($this->generateUrl('stanza'));
        }
        $this->addErrore("Errore nella valorizzazione dei campi");
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Stanza entity.
     *
     * @Route("/{id}/delete", name="stanza_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Stanza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stanza entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('stanza'));
    }
}
