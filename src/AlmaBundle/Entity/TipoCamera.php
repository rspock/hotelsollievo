<?php

namespace AlmaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoCamera
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoCamera
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
     * @var integer
     *
     * @ORM\Column(name="letti", type="integer")
     */
    private $letti;

    /**
     * @var boolean
     *
     * @ORM\Column(name="attivo", type="boolean")
     */
    private $attivo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity="Tariffa", mappedBy="tipoCamera")
     **/
    private $tariffe;

    function __construct()
    {
        $this->tariffe = new ArrayCollection();
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
     * Set letti
     *
     * @param integer $letti
     * @return TipoCamera
     */
    public function setLetti($letti)
    {
        $this->letti = $letti;

        return $this;
    }

    /**
     * Get letti
     *
     * @return integer 
     */
    public function getLetti()
    {
        return $this->letti;
    }

    /**
     * Set attivo
     *
     * @param boolean $attivo
     * @return TipoCamera
     */
    public function setAttivo($attivo)
    {
        $this->attivo = $attivo;

        return $this;
    }

    /**
     * Get attivo
     *
     * @return boolean 
     */
    public function getAttivo()
    {
        return $this->attivo;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return TipoCamera
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
     * Set note
     *
     * @param string $note
     * @return TipoCamera
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }
}
