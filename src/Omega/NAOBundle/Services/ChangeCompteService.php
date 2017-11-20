<?php 

namespace Omega\NAOBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ChangeCompteService
{
	private $em;
	private $session;

	public function __construct(EntityManager $em, Session $session)
	{
		$this->em = $em;
		$this->session = $session;
	}


	public function change($id)
	{
		$repository = $this->em->getRepository('OmegaNAOBundle:Utilisateurs');
        $utilisateur = $repository->find($id);

        $utilisateur->setCompte('naturaliste');

        $this->em->persist($utilisateur);
        $this->em->flush();

        $this->session->getFlashBag()->add('infoCompte', "Votre demande de changement de type de compte a été pris en compte. Votre recevrez très prochainement une réponse concernant votre demande. ");
	}
}