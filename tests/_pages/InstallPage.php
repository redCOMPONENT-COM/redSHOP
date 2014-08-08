<?php

class InstallPage
{
	// include url of current page
	public static $URL = 'installation/';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */
	public static $language = "//option[contains(text(),'English (United States)')]";

	public static $nextButton = ""

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	public static function route($param)
	{
		return static::$URL . $param;
	}
}
