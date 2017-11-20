<?php

namespace Omega\NAOBundle\Services;

use Doctrine\ORM\EntityManager;
use Omega\NAOBundle\Services\MailService;
use Omega\NAOBundle\Services\UploadedPhotosService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModerationObservationService
{
	private $em;
	private $templating;
	private $mail;
	private $session;
	private $upload;

	public function __construct(EntityManager $em, $templating, MailService $mail, Session $session, UploadedPhotosService $upload)
	{
		$this->em = $em;
		$this->templating = $templating;
		$this->mail = $mail;
		$this->session = $session;
		$this->upload = $upload;
	}

	public function accept($id)
	{
		$repository = $this->em->getRepository('OmegaNAOBundle:Observations');
        $observation = $repository->find($id);

        if($observation == null)
        {
            throw new NotFoundHttpException("L'observation n'existe pas");
        }

        $observation->setVerifie(true);

        $this->em->persist($observation);
        $this->em->flush();

        $emailBody = $this->templating->render('OmegaNAOBundle:Default:mailAcceptObs.html.twig', array('name' => $observation->getUtilisateur()->getUsername()));
        $subject = "Validation de votre observation";
        $this->mail->getMailService($emailBody, $observation->getUtilisateur()->getEmail(), $subject);

        $this->session->getFlashBag()->add('success', "L'observation a bien été validée.");
	}

	public function delete($id)
	{
		$repository = $this->em->getRepository('OmegaNAOBundle:Observations');
        $observation = $repository->find($id);

        if($observation == null)
        {
            throw new NotFoundHttpException("L'observation n'existe pas");
        }

        $this->em->remove($observation);

        if($observation->getPhoto() != null)
        {
            $this->upload->remove($observation);
        }

        $this->em->flush();

        $emailBody = $this->templating->render('OmegaNAOBundle:Default:mailDeleteObs.html.twig', array('name' => $observation->getUtilisateur()->getUsername()));
        $subject = "Observation refusée";
        $this->mail->getMailService($emailBody, $observation->getUtilisateur()->getEmail(), $subject);

        $this->session->getFlashBag()->add('success', "L'observation a bien été supprimée.");
	}
}