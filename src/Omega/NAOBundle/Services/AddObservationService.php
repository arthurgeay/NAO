<?php

namespace Omega\NAOBundle\Services;
use Omega\NAOBundle\Entity\Observations;
use Omega\NAOBundle\Services\UploadedPhotosService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class AddObservationService
{
	private $upload;
	private $tokenStorage;
	private $em;
	private $session;

	public function __construct(
			UploadedPhotosService $uploadService, 
			TokenStorageInterface $tokenStorage,
			EntityManager $em, Session $session)
	{
		$this->uploadService = $uploadService;
		$this->tokenStorage = $tokenStorage;
		$this->em = $em;
		$this->session = $session;
	}

	public function persistData(Observations $observation)
	{
		$this->uploadService->upload($observation); // service d'upload d'image

        $user = $this->tokenStorage->getToken()->getUser(); // On récupère le user courant et on le relie à l'observation
        $observation->setUtilisateur($user);

        $roles = $user->getRoles(); // Si le user est naturaliste
        if($roles == array('ROLE_NATURALISTE') OR $roles == array('ROLE_ADMIN'))
        {
            $observation->setVerifie(true); //On valide directement son observation
        }
    		
    	$this->em->persist($observation);
    	$this->em->flush();

        if($roles == array('ROLE_PARTICULIER'))
        {
            $this->session->getFlashBag()->add('success', 'Votre observation a bien été prise en compte, et est désormais en attente de modération.');
        }
        else if($roles = array('ROLE_NATURALISTE'))
        {
            $this->session->getFlashBag()->add('success', 'Votre observation a bien été ajoutée');
        }
	}

	
}