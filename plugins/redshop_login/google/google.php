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
require_once JPATH_PLUGINS . '/redshop_login/google/library/vendor/autoload.php';


/**
 * Generate login facebook
 *
 * @since __DEPLOY_VERSION__
 */
class PlgRedshop_LoginGoogle extends JPlugin
{
	public function onThirdPartyLogin()
    {
        $client = $this->getGoogleObject();

        return [
            'linkLogin' => $client->createAuthUrl(),
            'plugin' => $this->_name
        ];
    }

    /**
     * @return Google_Client
     * @since  1.0
     */
    public function getGoogleObject()
    {
        $client = new Google_Client();

        $redirectUri = \JRoute::_(
            \JUri::root() . 'index.php?option=com_ajax&group=redshop_login&plugin=googleLoginCallBack&format=raw'
        );  // URL này được Google chuyển hướng, khi người dùng đồng ý

        $client->setRedirectUri($redirectUri);

        $client->addScope(
            [
                'https://www.googleapis.com/auth/plus.login',
                'https://www.googleapis.com/auth/userinfo.email'
            ]
        );

        //Set param google API
        $client->setClientId($this->params->get('client_id', ''));
        $client->setClientSecret($this->params->get('secret', ''));
        $client->setAccessType('offline');

        return $client;
    }

    /**
     * @throws Exception
     * @since  1.0
     */
    public function onAjaxGoogleLoginCallBack()
    {
        $client = $this->getGoogleObject();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            /*
             * Đã đăng nhập trước rồi do tồn tại access_token trong Session
             * Nên bạn không cần xác thực từ Google nữa mà chỉ việc lấy thông tin
             */

            $this->getInfoUserGoogle($client);
        } else {
            $code = \JFactory::getApplication()->input->getString('code', '');

            if (isset($code)) {
                $tokenMetaData = $client->fetchAccessTokenWithAuthCode($code);

                //Lấy mã Token và lưu lại tại SESSION
                $token = $client->getAccessToken();

                $_SESSION['access_token'] = $token;
                $this->getInfoUserGoogle($client);
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
    public function getInfoUserGoogle($client)
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

        Redshop\Helper\Login::loginJoomlaRedShop($data);
    }
}
