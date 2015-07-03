<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ospite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AlmaBundle\Entity\OspiteRepository")
 */
class Ospite extends  Persona
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return parent::getId();
    }
}
