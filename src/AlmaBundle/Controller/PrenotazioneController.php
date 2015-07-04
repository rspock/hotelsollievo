<?php

namespace AlmaBundle\Controller;

use AlmaBundle\Entity\Prenotazione;
use AlmaBundle\Entity\VoceSpesa;
use AlmaBundle\Form\DataClass\PrenotazioneEstesa;
use AlmaBundle\Form\DataClass\RicercaPrenotazione;
use AlmaBundle\Form\PrenotazioneEstesaType;
use AlmaBundle\Form\PrenotazioneType;
use AlmaBundle\Form\RicercaPrenotazioneType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Prenotazioni controller.
 *
 * @Route("/prenotazione")
 */
class PrenotazioneController extends BaseController
{

    /**
     * Lists all Prenotazioni entities.
     *
     * @Route("/", name="prenotazione")
     * @Route("/paginazione/{paginazione}", name="prenotazione_paginata")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request, $paginazione = null)
    {
        $em = $this->getDoctrine()->getManager();

        $stanze = $em->getRepository('AlmaBundle:Stanza')->findAll();

        $ricercaPrenotazione = new RicercaPrenotazione();
        if ($request->isMethod ( 'POST' )) {
            $form = $this->createCreateForm($ricercaPrenotazione);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $request->getSession()->set("ricerca_prenotazioni", $ricercaPrenotazione);
            }
        }else{
            $ricercaPrenotazione = $request->getSession()->get("ricerca_prenotazioni");
            $ricercaPrenotazione = is_null($ricercaPrenotazione) ? new RicercaPrenotazione():$ricercaPrenotazione;
            $form = $this->createCreateForm($ricercaPrenotazione);
        }


        $visualizzazione = array();
        $intervallo=null;
        $adesso = new \DateTime();

        $dataArrivo = $ricercaPrenotazione->getDataArrivo() != null ? $ricercaPrenotazione->getDataArrivo():new \DateTime();
        $intervalloOffsetPaginazione = new \DateInterval('P7D');
        if($ricercaPrenotazione->getPeriodo()=="mesi"){
            $intervalloOffsetPaginazione = new \DateInterval('P1M');
        }
        if($paginazione!=null && $paginazione==true){
            $dataArrivo->add($intervalloOffsetPaginazione);

        }elseif($paginazione!=null && $paginazione==false){
            $dataArrivo->sub($intervalloOffsetPaginazione);
        }
        if($paginazione!=null){
            $ricercaPrenotazione->setDataArrivo(clone $dataArrivo);
            $request->getSession()->set("ricerca_prenotazioni", $ricercaPrenotazione);
        }
        $dataArrivo->sub(new \DateInterval('P7D'));//offset iniziale per centrare oggi

        if($ricercaPrenotazione->getDataArrivo()!=null && $ricercaPrenotazione->getDataPartenza() !=null){
            $intervallo = $ricercaPrenotazione->getDataPartenza()->diff($ricercaPrenotazione->getDataArrivo());
            if($intervallo->m>1){
                $visualizzazione[0]["label"]=$dataArrivo->format("M");
                $visualizzazione[0]["css"] = "";
                $visualizzazione[0]["dataRiferimento"] = $dataArrivo;
                for($i=1;$i<$intervallo->m;$i++){
                    $visualizzazione[$i+1]["label"]=$dataArrivo->add(new \DateInterval("P1M"))->format("M");
                    $visualizzazione[$i+1]["css"] = "";
                    $visualizzazione[$i]["dataRiferimento"] = clone $dataArrivo;
                }
                $last = count($visualizzazione);
                $visualizzazione[$last]["label"]=$dataArrivo->format("M");
                $visualizzazione[$last]["css"] = "";
                $visualizzazione[$last]["dataRiferimento"] = clone $dataArrivo->add(new \DateInterval("P1M"));
                $modalita="Mesi";
                $ricercaPrenotazione->setPeriodo("mesi");
                $request->getSession()->set("ricerca_prenotazioni", $ricercaPrenotazione);

            }elseif ($intervallo->days > 28){
                $visualizzazione[0]["label"]=$dataArrivo->format("W");
                $visualizzazione[0]["css"] = "";
                $visualizzazione[0]["dataRiferimento"] = clone $dataArrivo;
                for($i=1;$i< \ceil($intervallo->days/7);$i++){
                    $visualizzazione[$i]["label"]=$dataArrivo->add(new \DateInterval("P7D"))->format("W");
                    $visualizzazione[$i]["css"] = "";
                    $visualizzazione[$i]["dataRiferimento"] = clone $dataArrivo;
                }
                $last = count($visualizzazione);
                $visualizzazione[$last]["label"]=$dataArrivo->format("W");
                $visualizzazione[$last]["css"] = "";
                $visualizzazione[$last]["dataRiferimento"] = clone $dataArrivo->add(new \DateInterval("P7D"));
                $modalita="Settimane N.";
                $ricercaPrenotazione->setPeriodo("settimane");
                $request->getSession()->set("ricerca_prenotazioni", $ricercaPrenotazione);
            }
        }
        if(count($visualizzazione)==0){
            $numeroGiorni = $intervallo != null ? $intervallo->days : 28;
            $visualizzazione[0]["label"]=$dataArrivo->format("d");
            $visualizzazione[0]["css"] = "";
            $visualizzazione[0]["dataRiferimento"] = clone $dataArrivo;
            for($i=1;$i<$numeroGiorni;$i++){
                $visualizzazione[$i]["label"]=$dataArrivo->add(new \DateInterval("P1D"))->format("d");
                if($dataArrivo->format("N") == 7 || $dataArrivo->format("N") == 6){
                    $visualizzazione[$i]["css"] = "festivo";
                }else{
                    $visualizzazione[$i]["css"] = "";
                }
                if($dataArrivo->format("d") == $adesso->format("d")){
                    $visualizzazione[$i]["css"] = "oggi";
                }
                $visualizzazione[$i]["dataRiferimento"] = clone $dataArrivo;
            }
            $last = count($visualizzazione);
            $visualizzazione[$last]["label"]=$dataArrivo->format("M");
            $visualizzazione[$last]["css"] = "";
            $visualizzazione[$last]["dataRiferimento"] = clone $dataArrivo->add(new \DateInterval("P1D"));
            $modalita="Giorni";
            $ricercaPrenotazione->setPeriodo("giorni");
            $request->getSession()->set("ricerca_prenotazioni", $ricercaPrenotazione);
        }

        $lettoRepository = $em->getRepository('AlmaBundle:Letto');

        $visualizzazioneLetti = array();
        foreach($stanze as $stanza){
            foreach($stanza->getLetti() as $letto){
                for($i=1; $i<count($visualizzazione);$i++){
                    $lettoId = $letto->getId();
                    $da = $visualizzazione[$i-1]["dataRiferimento"];
                    $a = $visualizzazione[$i]["dataRiferimento"];
                    $prenotazione = $lettoRepository->getPrenotazioneLetto($lettoId,$da,$a);
                    $visualizzazioneLetti[$lettoId][$i-1]=$prenotazione;
                }
            }
        }
        $formPrenotazione = $this->createForm(new PrenotazioneEstesaType(), new PrenotazioneEstesa(), array(
            'action' => $this->generateUrl('nuova_prenotazione'),
            'method' => 'POST'));
        return array(
            'stanze' => $stanze,
            'form_ricerca_prenotazione' => $form->createView(),
            "visualizzazione" => $visualizzazione,
            "visualizzazioneLetti" => $visualizzazioneLetti,
            "modalita"=>$modalita,
            "formPrenotazione" => $formPrenotazione->createView()
        );
    }


    /**
     * Lists all Prenotazioni entities.
     *
     * @Route("/cancella_ricerca", name="cancella_ricerca")
     * @Method({"GET"})
     * @Template()
     */
    public function cancellaRicercaAction(Request $request)
    {
        $request->getSession()->set("ricerca_prenotazioni",null);
        return new RedirectResponse($this->generateUrl("prenotazione"));
    }

