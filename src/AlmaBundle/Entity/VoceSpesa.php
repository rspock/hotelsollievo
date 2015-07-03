<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoceSpesa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\VoceSpesaRepository")
 */
class VoceSpesa
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
     * @ORM\Column(name="dataRegistrazione", type="datetimetz")
     */
    private $dataRegistrazione;


    /**
     * @ORM\ManyToOne(targetEntity="Conto", inversedBy="vociSpesa")
     * @ORM\JoinColumn(name="conto_id", referencedColumnName="id")
     **/
    private $conto;


    /**
     * @ORM\ManyToOne(targetEntity="TipoVoceSpesa")
     * @ORM\JoinColumn(name="tipovocespesa_id", referencedColumnName="id")
     **/
    private $tipo;

    /**
     * @var \float
     *
     * @ORM\Column(name="importo", type="float")
     */
    private $importo;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text",nullable=true)
     */
    private $note;


    /**
     * @ORM\OneToOne(targetEntity="Prenotazione", mappedBy="caparra")
     **/
    private $prenotazione;


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
     * Set dataRegistrazione
     *
     * @param \DateTime $dataRegistrazione
     * @return VoceSpesa
     */
    public function setDataRegistrazione($dataRegistrazione)
    {
        $this->dataRegistrazione = $dataRegistrazione;

        return $this;
    }

    /**
     * Get dataRegistrazione
     *
     * @return \DateTime
     */
    public function getDataRegistrazione()
    {
        return $this->dataRegistrazione;
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

    /**
     * @return TipoVoceSpesa
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param TipoVoceSpesa $tipo
     */
    public function setTipo($tipo)
    {
        if($tipo->isNegativa()){
            $this->importo = $this->importo < 0 ? $this->importo: -1 * $this->importo;
        }
        $this->tipo = $tipo;
    }

    /**
     * @return float
     */
    public function getImporto()
    {
        return $this->importo;
    }
    /**
     * @param float $importo
     */
    public function setImporto($importo)
    {
        if($this->tipo!=null && $this->tipo->isNegativa()){
            $this->importo = $importo < 0 ? $importo : -1 * $importo;
        }else{
            $this->importo = $importo;
        }
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
     * @return mixed
     */
    public function getPrenotazione()
    {
        return $this->prenotazione;
    }

    /**
     * @param mixed $prenotazione
     */
    public function setPrenotazione($prenotazione)
    {
        $this->prenotazione = $prenotazione;
    }

}
