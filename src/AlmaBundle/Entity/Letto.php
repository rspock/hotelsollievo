<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Letto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\LettoRepository")
 */
class Letto
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
     * @var boolean
     *
     * @ORM\Column(name="prenotato", type="boolean")
     */
    private $prenotato;

    /**
     * @var boolean
     *
     * @ORM\Column(name="occupato", type="boolean")
     */
    private $occupato;

    /**
     * @ORM\ManyToOne(targetEntity="Ospite", inversedBy="letti")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     **/
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Stanza", inversedBy="letti")
     * @ORM\JoinColumn(name="stanza_id", referencedColumnName="id")
     **/
    private $stanza;

    // ...
    /**
     * @ORM\ManyToMany(targetEntity="Prenotazione", mappedBy="letti")
     **/
    private $prenotazioni;

    public function __construct() {
        $this->prenotazioni = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prenotato = false;
        $this->occupato=false;
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
     * Set prenotato
     *
     * @param boolean $prenotato
     * @return Letto
     */
    public function setPrenotato($prenotato)
    {
        $this->prenotato = $prenotato;

        return $this;
    }

    /**
     * Get prenotato
     *
     * @return boolean 
     */
    public function getPrenotato()
    {
        return $this->prenotato;
    }

    /**
     * Set occupato
     *
     * @param boolean $occupato
     * @return Letto
     */
    public function setOccupato($occupato)
    {
        $this->occupato = $occupato;

        return $this;
    }

    /**
     * Get occupato
     *
     * @return boolean 
     */
    public function getOccupato()
    {
        return $this->occupato;
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
     * @return Stanza
     */
    public function getStanza()
    {
        return $this->stanza;
    }

    /**
     * @param Stanza $stanza
     */
    public function setStanza($stanza)
    {
        $this->stanza = $stanza;
    }

    /**
     * @return Prenotazione
     */
    public function getPrenotazioni()
    {
        return $this->prenotazioni;
    }

    /**
     * @param Prenotazione $prenotazioni
     */
    public function setPrenotazioni($prenotazioni)
    {
        $this->prenotazioni = $prenotazioni;
    }

    /**
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @param string $descrizione
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }
}
