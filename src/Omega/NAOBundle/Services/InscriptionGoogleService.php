<?php 

namespace Omega\NAOBundle\Services;
use Omega\NAOBundle\Entity\Utilisateurs;
use Omega\NAOBundle\Services\MailService;
use Doctrine\ORM\EntityManager;

class InscriptionGoogleService
{
	private $em;
	private $templating;
	private $mail;

	public function __construct(EntityManager $em, $templating, MailService $mail)
	{
		$this->em = $em;
		$this->templating = $templating;
		$this->mail = $mail;
	}

	public function register($request)
	{
		$lastname = $request->get('lastname');
        $firstname = $request->get('firstname');
        $email = $request->get('email');
        $googleId = $request->get('id');

        $username = $firstname.' '.$lastname;
        $password = uniqid();

        $inscription = new Utilisateurs();
        $inscription->setNom($lastname)
                    ->setUsername($username)
                    ->setEmail($email)
                    ->setPassword($password)
                    ->setCompte('particulier')
                    ->setRoles(array('ROLE_PARTICULIER'))
                    ->setSalt('')
                    ->setGoogleId($googleId)
        ;
        $this->em->persist($inscription);
        $this->em->flush();

        $emailBody = $this->templating->render('OmegaNAOBundle:Default:bodyMail.html.twig');
        $subject = 'Votre compte a bien été enregistré';
        $this->mail->getMailService($emailBody, $email, $subject);
	}
}