<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\DocumentoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"ricevuta" = "Ricevuta"})

 */
class Documento
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
     * @ORM\Column(name="data", type="datetime")
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="string", length=255, nullable=true)
     */
    private $mime;

    /**
     * @var integer
     *
     * @ORM\Column(name="dimensione", type="integer", nullable=true)
     */
    private $dimensione;

    /**
     * @var string
     *
     * @ORM\Column(name="percorso", type="string", length=1000)
     */
    private $percorso;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;


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
     * Set data
     *
     * @param \DateTime $data
     * @return Documento
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set mime
     *
     * @param string $mime
     * @return Documento
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string 
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set dimensione
     *
     * @param integer $dimensione
     * @return Documento
     */
    public function setDimensione($dimensione)
    {
        $this->dimensione = $dimensione;

        return $this;
    }

    /**
     * Get dimensione
     *
     * @return integer 
     */
    public function getDimensione()
    {
        return $this->dimensione;
    }

    /**
     * Set percorso
     *
     * @param string $percorso
     * @return Documento
     */
    public function setPercorso($percorso)
    {
        $this->percorso = $percorso;

        return $this;
    }

    /**
     * Get percorso
     *
     * @return string 
     */
    public function getPercorso()
    {
        return $this->percorso;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Documento
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
}
