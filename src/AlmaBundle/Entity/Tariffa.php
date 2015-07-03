<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tariffe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\TariffaRepository")
 */
class Tariffa
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
     * @var TipoCamera
     *
     * @ORM\ManyToOne(targetEntity="TipoCamera")
     * @ORM\JoinColumn(name="tipo_camera_id", referencedColumnName="id")
     **/
    private $tipoCamera;


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
     * @return Tariffe
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
     * @return Tariffe
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
     * Set tipoStanza
     *
     * @param TipoCamera $tipoCamera
     */
    public function setTipoCamera($tipoCamera)
    {
        $this->tipoCamera = $tipoCamera;
    }

    /**
     * Get tipoStanza
     *
     * @return TipoCamera
     */
    public function getTipoCamera()
    {
        return $this->tipoCamera;
    }
}
