<?php

namespace Omega\NAOBundle\Services;
use Doctrine\ORM\EntityManager;
use Omega\NAOBundle\Services\MailService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModerationCompteService
{
	private $em;
	private $templating;
	private $mail;
	private $session;

	public function __construct(EntityManager $em, $templating, MailService $mail, Session $session)
	{
		$this->em = $em;
		$this->templating = $templating;
		$this->mail = $mail;
		$this->session = $session;
	}

	public function accept($id)
	{
		$repository = $this->em->getRepository('OmegaNAOBundle:Utilisateurs');
        $compte = $repository->find($id);

        if($compte == null)
        {
            throw new NotFoundHttpException("Le compte n'existe pas");
        }

        $compte->setVerifie(true);
        $compte->setRoles(array('ROLE_NATURALISTE'));

        $this->em->persist($compte);
        $this->em->flush();

        $emailBody = $this->templating->render('OmegaNAOBundle:Default:mailAcceptCompte.html.twig', array('name' => $compte->getUsername()));
        $subject = "Validation du compte";
        $mail = $this->mail->getMailService($emailBody, $compte->getEmail(), $subject);
 
        $this->session->getFlashBag()->add('success', 'Le compte a bien été validé en tant que naturaliste.');
	}

	public function refused($id)
	{
		$repository = $this->em->getRepository('OmegaNAOBundle:Utilisateurs');
        $compte = $repository->find($id);

        if($compte == null)
        {
            throw new NotFoundHttpException("Le compte n'existe pas");
        }

        $compte->setCompte('particulier')
               ->setRoles(array('ROLE_PARTICULIER'));

        $this->em->persist($compte);
        $this->em->flush();

        $emailBody = $this->templating->render('OmegaNAOBundle:Default:mailRefusedCompte.html.twig', array('name' => $compte->getUsername()));
        $subject = "Votre demande a été rejetée";
        $mail = $this->mail->getMailService($emailBody, $compte->getEmail(), $subject);

        $this->session->getFlashBag()->add('success', 'Le compte a bien été refusé. Le type du compte a été basculé en particulier.');
	}
}