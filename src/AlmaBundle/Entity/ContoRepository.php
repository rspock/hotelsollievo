<?php

namespace AlmaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContoRepository extends EntityRepository
{

    public function findAttivoDaOspite($ospiteId){
        $em = $this->getEntityManager();

        $dql = "SELECT c FROM AlmaBundle:Conto c
                JOIN c.persona p
                WHERE p.id = :ospiteId AND c.stato=:statoAttivo";

        $query = $em->createQuery($dql);
        $query->setParameter("ospiteId",$ospiteId);
        $query->setParameter("statoAttivo","APERTO");

        return $query->getOneOrNullResult();
    }

    public function findByPersona($ospiteId){
        $em = $this->getEntityManager();

        $dql = "SELECT c FROM AlmaBundle:Conto c
                JOIN c.persona p
                WHERE p.id = :ospiteId
                ORDER BY c.dataApertura DESC";

        $query = $em->createQuery($dql);
        $query->setParameter("ospiteId",$ospiteId);

        return $query->getResult();
    }

    public function findUltimoChiuso($ospiteId){
        $em = $this->getEntityManager();

        $dql = "SELECT c FROM AlmaBundle:Conto c
                JOIN c.persona p
                WHERE p.id = :ospiteId
                AND c.stato = :statoChiuso
                ORDER BY c.dataPagamento DESC";

        $query = $em->createQuery($dql);
        $query->setParameter("ospiteId",$ospiteId);
        $query->setParameter("statoChiuso","CHIUSO");
        $query->setMaxResults(1);

        return $query->getOneOrNullResult();
    }
}
