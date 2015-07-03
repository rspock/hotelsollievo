<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoVoceSpesa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\TipoVoceSpesaRepository")
 */
class TipoVoceSpesa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="string", length=255)
     */
    private $descrizione;

    /**
     * @var float
     *
     * @ORM\Column(name="prezzoStandard", type="float",nullable=true)
     */
    private $prezzoStandard;

    /**
     * @var boolean
     *
     * @ORM\Column(name="negativa", type="boolean")
     */
    private $negativa;

    /**
     * @var boolean
     *
     * @ORM\Column(name="eliminabile", type="boolean")
     */
    private $eliminabile;

    function __construct()
    {
        $this->negativa = false;
        $this->eliminabile=true;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     * @return TipoVoceSpesa
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    /**
     * Get descrizione
     *
     * @return string 
     */
    public function getDescrizione()
    {

        return $this->descrizione . ($this->negativa ? " - Sconto" : "");
    }

    /**
     * Set prezzoStandard
     *
     * @param float $prezzoStandard
     * @return TipoVoceSpesa
     */
    public function setPrezzoStandard($prezzoStandard)
    {
        $this->prezzoStandard = $prezzoStandard;

        return $this;
    }

    /**
     * Get prezzoStandard
     *
     * @return float 
     */
    public function getPrezzoStandard()
    {
        return $this->prezzoStandard;
    }

    /**
     * @return boolean
     */
    public function isNegativa()
    {
        return $this->negativa;
    }

    /**
     * @param boolean $negativa
     */
    public function setNegativa($negativa)
    {
        $this->negativa = $negativa;
    }

    /**
     * @return boolean
     */
    public function isEliminabile()
    {
        return $this->eliminabile;
    }

    /**
     * @param boolean $eliminabile
     */
    public function setEliminabile($eliminabile)
    {
        $this->eliminabile = $eliminabile;
    }



}
