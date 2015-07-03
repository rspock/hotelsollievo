<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Stanza
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\StanzaRepository")
 */
class Stanza
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
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="Letto", mappedBy="stanza")
     **/
    private $letti;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCamera")
     * @ORM\JoinColumn(name="tipo_camera_id", referencedColumnName="id")
     **/
    private $tipo;

    function __construct()
    {
        $this->letti = new ArrayCollection();
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
     * Set numero
     *
     * @param string $numero
     * @return Stanza
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return mixed
     */
    public function getLetti()
    {
        return $this->letti;
    }

    /**
     * @param mixed $letti
     */
    public function setLetti($letti)
    {
        $this->letti = $letti;
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
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


}