    /**
     * Lists all Prenotazioni entities.
     *
     * @Route("/nuova", name="nuova_prenotazione")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request){
        $entity = new PrenotazioneEstesa();
        $form = $this->createForm(new PrenotazioneEstesaType(), $entity, array(
            'action' => $this->generateUrl('nuova_prenotazione'),
            'method' => 'POST'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //controllo che non ci siano prenotazioni per la stessa data
            foreach($entity->getLetti() as $letto){
                if($em->getRepository('AlmaBundle:Letto')->getOccupazioneLetto($letto->getId(),$entity->getDataInizio(),$entity->getDataFine())){
                    $this->addErrore("Il letto ".$letto->getDescrizione()." risulta occupato per il periodo indicato");
                    return new RedirectResponse($this->generateUrl("prenotazione"));
                }
            }
            if(!is_null($entity->getImportoCaparra())){
                //aggiungo una voce spesa negativa dell 'importo della caparra
                $conto = $em->getRepository('AlmaBundle:Conto')->findAttivoDaOspite($entity->getPersona()->getId());
                if(is_null($conto)){
                    $this->addErrore("Nessun conto attivo per l'ospite indicato");
                    return new RedirectResponse($this->generateUrl("prenotazione"));
                }
                $voceSpesaCaparra = new VoceSpesa();
                $voceSpesaCaparra->setConto($conto);
                $voceSpesaCaparra->setDataRegistrazione(new \DateTime());
                $voceSpesaCaparra->setTipo($em->getRepository('AlmaBundle:TipoVoceSpesa')->findTipoCaparra());
                $voceSpesaCaparra->setImporto($entity->getImportoCaparra());
                $entity->setCaparra($voceSpesaCaparra);
            }
            $em->persist($entity->getPrenotazione());
            $em->flush();

            $this->addSucces("Prenotazione registrata con successo");
            return $this->redirect($this->generateUrl('prenotazione'));
        }
        $this->addErrore("Prenotazione non registrata");
        return new RedirectResponse($this->generateUrl("prenotazione"));
    }

    /**
     * Creates a form to create a RicercaPrenotazione entity.
     *
     * @param RicercaPrenotazione $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RicercaPrenotazione $entity)
    {
        $form = $this->createForm(new RicercaPrenotazioneType(), $entity, array(
            'action' => $this->generateUrl('prenotazione'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to edit an existing Ospite entity.
     *
     * @Route("/{id}/edit", name="prenotazione_edit")
     * @Method("GET")
     * @Template("AlmaBundle:Prenotazione:edit.html.twig")
     */
    public function editAction($id){
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Prenotazione')->find($id);

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
     * Edits an existing Ospite entity.
     *
     * @Route("/{id}", name="prenotazione_update")
     * @Method("PUT")
     * @Template("AlmaBundle:Prenotazione:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AlmaBundle:Prenotazione')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospite entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            //controllo che non ci siano prenotazioni per la stessa data
            foreach($entity->getLetti() as $letto){
                if($em->getRepository('AlmaBundle:Letto')->getOccupazioneLetto($letto->getId(),
                        $entity->getDataInizio(),$entity->getDataFine(),$entity->getId())){

                    $this->addErrore("Il letto ".$letto->getDescrizione()." risulta occupato per il periodo indicato");
                    return new RedirectResponse($this->generateUrl("prenotazione"));
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('prenotazione'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Creates a form to edit a Ospite entity.
     *
     * @param Prenotazione $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Prenotazione $entity)
    {
        $form = $this->createForm(new PrenotazioneType(), $entity, array(
            'action' => $this->generateUrl('prenotazione_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifica'));

        return $form;
    }
}
