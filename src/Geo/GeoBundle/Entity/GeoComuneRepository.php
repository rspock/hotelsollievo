<?php

namespace Geo\GeoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GeoComuneRepository extends GeoRepository {

    protected function getGeoTable() {
        return 'GeoComune';
    }

    public function findByCodiceIstat($codice_istat) {
        return $this->findOneBy(array('codice_completo' => "11101" . $codice_istat));
    }

    public function comuniList($id_provincia, $stato_cessazione = null, $stato_cessione_legge_1989 = null) {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.provincia = :provincia_id')
                ->setParameter('provincia_id', $id_provincia);

        if ($stato_cessazione == "cessati") {
            $qb->andWhere('c.cessato = :stato_cessazione')
                    ->setParameter('stato_cessazione', 1);
        } elseif ($stato_cessazione == "non-cessati") {
            $qb->andWhere('c.cessato = :stato_cessazione')
                    ->setParameter('stato_cessazione', 0);
        }

        if ($stato_cessione_legge_1989 == "ceduti") {
            $qb->andWhere('c.ceduto_legge_1989 = :stato_cessione_legge_1989')
                    ->setParameter('stato_cessione_legge_1989', 1);
        } elseif ($stato_cessione_legge_1989 == "non-ceduti") {
            $qb->andWhere('c.ceduto_legge_1989 = :stato_cessione_legge_1989')
                    ->setParameter('stato_cessione_legge_1989', 0);
        }

        $qb->orderBy('c.denominazione', 'ASC');
        return $qb->getQuery()->getResult();
    }

}

?>
