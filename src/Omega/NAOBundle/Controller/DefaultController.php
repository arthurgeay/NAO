<?php

namespace Omega\NAOBundle\Controller;

use Omega\NAOBundle\Entity\Utilisateurs;
use Omega\NAOBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Omega\NAOBundle\Entity\Observations;
use Omega\NAOBundle\Form\ObservationsType;

use Symfony\Component\Security\Core\SecurityContext;
use Omega\NAOBundle\Form\UtilisateursType;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OmegaNAOBundle:Default:index.html.twig');
    }


    public function addObservationAction(Request $request)
    {
    	$observation = new Observations();
    	$form = $this->get('form.factory')->create(ObservationsType::class, $observation);

    	$noms = $this->get('omega_nao.datataxref')->getData();

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		$this->get('omega_nao.upload')->upload($observation);
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($observation);
    		$em->flush();

    		$this->get('session')->getFlashBag()->add('success', 'Votre observation a bien été ajoutée');

    		return $this->redirectToRoute('omega_nao_add_observation');
    	}

    	return $this->render('OmegaNAOBundle:Observations:add.html.twig', array('form' => $form->createView(),
    		'noms' => $noms
    	));
    }
    
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('omega_nao_homepage');
        }
        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('OmegaNAOBundle:Utilisateurs:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function inscriptionAction (Request $request)
    {
        $inscription = new Utilisateurs();
        $formInscription = $this->get('form.factory')->create(UtilisateursType::class, $inscription);
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST') && $formInscription->handleRequest($request)->isValid())
        {
            $emailBody = $this->renderView('OmegaNAOBundle:Default:bodyMail.html.twig');
            $inscription->setSalt('');
            $inscription->setRoles(array('ROLE_PARTICULIER'));
            $em->persist($inscription);
            $em->flush();

            $mailerService = $this->container->get('NAOBundle.mail');
            $mailerService->getMailService($emailBody, $inscription->getEmail());
        }

        return $this->render('OmegaNAOBundle:Utilisateurs:inscription.html.twig', array('formInscription' => $formInscription->createView()));
    }

    public function rechercheAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formRecherche = $this->createForm(RechercheType::class, null);
        $recherche = array();
        $ficheEspece = null;
        $count = 0;
        $countEspeces = 0;

        if ($request->isMethod('POST') && $formRecherche->handleRequest($request)->isValid())
        {
            $espece[0] = $formRecherche->getData('espece');
            $recherche = $em->getRepository('OmegaNAOBundle:Observations')->RecupObservation($espece);
            $countRecherche = $em->getRepository('OmegaNAOBundle:Observations')->countObservation($espece);
            $count = (int) $countRecherche;
            $ficheEspece = $em->getRepository('OmegaNAOBundle:Taxref')->RecupEspece($espece);
            $countEspece = $em->getRepository('OmegaNAOBundle:Taxref')->countEspece($espece);
            $countEspeces = (int) $countEspece;

        }
        return $this->render('OmegaNAOBundle:Rechercher:rechercher.html.twig', array('formRecherche' => $formRecherche->createView(),  'recherche'=> $recherche,
                                                                                            'count' => $count, 'ficheEspece' => $ficheEspece, 'countEspece' => $countEspeces));
    }
}
