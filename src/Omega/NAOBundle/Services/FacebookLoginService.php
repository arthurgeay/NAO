<?php

namespace Omega\NAOBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Omega\NAOBundle\Entity\Utilisateurs;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class FacebookLoginService
{
    private $em;
    private $router;


        public function __construct (EntityManager $em, RouterInterface $router)
        {
            $this->em = $em;
            $this->router = $router;
        }

        function getConnect($page)
        {

            $pageCourante = $page;
            //v5.X//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $fb = new Facebook([
                'app_id' =>  '164427444154033',
                'app_secret' =>'d50ce35719164703e0941dc134283aed',
                'default_graph_version' => 'v2.4',
            ]);

            $helper = $fb->getRedirectLoginHelper();

            if (isset($_GET['state'])) {
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
            }

           try {
                $accessToken = $helper->getAccessToken();

            } catch(FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            if (! isset($accessToken)) {
                if ($helper->getError()) {
                    header('HTTP/1.0 401 Unauthorized');
                    echo "Error: " . $helper->getError() . "\n";
                    echo "Error Code: " . $helper->getErrorCode() . "\n";
                    echo "Error Reason: " . $helper->getErrorReason() . "\n";
                    echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                    header('HTTP/1.0 400 Bad Request');
                    echo 'Bad request';
                }
                exit;
            }

// Logged in
            //echo '<h3>Access Token</h3>';
           // var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            //echo '<h3>Metadata</h3>';
            //var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId( '164427444154033'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (FacebookSDKException $e) {
                    echo "<p>Error getting long-lived access token: " . $helper->getError() . "</p>\n\n";
                    exit;
                }

                echo '<h3>Long-lived</h3>';
                var_dump($accessToken->getValue());
            }

            $_SESSION['fb_access_token'] = (string) $accessToken;

            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/me?fields=id,name,email', $accessToken);
            } catch(FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $user = $response->getGraphUser();

           //var_dump($user);

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');


            if ($pageCourante == "inscription")
            {
                $inscription = new Utilisateurs();
                $inscription->setEmail($user->getEmail());
                $inscription->setCompte('particulier');
                $inscription->setUsername($user->getName());
                $inscription->setNom($user->getName());
                $inscription->setPassword('123456789');
                $inscription->setFacebookId($user->getId());
                $inscription->setSalt('');
                $inscription->setRoles(array('ROLE_PARTICULIER'));
                $this->em->persist($inscription);
                $this->em->flush();

            }
            elseif ($pageCourante == "connexion")
            {
                $utilisateur = $this->em
                    ->getRepository('OmegaNAOBundle:Utilisateurs')
                    ->findAll();
                $countComptes = $this->em->getRepository('OmegaNAOBundle:Utilisateurs')->countComptes();
                $count = (int) $countComptes;

                for ($i = 0; $count > $i ; $i ++)
                {
                    if ($utilisateur[$i]->getFacebookId() == $user->getId())
                    {
                       // var_dump($utilisateur[$i]);
                        $_SESSION['fb_access_token'] = (string) $tokenMetadata->getUserId();
                        return $_SESSION['fb_access_token'];
                    }
                }

            }
        }
}