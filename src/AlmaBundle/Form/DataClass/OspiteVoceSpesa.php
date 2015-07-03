<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 28/05/15
 * Time: 23:49
 */

namespace AlmaBundle\Form\DataClass;

use AlmaBundle\Entity\TipoVoceSpesa as TipoVoceSpesa;

class OspiteVoceSpesa {

    private $ospiteId;

    private $importo;

    private $tipoVoceSpesa;

    private $descrizione;

    private $sconto;

    /**
     * @return integer
     */
    public function getOspiteId()
    {
        return $this->ospiteId;
    }

    /**
     * @param integer $ospiteId
     */
    public function setOspiteId($ospiteId)
    {
        $this->ospiteId = $ospiteId;
    }

    /**
     * @return float
     */
    public function getImporto()
    {
        return $this->importo;
    }

    /**
     * @param mixed $importo
     */
    public function setImporto($importo)
    {
        $this->importo = $importo;
    }

    /**
     * @return mixed
     */
    public function getTipoVoceSpesa()
    {
        return $this->tipoVoceSpesa;
    }

    /**
     * @param mixed $tipoVoceSpesa
     */
    public function setTipoVoceSpesa($tipoVoceSpesa)
    {
        $this->tipoVoceSpesa = $tipoVoceSpesa;
    }

    /**
     * @return mixed
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @param mixed $descrizione
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    /**
     * @return mixed
     */
    public function getSconto()
    {
        return $this->sconto;
    }

    /**
     * @param mixed $sconto
     */
    public function setSconto($sconto)
    {
        $this->sconto = $sconto;
    }

}