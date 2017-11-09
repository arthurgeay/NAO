<?php

namespace Omega\NAOBundle\Services;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;



class FacebookLoginService
{
    private $appId;
    private $appSecret;


        function connect($redirect_url)
        {
            //v4.X//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

           /* FacebookSession::setDefaultApplication($this->appId, $this->appSecret);
            $helper = new FacebookRedirectLoginHelper($redirect_url);
            if(isset($_SESSION) && isset($_SESSION['fb_token'])){
                $session = new FacebookSession($_SESSION['fb_token']);
            }else{
                $session = $helper->getSessionFromRedirect();
            }
            if($session){
                try{
                    $_SESSION['fb_token'] = $session->getToken();
                    $request = new FacebookRequest($session, 'GET', '/me');
                    $profile = $request->execute()->getGraphObject('Facebook\GraphUser');
                    if($profile->getEmail() === null){
                        throw new \Exception('L\'email n\'est pas disponible');
                    }
                    return $profile;
                }catch (\Exception $e){
                    unset($_SESSION['fb_token']);
                    return $helper->getReRequestUrl(['email']);
                }
            }else{
                return $helper->getLoginUrl(['email']);
            }*/

            //v5.X//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $fb = new Facebook([
                'app_id' =>  '164427444154033',
                'app_secret' =>'d50ce35719164703e0941dc134283aed',
                'default_graph_version' => 'v2.2',
            ]);

            $helper = $fb->getRedirectLoginHelper();

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
            echo '<h3>Access Token</h3>';
            var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            echo '<h3>Metadata</h3>';
            var_dump($tokenMetadata);

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

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
        }
}