<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_login
 *
 * @since  1.5
 */
class ModRedshopLoginHelper
{
    /**
     * @param $params
     * @param $type
     *
     * @return string
     * @throws Exception
     * @since  1.0
     */
    public static function getReturnUrl($params, $type)
    {
        $app  = JFactory::getApplication();
        $item = $app->getMenu()->getItem($params->get($type));

        // Stay on the same page
        $url = JUri::getInstance()->toString();

        if ($item) {
            $lang = '';

            if ($item->language !== '*' && JLanguageMultilang::isEnabled()) {
                $lang = '&lang=' . $item->language;
            }

            $url = 'index.php?Itemid=' . $item->id . $lang;
        }

        return base64_encode($url);
    }

    /**
     * Returns the current users type
     *
     * @return string
     * @since  1.0
     */
    public static function getType()
    {
        $user = JFactory::getUser();

        return (!$user->get('guest')) ? 'logout' : 'login';
    }

    /**
     * Get list of available two factor methods
     *
     * @return array
     *
     * @deprecated  4.0  Use JAuthenticationHelper::getTwoFactorMethods() instead.
     * @since       1.0
     */
    public static function getTwoFactorMethods()
    {
        JLog::add(
            __METHOD__ . ' is deprecated, use JAuthenticationHelper::getTwoFactorMethods() instead.',
            JLog::WARNING,
            'deprecated'
        );

        return JAuthenticationHelper::getTwoFactorMethods();
    }

    /**
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @since  1.0
     */
    public static function loginFb()
    {
        $fb = new Facebook\Facebook(
            [
                'app_id'                => '526790588005827',
                'app_secret'            => 'ceee70802e8b8da67727737036b93655',
                'default_graph_version' => 'v2.10',
            ]
        );

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl    = $helper->getLoginUrl(\JRoute::_(\JUri::root() . 'index.php?option=com_ajax&module=redshop_login&method=fbLoginCallBack&format=raw'), $permissions);

        echo '<a href="' . $loginUrl . '" class="btn btn-primary login-button">Log in with Facebook!</a>';
    }

    /**
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @since  1.0
     */
    public static function fbLoginCallBackAjax() {
        $input = \JFactory::getApplication()->input;

        $response = $input->getString('code', '');
        $error = $input->getString('error_message', null);

        $fb = new \Facebook\Facebook(
            [
                'app_id'                => '526790588005827',
                'app_secret'            => 'ceee70802e8b8da67727737036b93655',
                'default_graph_version' => 'v2.10',
            ]
        );

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
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
        $tokenMetadata->validateAppId($config['app_id']);
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string)$accessToken;

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');
    }
}
