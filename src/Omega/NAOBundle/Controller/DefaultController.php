<?php

namespace Omega\NAOBundle\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Facebook;
use Facebook\FacebookSDKException;
use Omega\NAOBundle\Entity\Utilisateurs;
use Omega\NAOBundle\Form\RechercheType;
use Omega\NAOBundle\Services\FacebookLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Omega\NAOBundle\Entity\Observations;
use Omega\NAOBundle\Form\ObservationsType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Omega\NAOBundle\Form\UtilisateursType;
use Omega\NAOBundle\Form\ContactType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $result = $this->get('omega_nao.index')->verifSecurity();
        return $this->render('OmegaNAOBundle:Default:index.html.twig', $result);
    }

    /**
     * @Security("has_role('ROLE_PARTICULIER')")
     */
    public function addObservationAction(Request $request)
    {
    	$observation = new Observations();
    	$form = $this->get('form.factory')->create(ObservationsType::class, $observation); // Création du form
    	$noms = $this->get('omega_nao.datataxref')->getData(); // On récupère les données pour l'autocomplétion

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		$this->get('omega_nao.add_obs')->persistData($observation);
    		return $this->redirectToRoute('omega_nao_add_observation');
    	}

    	return $this->render('OmegaNAOBundle:Observations:add.html.twig', array('form' => $form->createView(),
    		'noms' => $noms
    	));
    }

    public function moderationCompteAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('OmegaNAOBundle:Utilisateurs');
        $comptes = $repository->getCompte();

        return $this->render('OmegaNAOBundle:Moderation:compte.html.twig', array('comptes' => $comptes));

    }

    public function acceptCompteAction($id)
    {
        $this->get('omega_nao.moderation_compte')->accept($id);

        return $this->redirectToRoute('omega_nao_moderation_compte');
    }

    public function refusedCompteAction($id)
    {
        $this->get('omega_nao.moderation_compte')->refused($id);

        return $this->redirectToRoute('omega_nao_moderation_compte');

    }

    public function moderationObsAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('OmegaNAOBundle:Observations');
        $observations = $repository->getObservationParticulier();

        return $this->render('OmegaNAOBundle:Moderation:observation.html.twig', array('observations' => $observations));
    }

    public function acceptObsAction($id)
    {
        $this->get('omega_nao.moderation_observation')->accept($id);

        return $this->redirectToRoute('omega_nao_moderation_observation');   
    }

    public function deleteObsAction($id)
    {
        $this->get('omega_nao.moderation_observation')->delete($id);

        return $this->redirectToRoute('omega_nao_moderation_observation');
    }

    public function loginAction(Request $request)
    {
        //Facebook inscription//////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //v.5.x  ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $fb = new Facebook([
            'app_id' => '164427444154033', // Replace {app-id} with your app id
            'app_secret' => 'd50ce35719164703e0941dc134283aed',
            'default_graph_version' => 'v2.4',
        ]);

    
        
        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('http://localhost/NAO/web/login', $permissions);

        if ($request->query->get('code'))
        {
            $user = $this->container->get('NAOBundle.FacebookLogin');
            $varibaleFB = $user->getConnect('connexion');

            //var_dump($varibaleFB);
            $request->query->set('code', $varibaleFB);
            $authenticationUtils = $this->get('security.authentication_utils');

            return $this->render('OmegaNAOBundle:Utilisateurs:login.html.twig', array(
                'id' => $varibaleFB, 'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(), 'url' => $loginUrl));

        }

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
            'error'         => $authenticationUtils->getLastAuthenticationError(), 'url' => $loginUrl
        ));
    }

    public function inscriptionAction (Request $request)
    {
        $inscription = new Utilisateurs();
        $formInscription = $this->get('form.factory')->create(UtilisateursType::class, $inscription);
        $em = $this->getDoctrine()->getManager();
        $passwordEncoder = $this->get('security.password_encoder');
        //Facebook inscription//////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //v.5.x  ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $fb = new Facebook([
            'app_id' => '164427444154033', // Replace {app-id} with your app id
            'app_secret' => 'd50ce35719164703e0941dc134283aed',
            'default_graph_version' => 'v2.4',
        ]);

        $url = 'http://localhost'.$_SERVER['REDIRECT_URL'];

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($url, $permissions);
        $urlFB = "";
        if (!$request->query->get('code'))
        {
            $urlFB = $loginUrl;
        }
        if ($request->query->get('code'))
        {
            $user = $this->container->get('NAOBundle.FacebookLogin');
            $user->getConnect('inscription');

            $authenticationUtils = $this->get('security.authentication_utils');

            return $this->render('OmegaNAOBundle:Default:index.html.twig');
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        elseif ($request->isMethod('POST') && $formInscription->handleRequest($request)->isValid())
        {
            $emailBody = $this->renderView('OmegaNAOBundle:Default:bodyMail.html.twig');

            $password = $passwordEncoder->encodePassword($inscription, $inscription->getPassword());
            $inscription->setPassword($password);
            $inscription->setSalt('');
            $inscription->setRoles(array('ROLE_PARTICULIER'));
            $em->persist($inscription);
            $em->flush();

            $mailerService = $this->container->get('NAOBundle.mailInscription');
            $mailerService->getMailInscriptionService($emailBody, $inscription->getEmail());
        }

        return $this->render('OmegaNAOBundle:Utilisateurs:inscription.html.twig', array('formInscription' => $formInscription->createView(), 'url' =>$urlFB));
    }

    public function inscriptionGoogleAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $this->get('omega_nao.register_google')->register($request);
            return $this->redirectToRoute('omega_nao_homepage');
        }

        return new Response('Erreur ce n\'est pas une requête Ajax', 400);
    }

    public function rechercheAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formRecherche = $this->createForm(RechercheType::class, null);
        $recherche = array();
        $ficheEspece = null;
        $count = 0;
        $countEspeces = 0;
        $noms = $this->get('omega_nao.datataxref')->getData(); // On récupère les données pour l'autocomplétion

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
                                                                                            'count' => $count, 'ficheEspece' => $ficheEspece, 'countEspece' => $countEspeces, 'noms' => $noms));
    }


    /**
     * @Security("has_role('ROLE_PARTICULIER')")
     */
    public function profilAction()
    {
        $user = $this->getUser();

        return $this->render('OmegaNAOBundle:utilisateurs:profil.html.twig', array('user' => $user));
    }

    public function changerTypeCompteAction($id)
    {           
        $this->get('omega_nao.change_compte')->change($id);
        return $this->redirectToRoute('omega_nao_profile');
    }

    public function authentificationFB ()
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


    public function mentionsLegalesAction()
    {
        return $this->render('OmegaNAOBundle:Default:mentions-legales.html.twig');
    }

    public function cguAction()
    {
        return $this->render('OmegaNAOBundle:Default:cgu.html.twig');
    }

    public function contactAction (Request $request)
    {
        $formContact = $this->createForm(ContactType::class, null);
        if ($request->isMethod('POST') && $formContact->handleRequest($request)->isValid())
        {
            $corps = $formContact['Message']->getData();

            $subject = $formContact['Sujet']->getData();
            $email = $formContact['Email']->getData();
            $emailBody = $this->renderView('OmegaNAOBundle:Default:bodyMailContact.html.twig', array('corps' => $corps));
            $mailerService = $this->container->get('NAOBundle.mail');
            $mailerService->getMailService($emailBody, $email, $subject);

            return $this->redirectToRoute('omega_nao_homepage');
        }

        return $this->render('OmegaNAOBundle:Contact:contact.html.twig', array('formContact' => $formContact->createView()));
    }

    public function EnvoiMail ()
    {

    }
}
