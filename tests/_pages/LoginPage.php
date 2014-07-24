<?php

class LoginPage
{
    // include url of current page
	public static $URL = 'tests/system/joomla-cms/administrator/index.php';

	public static $usernameField = '#mod-login-username';
	public static $passwordField = '#mod-login-password';
	public static $loginButton = 'Log in';


    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
        return static::$URL.$param;
     }


}