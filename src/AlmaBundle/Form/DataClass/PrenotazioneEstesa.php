<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 01/07/15
 * Time: 23:37
 */

namespace AlmaBundle\Form\DataClass;

use AlmaBundle\Entity\Prenotazione;

class PrenotazioneEstesa extends Prenotazione{

    private $importoCaparra;

    /**
     * @return mixed
     */
    public function getImportoCaparra()
    {
        return $this->importoCaparra;
    }

    /**
     * @param mixed $importoCaparra
     */
    public function setImportoCaparra($importoCaparra)
    {
        $this->importoCaparra = $importoCaparra;
    }


    public function getPrenotazione(){
        $prenotazione = new Prenotazione();
        $prenotazione->setLetti($this->getLetti());
        $prenotazione->setDataFine($this->getDataFine());
        $prenotazione->setDataInizio($this->getDataInizio());
        $prenotazione->setPersona($this->getPersona());
        $prenotazione->setCaparra($this->getCaparra());
        return $prenotazione;
    }


}