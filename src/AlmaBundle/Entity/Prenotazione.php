<?php

namespace AlmaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AlmaBundle\Entity\VoceSpesa;
/**
 * Prenotazione
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\PrenotazioneRepository")
 */
class Prenotazione
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
     * @var \DateTime
     *
     * @ORM\Column(name="dataInizio", type="date")
     */
    private $dataInizio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataFine", type="date")
     */
    private $dataFine;

    /**
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="prenotazioni")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     **/
    private $persona;


    /**
     * @ORM\ManyToMany(targetEntity="Letto", inversedBy="prenotazioni")
     * @ORM\JoinTable(name="LettiPrenotazioni")
     **/
    private $letti;

    /**
     * @var VoceSpesa
     *
     * @ORM\OneToOne(targetEntity="VoceSpesa", inversedBy="prenotazione", cascade={"all"})
     * @ORM\JoinColumn(name="caparra_voce_spesa_id", referencedColumnName="id",nullable=true)
     **/
    private $caparra;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mezzaPensione", type="boolean")
     */
    private $mezzaPensione;

    function __construct(){
        $this->letti = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mezzaPensione = false;

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
     * Set dataInizio
     *
     * @param \DateTime $dataInizio
     * @return Prenotazione
     */
    public function setDataInizio($dataInizio)
    {
        $this->dataInizio = $dataInizio;

        return $this;
    }

    /**
     * Get dataInizio
     *
     * @return \DateTime 
     */
    public function getDataInizio()
    {
        return $this->dataInizio;
    }

    /**
     * Set dataFine
     *
     * @param \DateTime $dataFine
     * @return Prenotazione
     */
    public function setDataFine($dataFine)
    {
        $this->dataFine = $dataFine;

        return $this;
    }

    /**
     * Get dataFine
     *
     * @return \DateTime 
     */
    public function getDataFine()
    {
        return $this->dataFine;
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
     * @return ArrayCollection
     */
    public function getLetti()
    {
        return $this->letti;
    }

    /**
     * @param ArrayCollection $letti
     */
    public function setLetti($letti)
    {
        $this->letti = $letti;
    }

    /**
     * @return VoceSpesa
     */
    public function getCaparra()
    {
        return $this->caparra;
    }

    /**
     * @param VoceSpesa $caparra
     */
    public function setCaparra($caparra)
    {
        $this->caparra = $caparra;
    }

    /**
     * @return boolean
     */
    public function isMezzaPensione()
    {
        return $this->mezzaPensione;
    }

    /**
     * @param boolean $mezzaPensione
     */
    public function setMezzaPensione($mezzaPensione)
    {
        $this->mezzaPensione = $mezzaPensione;
    }



}
