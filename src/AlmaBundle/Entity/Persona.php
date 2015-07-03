<?php

namespace AlmaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ospite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\PersonaRepository")
 */

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"persona" = "Ospite", "ospite" = "Ospite"})
 */
class Persona
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
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cognome", type="string", length=255)
     */
    private $cognome;

    /**
     * @var string
     *
     * @ORM\Column(name="codiceFiscale", type="string", length=20, unique=true)
     */
    private $codiceFiscale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataNascita", type="date",nullable=true)
     */
    private $dataNascita;

    /**
     * @var string
     *
     * @ORM\Column(name="indirizzoVia", type="string", length=255,nullable=true)
     */
    private $indirizzoVia;

    /**
     * @var string
     *
     * @ORM\Column(name="indirizzoCivico", type="string", length=255,nullable=true)
     */
    private $indirizzoCivico;

    /**
     * @var string
     *
     * @ORM\Column(name="indirizzoCap", type="string", length=255,nullable=true)
     */
    private $indirizzoCap;

    /**
     * @var string
     *
     * @ORM\Column(name="indirizzoCitta", type="string", length=255,nullable=true)
     */
    private $indirizzoCitta;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255,nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="documento", type="string", length=255,nullable=true)
     */
    private $numeroDocumento;


    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text",nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity="Conto", mappedBy="persona")
     **/
    private $conti;

    /**
     * @ORM\OneToMany(targetEntity="Letto", mappedBy="persona")
     **/
    private $letti;

    /**
     * @ORM\OneToMany(targetEntity="Prenotazione", mappedBy="persona")
     **/
    private $prenotazioni;


    function __construct()
    {
        $this->conti = new ArrayCollection();
        $this->letti = new ArrayCollection();
        $this->prenotazioni = new ArrayCollection();
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
     * Set nome
     *
     * @param string $nome
     * @return Persona
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set cognome
     *
     * @param string $cognome
     * @return Persona
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;

        return $this;
    }

    /**
     * Get cognome
     *
     * @return string 
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * Set codiceFiscale
     *
     * @param string $codiceFiscale
     * @return Persona
     */
    public function setCodiceFiscale($codiceFiscale)
    {
        $this->codiceFiscale = $codiceFiscale;

        return $this;
    }

    /**
     * Get codiceFiscale
     *
     * @return string 
     */
    public function getCodiceFiscale()
    {
        return $this->codiceFiscale;
    }

    /**
     * Set dataNascita
     *
     * @param \DateTime $dataNascita
     * @return Persona
     */
    public function setDataNascita($dataNascita)
    {
        $this->dataNascita = $dataNascita;

        return $this;
    }

    /**
     * Get dataNascita
     *
     * @return \DateTime 
     */
    public function getDataNascita()
    {
        return $this->dataNascita;
    }

    /**
     * Set indirizzoVia
     *
     * @param string $indirizzoVia
     * @return Persona
     */
    public function setIndirizzoVia($indirizzoVia)
    {
        $this->indirizzoVia = $indirizzoVia;

        return $this;
    }

    /**
     * Get indirizzoVia
     *
     * @return string 
     */
    public function getIndirizzoVia()
    {
        return $this->indirizzoVia;
    }

    /**
     * Set indirizzoCivico
     *
     * @param string $indirizzoCivico
     * @return Persona
     */
    public function setIndirizzoCivico($indirizzoCivico)
    {
        $this->indirizzoCivico = $indirizzoCivico;

        return $this;
    }

    /**
     * Get indirizzoCivico
     *
     * @return string 
     */
    public function getIndirizzoCivico()
    {
        return $this->indirizzoCivico;
    }

    /**
     * Set indirizzoCap
     *
     * @param string $indirizzoCap
     * @return Persona
     */
    public function setIndirizzoCap($indirizzoCap)
    {
        $this->indirizzoCap = $indirizzoCap;

        return $this;
    }

    /**
     * Get indirizzoCap
     *
     * @return string 
     */
    public function getIndirizzoCap()
    {
        return $this->indirizzoCap;
    }

    /**
     * Set indirizzoCitta
     *
     * @param string $indirizzoCitta
     * @return Persona
     */
    public function setIndirizzoCitta($indirizzoCitta)
    {
        $this->indirizzoCitta = $indirizzoCitta;

        return $this;
    }

    /**
     * Get indirizzoCitta
     *
     * @return string 
     */
    public function getIndirizzoCitta()
    {
        return $this->indirizzoCitta;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Persona
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @return string
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @param string $numeroDocumento
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;
    }


    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return ArrayCollection
     */
    public function getConti()
    {
        return $this->conti;
    }

    /**
     * @param ArrayCollection $conti
     */
    public function setConti($conti)
    {
        $this->conti = $conti;
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
     * @return ArrayCollection
     */
    public function getPrenotazioni()
    {
        return $this->prenotazioni;
    }

    /**
     * @param ArrayCollection $prenotazioni
     */
    public function setPrenotazioni($prenotazioni)
    {
        $this->prenotazioni = $prenotazioni;
    }



    /**
     * @return string
     */
    public function getNomeCognome(){
        return $this->nome . " " . $this->cognome;
    }

    public function hasContoAperto(){
        foreach($this->conti as $conto){
            if($conto->getStato() == "APERTO"){
                return true;
            }
        }
        return false;
    }
}
