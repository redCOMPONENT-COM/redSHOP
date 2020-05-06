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
        $fb = self::getFbObject();

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl    = $helper->getLoginUrl(
            \JRoute::_(
                \JUri::root() . 'index.php?option=com_ajax&module=redshop_login&method=fbLoginCallBack&format=raw'
            ),
            $permissions
        );

        return $loginUrl;
    }

    /**
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @since  1.0
     */
    public static function fbLoginCallBackAjax()
    {
        $app   = \JFactory::getApplication();
        $input = $app->input;

        $fb = self::getFbObject();

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $app->enqueueMessage('Graph returned an error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $app->enqueueMessage('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
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
        $tokenMetadata->validateAppId('526790588005827');
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
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

        self::loginJoomlaRedShop($data);
    }

    /**
     * @return \Facebook\Facebook
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @since  1.0
     */
    public static function getFbObject()
    {
        $module = \JModuleHelper::getModule('mod_redshop_login');
        $params = new \JRegistry();
        $params->loadString($module->params);

        $fb = new \Facebook\Facebook(
            [
                'app_id'                => $params->get('fbappid', ''),
                'app_secret'            => $params->get('fbappsecret', ''),
                'default_graph_version' => 'v2.10',
            ]
        );

        return $fb;
    }

    /**
     * @since 1.0
     */
    public static function loginGoogle()
    {
        $client = self::getGoogleObject();

        //Đây là URL đến Google, bạn cần mở nếu chưa đăng nhập
        $auth_url = $client->createAuthUrl();

        return $auth_url;
    }

    /**
     * @throws Exception
     * @since  1.0
     */
    public static function googleLoginCallBackAjax()
    {
        $client = self::getGoogleObject();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            /*
             * Đã đăng nhập trước rồi do tồn tại access_token trong Session
             * Nên bạn không cần xác thực từ Google nữa mà chỉ việc lấy thông tin
             */

            self::getInfoUserGoogle($client);
        } else {
            $code = \JFactory::getApplication()->input->getString('code', '');

            if (isset($code)) {
                $tokenMetaData = $client->fetchAccessTokenWithAuthCode($code);

                //Lấy mã Token và lưu lại tại SESSION
                $token = $client->getAccessToken();

                $_SESSION['access_token'] = $token;
                self::getInfoUserGoogle($client);
            } else {
                //Chuyển hướng sang Google để lấy xác thực
                $auth_url = $client->createAuthUrl();
                header("Location: $auth_url");
                die();
            }
        }
    }

    /**
     * @param $client
     *
     * @throws Exception
     * @since  1.0
     */
    public static function getInfoUserGoogle($client)
    {
        $client->setAccessToken($_SESSION['access_token']);
        $google_oauth2 = new Google_Service_Oauth2($client);
        $google_user   = $google_oauth2->userinfo->get();

        if ($client->isAccessTokenExpired()) {
            //Truy cập bị hết hạn, cần xác thực lại
            //Chuyển hướng sang Google để lấy xác thực
            $auth_url = $client->createAuthUrl();
            header("Location: $auth_url");
            die();
        }

        $password = \JUserHelper::genRandomPassword(32);

        $data              = [];
        $data['password']  = $password;
        $data['password2'] = $password;
        $data['email']     = $data['email1'] = $data['username'] = $google_user->email;
        $data['name']      = $data['firstname'] = $google_user->givenName;

        self::loginJoomlaRedShop($data);
    }

    /**
     * @return Google_Client
     * @since  1.0
     */
    public static function getGoogleObject()
    {
        $client = new Google_Client();

        $redirectUri = \JRoute::_(
            \JUri::root() . 'index.php?option=com_ajax&module=redshop_login&method=googleLoginCallBack&format=raw'
        );  // URL này được Google chuyển hướng, khi người dùng đồng ý

        $client->setRedirectUri($redirectUri);

        $client->addScope(
            [
                'https://www.googleapis.com/auth/plus.login',
                'https://www.googleapis.com/auth/userinfo.email'
            ]
        );

        $module = \JModuleHelper::getModule('mod_redshop_login');
        $params = new \JRegistry();
        $params->loadString($module->params);

        //Set param google API
        $client->setClientId($params->get('ggclientid', ''));
        $client->setClientSecret($params->get('ggsecrect', ''));
        $client->setAccessType('offline');

        return $client;
    }

    /**
     * @param $data
     *
     * @throws Exception
     * @since  1.0
     */
    public static function loginJoomlaRedShop($data) {
        $app = \JFactory::getApplication();
        $jUser = \JUserHelper::getUserId($data['email']);

        if ($jUser > 0) {
            $jUser = \JFactory::getUser($jUser);

            JPluginHelper::importPlugin('user');

            $options           = array();
            $options['action'] = 'core.login.site';

            $result             = $app->triggerEvent('onUserLogin', array($data, $options));
        } else {
            $jUser = \RedshopHelperJoomla::createJoomlaUser($data, 1);
        }

        $check = \RedshopHelperUser::getUserInformation($jUser->id);

        if (empty($check)) {
            $redUser = \RedshopHelperUser::storeRedshopUser($data, $jUser->id);
        }

        $app->redirect(\JUri::root());
    }
}
