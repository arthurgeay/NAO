<?php

namespace Omega\NAOBundle\Repository;

/**
 * ObservationsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationsRepository extends \Doctrine\ORM\EntityRepository
{
	public function getObservationParticulier()
	{
		$qb = $this->createQueryBuilder('o');
		 $qb
		 ->join('o.utilisateur', 'u')
		 ->where('u.roles = :roles')
		 ->setParameter('roles', 'a:1:{i:0;s:16:"ROLE_PARTICULIER";}')
		 ->andWhere('o.verifie = :verifie')
		 ->setParameter('verifie', false)
		 ->orderBy('o.date', 'DESC')
		 ;
		 
		 return $qb
		 	->getQuery()
		 	->getResult()
		 ;
	}
    public function RecupObservation ($espece)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.espece = :espece')
            ->setParameter('espece', $espece);

        return $qb  ->getQuery()
                    ->getResult();

    }
    public function countObservation ($espece)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.espece = :espece')
            ->setParameter('espece', $espece);

        return $qb  ->getQuery()
            ->getSingleScalarResult();

    }
}
