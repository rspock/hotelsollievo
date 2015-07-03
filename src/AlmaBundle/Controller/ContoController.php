<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\Conto;
use AlmaBundle\Entity\TipoVoceSpesa;
use AlmaBundle\Entity\VoceSpesa;
use AlmaBundle\Form\ContoType;
use AlmaBundle\Form\DataClass\OspiteVoceSpesa;
use AlmaBundle\Form\VoceSpesaType;
use Doctrine\Common\Collections\ArrayCollection;
use Proxies\__CG__\AlmaBundle\Entity\Prenotazione;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

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
            $ultimoContoChiuso = $em->getRepository("AlmaBundle:Conto")->findUltimoChiuso($idPersona);
            $conto = new Conto();
            $conto->setPersona($persona);
            if($ultimoContoChiuso != null){
                $dataChiusuraUltimoConto = clone $ultimoContoChiuso->getDataPagamento();
                $dataChiusuraUltimoConto->add(new \DateInterval("P1D"));
                $dataChiusuraUltimoConto->setTime(0,0,0);
                $conto->setDataApertura($dataChiusuraUltimoConto);
            }else{
                $conto->setDataApertura(new \DateTime());
            }
            $conto->setStato("APERTO");
            $em->persist($conto);
            $em->flush();
            $this->addSucces("Conto aperto correttamento");
        }else{
            $this->addErrore("Esiste già un conto aperto");
        }
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
     * Finds and displays a Conto entity.
     *
     * @Route("/{id}/ricevuta", name="genera_ricevuta")
     * @Method("GET")
     * @Template()
     */
    public function ricevutaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        $this->get("generazione_documenti")->generaRicevutaConto($entity);
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
     * @Route("/{id}/controlla_chiusura", name="conto_controlla_chiusura")
     * @Method("GET")
     * @Template("AlmaBundle:Conto:vociSpesa.html.twig")
     */
    public function controllaChiusuraAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AlmaBundle:Conto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conto entity.');
        }

        if($entity->getStato() != "APERTO"){
            $referer = $request->headers->get('referer');
            $this->addErrore("Il conto indicato risulta non essere aperto");
            return new RedirectResponse($referer);
        }

        $vociSpesaPrenotazioni = $this->calcolaVociPrenotazione($entity);
        foreach($vociSpesaPrenotazioni as $vociSpesaPrenotazione){
            $entity->getVociSpesa()->add($vociSpesaPrenotazione);
        }

        $dati = $this->getDataVistaVociSpesa($entity);
        $dati["mostraChiusura"] = true;
        $dati["mostraSaldo"] = true;
        return $dati;

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
        $this->calcolaVociPrenotazione($entity,true);

        $entity->setDataPagamento(new \DateTime());
        $entity->setStato("CHIUSO");
        $em->persist($entity);

        $conto = new Conto();
        $conto->setStato("APERTO");
        $dataApertura = new \DateTime();
        $dataApertura->add(new \DateInterval("P1D"));
        $dataApertura->setTime(0,0,0);
        $conto->setDataApertura($dataApertura);
        $conto->setPersona($entity->getPersona());
        $em->persist($conto);

        $em->flush();

        $this->addSucces("Conto chiuso correttamente");
        return $this->redirect($this->generateUrl('ospite'));
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

        $this->calcolaVociPrenotazione($entity,true);

        $entity->setDataPagamento(new \DateTime());
        $entity->setStato("CHIUSO");
        $entity->setSaldo(true);
        $em->persist($entity);
        $em->flush();

        $this->addSucces("Conto chiuso correttamente");
        return $this->redirect($this->generateUrl('ospite'));
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

        $dati = $this->getDataVistaVociSpesa($entity);
        $dati["mostraChiusura"] = false;
        $dati["mostraSaldo"] = false;
        return $dati;
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


    /**
     * Calcola l'importo relativo alla prenotazione per il periodo del conto
     * @param Conto $conto
     * @return ArrayCollection
     */
    private function calcolaVociPrenotazione($conto, $persisti=false){
        $vociSpesaPrenotazioni = new ArrayCollection();
        $em = $this->getDoctrine()->getEntityManager();
        $dataApertuaConto = $conto->getDataApertura();
        $dataChiusuraConto = $conto->getDataPagamento()!=null ? $conto->getDataPagamento() : new \DateTime();
        //TODO da rimuovere
        //$dataFake = new \DateTime("2015-08-28 00:00:00");
        //$dataChiusuraConto = $conto->getDataPagamento()!=null ? $conto->getDataPagamento() : $dataFake;

        $prenotazioni = $em->getRepository("AlmaBundle:Prenotazione")->getPrenotazioniDaAPerPersona($dataApertuaConto,$dataChiusuraConto,$conto->getPersona()->getId());
        $tipoVoceSpesaPrenotazione = $em->getRepository("AlmaBundle:TipoVoceSpesa")->findTipoPrenotazione();
        foreach($prenotazioni as $prenotazione){
            //calcolo l'intervallo da considerare tra gli estremi del conto e quella della prenotazione
            $dataInizioContoPrenotazione = $this->getMaxDate($dataApertuaConto,$prenotazione->getDataInizio());
            $dataFineContoPrenotazione = $this->getMinDate($dataChiusuraConto,$prenotazione->getDataFine());
            //trovo tutte le possibili tariffe su questo intervallo e per il tipo di ogni letto della prenotazione
            foreach($prenotazione->getLetti() as $letto){
                $tipoStanzaId = $letto->getStanza()->getTipo()->getId();
                $tariffe = $em->getRepository("AlmaBundle:Tariffa")->getTariffaDaAPerTipoCamera($dataInizioContoPrenotazione,$dataFineContoPrenotazione,$tipoStanzaId);
                foreach($tariffe as $tariffa){
                    //trovo l'intervallo di date intersecando il periodo della tariffa con il periodo della prenotazione
                    $dataIntervalloInizio=$this->getMaxDate($dataInizioContoPrenotazione,$tariffa->getDataInizio());
                    $dataIntervalloFine = $this->getMinDate($dataFineContoPrenotazione,$tariffa->getDataFine());
                    //calcolo i giorni
                    $giorni = $dataIntervalloFine->diff($dataIntervalloInizio)->d;
                    $voceSpesa = new VoceSpesa();
                    $voceSpesa->setTipo($tipoVoceSpesaPrenotazione);
                    $voceSpesa->setConto($conto);
                    $voceSpesa->setDataRegistrazione(new \DateTime());
                    $importoGiornaliero=0;
                    if($prenotazione->isMezzaPensione()){
                        $importoGiornaliero= $tariffa->getImportoMezzaPensione();
                    }else{
                        $importoGiornaliero= $tariffa->getImporto();
                    }
                    $voceSpesa->setImporto($giorni*$importoGiornaliero);

                    $voceSpesa->setNote("Prenotazione: ".$prenotazione->getId()." tariffa: ".
                        $tariffa->getId()." importo giornaliero ".$importoGiornaliero." periodo da: ".$dataIntervalloInizio->format("d:m:Y")." periodo a ".$dataIntervalloFine->format("d:m:Y"));
                    $vociSpesaPrenotazioni->add($voceSpesa);
                    if($persisti){
                        $em->persist($voceSpesa);
                    }
                }
            }

        }
        if($persisti){
            $em->flush();
        }

        return $vociSpesaPrenotazioni;
    }

    private function getMinDate(\DateTime $d1, \DateTime $d2){
        return $d1>$d2 ? $d2 : $d1;
    }

    private function getMaxDate(\DateTime $d1, \DateTime $d2){
        return $d1>$d2 ? $d1 : $d2;
    }

    private function getDataVistaVociSpesa(Conto $conto){
        $em = $this->getDoctrine()->getEntityManager();

        $modificaVoceSpesaForm = $this->createVoceSpesaEditForm(new VoceSpesa());

        $formOspiteVoceSpesa = $this->createOspiteVoceSpesaForm(new OspiteVoceSpesa());

        $tipiVociSpesa = $em->getRepository('AlmaBundle:TipoVoceSpesa')->findAll();
        $serializer = $this->container->get('jms_serializer');
        $tipiVociSpesaJson = $serializer->serialize($tipiVociSpesa, 'json');


        return array(
            'conto' => $conto,
            'vociSpesa' => $conto->getVociSpesa(),
            'modifica_voce_spesa_form' => $modificaVoceSpesaForm->createView(),
            'voce_spesa_form'=>$formOspiteVoceSpesa->createView(),
            'tipi_voci_spesa' =>$tipiVociSpesaJson
        );
    }
}
