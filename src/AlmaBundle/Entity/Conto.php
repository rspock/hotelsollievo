<?php

namespace AlmaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Conto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\ContoRepository")
 */
class Conto
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
     * @ORM\Column(name="stato", type="string", length=255)
     */
    private $stato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataApertura", type="datetime")
     */
    private $dataApertura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataPagamento", type="datetime", nullable=true)
     */
    private $dataPagamento;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saldo", type="boolean", nullable=true)
     */
    private $saldo;

    /**
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="conti")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     **/
    private $persona;

    /**
     * @ORM\OneToMany(targetEntity="VoceSpesa", mappedBy="conto", orphanRemoval=true)
     **/
    private $vociSpesa;

    function __construct()
    {
        $this->vociSpesa = new ArrayCollection();
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
     * Set stato
     *
     * @param string $stato
     * @return Conto
     */
    public function setStato($stato)
    {
        $this->stato = $stato;

        return $this;
    }

    /**
     * Get stato
     *
     * @return string 
     */
    public function getStato()
    {
        return $this->stato;
    }

    /**
     * Set dataApertura
     *
     * @param \DateTime $dataApertura
     * @return Conto
     */
    public function setDataApertura($dataApertura)
    {
        $this->dataApertura = $dataApertura;

        return $this;
    }

    /**
     * Get dataApertura
     *
     * @return \DateTime 
     */
    public function getDataApertura()
    {
        return $this->dataApertura;
    }

    /**
     * Set dataPagamento
     *
     * @param \DateTime $dataPagamento
     * @return Conto
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;

        return $this;
    }

    /**
     * Get dataPagamento
     *
     * @return \DateTime 
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * Set saldo
     *
     * @param boolean $saldo
     * @return Conto
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return boolean 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @return Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param Persona $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @return VoceSpesa
     */
    public function getVociSpesa()
    {
        return $this->vociSpesa;
    }

    /**
     * @param VoceSpesa $vociSpesa
     */
    public function setVociSpesa($vociSpesa)
    {
        $this->vociSpesa = $vociSpesa;
    }


    public function getTotale(){
        $totale = 0;
        foreach($this->vociSpesa as $voceSpesa){
            $totale += is_null($voceSpesa->getImporto()) ? 0 : $voceSpesa->getImporto();
        }
        return $totale;
    }


}
