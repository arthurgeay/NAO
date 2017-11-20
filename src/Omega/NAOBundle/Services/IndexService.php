<?php

namespace Omega\NAOBundle\Services;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class IndexService
{
    private $em;
    private $security;

    public function __construct(EntityManager $em, AuthorizationChecker $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function verifSecurity()
    {
        $nbCompte = null;
        $nbObs = null;


        if($this->security->isGranted('ROLE_ADMIN'))
        {
            $repository = $this->em->getRepository('OmegaNAOBundle:Utilisateurs');
            $nbCompte = $repository->countCompte(); 
        }

        if($this->security->isGranted('ROLE_NATURALISTE') OR $this->security->isGranted('ROLE_ADMIN'))
        {
            $repository = $this->em->getRepository('OmegaNAOBundle:Observations');
            $nbObs = $repository->countObsNotVerifie();
        }

        $result = array('nbCompte' => $nbCompte, 'nbObs' => $nbObs);

        return $result;
    }
}