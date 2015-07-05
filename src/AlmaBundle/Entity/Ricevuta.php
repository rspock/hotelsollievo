<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ricevuta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\RicevutaRepository")
 */
class Ricevuta extends Documento
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
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var Conto
     *
     * @ORM\OneToOne(targetEntity="Conto", mappedBy="ricevuta")
     **/
    private $conto;


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
     * @param integer $numero
     * @return Ricevuta
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return Conto
     */
    public function getConto()
    {
        return $this->conto;
    }

    /**
     * @param Conto $conto
     */
    public function setConto($conto)
    {
        $this->conto = $conto;
    }


}
