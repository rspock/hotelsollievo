<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VoceSpesaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoVoceSpesaRepository extends EntityRepository
{
    function findTipoCaparra(){
        return $this->findOneBy(array("descrizione"=>"Caparra prenotazione"));
    }

    function findTipoPrenotazione(){
        return $this->findOneBy(array("descrizione"=>"Prenotazione Letto"));
    }
}
