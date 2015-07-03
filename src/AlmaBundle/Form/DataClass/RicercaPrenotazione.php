<?php
/**
 * Created by PhpStorm.
 * User: rspock
 * Date: 28/05/15
 * Time: 23:49
 */

namespace AlmaBundle\Form\DataClass;

use AlmaBundle\Entity\TipoCamera;

class RicercaPrenotazione
{

    private $dataArrivo;
    private $dataPartenza;
    private $numeroStanza;
    private $tipo;
    private $periodo;

    /**
     * @return \DateTime
     */
    public function getDataArrivo()
    {
        return $this->dataArrivo;
    }

    /**
     * @param \DateTime $dataArrivo
     */
    public function setDataArrivo(\DateTime $dataArrivo)
    {
        $this->dataArrivo = $dataArrivo;
    }

    /**
     * @return \DateTime
     */
    public function getDataPartenza()
    {
        return $this->dataPartenza;
    }

    /**
     * @param \DateTime $dataPartenza
     */
    public function setDataPartenza(\DateTime $dataPartenza)
    {
        $this->dataPartenza = $dataPartenza;
    }

    /**
     * @return integer
     */
    public function getNumeroStanza()
    {
        return $this->numeroStanza;
    }

    /**
     * @param integer $numeroStanza
     */
    public function setNumeroStanza($numeroStanza)
    {
        $this->numeroStanza = $numeroStanza;
    }

    /**
     * @return TipoCamera
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param TipoCamera $tipo
     */
    public function setTipo(TipoCamera $tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param mixed $periodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }




}