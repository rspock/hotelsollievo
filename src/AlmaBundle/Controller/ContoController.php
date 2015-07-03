<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\Conto;
use AlmaBundle\Entity\TipoVoceSpesa;
use AlmaBundle\Entity\VoceSpesa;
use AlmaBundle\Form\ContoType;
use AlmaBundle\Form\DataClass\OspiteVoceSpesa;
use AlmaBundle\Form\VoceSpesaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Conto controller.
 *
 * @Route("/conto")
 */
class ContoController extends BaseController
{

    /**
     * Lists all Conto entities.
     *
     * @Route("/", name="conto")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AlmaBundle:Conto')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Conto entity.
     *
     * @Route("/new_conto_persona/{idPersona}", name="conto_persona_create")
     * @Method("GET")
     */
    public function createContoPersonaAction(Request $request, $idPersona){
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository("AlmaBundle:Persona")->find($idPersona);
        if(!$persona->hasContoAperto()){
            $conto = new Conto();
            $conto->setPersona($persona);
            $conto->setDataApertura(new \DateTime());
            $conto->setStato("APERTO");
            $em->persist($conto);
            $em->flush();
        }
        $this->addSucces("Conto aperto correttamento");
        return $this->returnResponse($request);
    }

    /**
     * Finds and displays a Conto entity.
     *
     * @Route("/{id}", name="conto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }


        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Conto entity.
     *
     * @Route("/{id}/edit", name="conto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Conto entity.
    *
    * @param Conto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Conto $entity)
    {
        $form = $this->createForm(new ContoType(), $entity, array(
            'action' => $this->generateUrl('conto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
    /**
     * Edits an existing Conto entity.
     *
     * @Route("/{id}", name="conto_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->addSucces("Conto modificato con successo");
            return $this->redirect($this->generateUrl('conto'));
        }
        $this->addErrore("Campi non validi");
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Conto entity.
     *
     * @Route("/{id}/delete", name="conto_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->addSucces("Conto eliminato con successo");
        return $this->redirect($this->generateUrl('conto'));
    }

    /**
     * Aggiunge una voce spesa ad Conto entity.
     *
     * @Route("/aggiungi_voce_spesa", name="conto_aggiungi_voce_spesa")
     * @Method("POST")
     */
    public function aggiungiVoceSpesaAction(Request $request)
    {

        $ospiteVoceSpesa = new OspiteVoceSpesa();
        $form = $this->createOspiteVoceSpesaForm($ospiteVoceSpesa);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $conto = $em->getRepository('AlmaBundle:Conto')->findAttivoDaOspite($ospiteVoceSpesa->getOspiteId());

            if(is_null($conto)){
                $this->addErrore("nessun conto attivo per la persona indicata");
                return $this->redirect($this->generateUrl('ospite'));
            }

            $tipoVoceSpesa = $ospiteVoceSpesa->getTipoVoceSpesa();
            if($tipoVoceSpesa == -1){
                //aggiungo la nuova voce spesa
                if($ospiteVoceSpesa->getDescrizione() == ""){
                    $this->addErrore("In caso di selezione di altro occorre indicare la descrizione");
                    return $this->redirect($this->generateUrl('ospite'));
                }else{
                    $tipoVoceSpesa = new TipoVoceSpesa();
                    $tipoVoceSpesa->setDescrizione($ospiteVoceSpesa->getDescrizione());
                    $tipoVoceSpesa->setPrezzoStandard($ospiteVoceSpesa->getImporto());
                    $tipoVoceSpesa->setNegativa($ospiteVoceSpesa->getSconto());
                    $em->persist($tipoVoceSpesa);
                }
            }else{
                $tipoVoceSpesa = $em->getRepository("AlmaBundle:TipoVoceSpesa")->find($tipoVoceSpesa);
            }
            $voceSpesa = new VoceSpesa();
            $voceSpesa->setConto($conto);
            $voceSpesa->setDataRegistrazione(new \DateTime());
            $voceSpesa->setImporto($ospiteVoceSpesa->getImporto());
            $voceSpesa->setTipo($tipoVoceSpesa);
            $em->persist($voceSpesa);
            $em->flush();

            $this->addSucces("Voce spesa aggiunta correttamente");
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }


        return $this->redirect($this->generateUrl('ospite'));
    }


    /**
     * Chiudi Conto entity.
     *
     * @Route("/{id}/chiusura", name="conto_chiusura")
     * @Method("GET")
     */
    public function chiusuraAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $entity->setDataPagamento(new \DateTime());
        $entity->setStato("CHIUSO");
        $em->persist($entity);

        $conto = new Conto();
        $conto->setStato("APERTO");
        $conto->setDataApertura(new \DateTime());
        $conto->setPersona($entity->getPersona());
        $em->persist($conto);

        $em->flush();

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * Salda Conto entity.
     *
     * @Route("/{id}/saldo", name="conto_saldo")
     * @Method("GET")
     */
    public function saldoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $entity->setDataPagamento(new \DateTime());
        $entity->setStato("CHIUSO");
        $entity->setSaldo(true);
        $em->persist($entity);
        $em->flush();

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * Mostra le voci spesa di un conto.
     *
     * @Route("/{id}/voci_spesa", name="voci_spesa")
     * @Method("GET")
     * @Template()
     */
    public function vociSpesaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $modificaVoceSpesaForm = $this->createVoceSpesaEditForm(new VoceSpesa());

        $formOspiteVoceSpesa = $this->createOspiteVoceSpesaForm(new OspiteVoceSpesa());

        $tipiVociSpesa = $em->getRepository('AlmaBundle:TipoVoceSpesa')->findAll();
        $serializer = $this->container->get('jms_serializer');
        $tipiVociSpesaJson = $serializer->serialize($tipiVociSpesa, 'json');


        return array(
            'conto' => $entity,
            'vociSpesa' => $entity->getVociSpesa(),
            'modifica_voce_spesa_form' => $modificaVoceSpesaForm->createView(),
            'voce_spesa_form'=>$formOspiteVoceSpesa->createView(),
            'tipi_voci_spesa' =>$tipiVociSpesaJson
        );
    }


    /**
     * Edits an existing Voce spesa entity.
     *
     * @Route("/{id}/voce_spesa_update", name="voce_spesa_update")
     * @Method("PUT")
     */
    public function updateVoceSpesaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:VoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Voce spesa non trovata');
        }

        $editForm = $this->createVoceSpesaEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * Creates a form to edit a Conto entity.
     *
     * @param Conto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createVoceSpesaEditForm(VoceSpesa $entity)
    {
        $form = $this->createForm(new VoceSpesaType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('voce_spesa_update', array('id' => "-")),
            'method' => 'PUT',
        ));

        return $form;
    }


    /**
     * Deletes a Conto entity.
     *
     * @Route("/{id}/voce_spesa_delete", name="voce_spesa_delete")
     * @Method("GET")
     */
    public function deleteVoceSpesaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:VoceSpesa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find voce spesa entity.');
        }

        $em->remove($entity);
        $em->flush();

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
