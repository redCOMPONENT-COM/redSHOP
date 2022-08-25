<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');
require_once JPATH_PLUGINS . '/redshop_login/facebook/library/vendor/autoload.php';

/**
 * Generate login facebook
 *
 * @since __DEPLOY_VERSION__
 */
class PlgRedshop_LoginFacebook extends JPlugin
{
    /**
     * @return array|false
     */
    public function onThirdPartyLogin()
    {
        try {
            $fb = $this->getFbObject();
            $helper = $fb->getRedirectLoginHelper();

            $permissions = ['email']; // Optional permissions

            $linkLogin =  $helper->getLoginUrl(
                JRoute::_(
                    \JUri::root() . 'index.php?option=com_ajax&group=redshop_login&plugin=fbLoginCallBack&format=raw'
                ),
                $permissions
            );

            return [
                'linkLogin' => $linkLogin,
                'plugin' => $this->_name
            ];
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            return false;
        }
    }

    /**
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function onAjaxFbLoginCallBack()
    {
        $app    = \JFactory::getApplication();
        $fb = $this->getFbObject();

        $helper = $fb->getRedirectLoginHelper();

        $urlCallback = JURI::root() . 'index.php?option=com_ajax&group=redshop_login&plugin=fbLoginCallBack&format=raw';

        try {
            $accessToken = $helper->getAccessToken($urlCallback);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $app->enqueueMessage('Graph returned an error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $app->enqueueMessage('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if ( ! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                $msg = "Error: " . $helper->getError() . "\n";
                $msg .= "Error Code: " . $helper->getErrorCode() . "\n";
                $msg .= "Error Reason: " . $helper->getErrorReason() . "\n";
                $msg .= "Error Description: " . $helper->getErrorDescription() . "\n";

                $app->enqueueMessage($msg);
            } else {
                header('HTTP/1.0 400 Bad Request');
                $app->enqueueMessage('Bad request');
            }
        }

        // Logged in
        $token = $accessToken->getValue();

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $userFbId      = $tokenMetadata->getUserId();

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->params->get('app_id', ''));
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if ( ! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                $app->enqueueMessage("Error getting long-lived access token: " . $e->getMessage());
            }

            $token = $accessToken->getValue();
        }

        $response = $fb->get('/me?fields=id,name,email', $token);
        $userFb   = $response->getGraphUser();

        $_SESSION['fb_access_token'] = (string)$accessToken;

        $password = \JUserHelper::genRandomPassword(32);

        $data              = [];
        $data['password']  = $password;
        $data['password2'] = $password;
        $data['email']     = $data['email1'] = $data['username'] = $userFb->getEmail();
        $data['name']      = $data['firstname'] = $userFb->getName();

        Redshop\Helper\Login::loginJoomlaRedShop($data);
    }


    /**
     * @return \Facebook\Facebook
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @since  1.0
     */
    public function getFbObject()
    {
        return new \Facebook\Facebook(
            [
                'app_id'                => $this->params->get('app_id', ''),
                'app_secret'            => $this->params->get('app_secret', ''),
                'default_graph_version' => 'v2.10',
            ]
        );
    }
}
