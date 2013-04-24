<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


// Access Joomla's configuration file
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../../configuration.php");
	require_once $my_path . "/../../../../configuration.php";
}
elseif (file_exists($my_path . "/../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../configuration.php");
	require_once $my_path . "/../../configuration.php";
}
elseif (file_exists($my_path . "/configuration.php"))
{
	$absolute_path = dirname($my_path . "/configuration.php");
	require_once $my_path . "/configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);
define('_JEXEC', 1);
define('JPATH_BASE', $absolute_path);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . '/administrator/components/com_redshop');
define('JPATH_COMPONENT', JPATH_BASE . '/components/com_redshop');


// Load the framework

require_once $absolute_path . '/includes/defines.php';
require_once $absolute_path . '/includes/framework.php';

// Set up the appropriate CMS framework

// Create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

// Load system plugin group
JPluginHelper::importPlugin('system');

// Create the mainframe object
$app = JFactory::getApplication();

// Trigger the onBeforeStart events
$app->triggerEvent('onBeforeStart');
$lang           = JFactory::getLanguage();
$mosConfig_lang = $GLOBALS['mosConfig_lang'] = strtolower($lang->getBackwardLang());
$session        = JFactory::getSession();


/*
* File: CaptchaSecurityImages.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 03/08/06
* Updated: 07/02/07
* Requirements: PHP 4/5 with GD and FreeType libraries
* Link: http://www.white-hat-web-design.co.uk/articles/php-captcha.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

class CaptchaSecurityImages
{
	public $font = 'monofont.ttf';

	function generateCode($characters)
	{
		// List all possible characters, similar looking characters and vowels have been removed
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code     = '';
		$i        = 0;

		while ($i < $characters)
		{
			$code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			$i++;
		}

		return $code;
	}

	function CaptchaSecurityImages($width = '120', $height = '40', $characters = '6', $captchaname = 'security_code')
	{
		$session = JFactory::getSession();

		$code = $this->generateCode($characters);

		// Font size will be 75% of the image height
		$font_size = $height * 0.75;
		$image     = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		// Set the colours
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color       = imagecolorallocate($image, 20, 40, 100);
		$noise_color      = imagecolorallocate($image, 100, 120, 180);

		// Generate random dots in background
		for ($i = 0; $i < ($width * $height) / 3; $i++)
		{
			imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
		}

		// Generate random lines in background
		for ($i = 0; $i < ($width * $height) / 150; $i++)
		{
			imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color);
		}

		// Create textbox and add text
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4]) / 2;
		$y = ($height - $textbox[5]) / 2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font, $code) or die('Error in imagettftext function');

		// Output captcha image to browser
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);

		$_SESSION['pri_security_code'] = '';
		$_SESSION['security_code']     = '';

		if ($captchaname == 'pri_security_code')
		{
			$_SESSION['pri_security_code'] = $code;
		}
		else
		{
			$_SESSION['security_code'] = $code;
		}
	}
}

$width       = isset($_GET['width']) ? $_GET['width'] : '120';
$height      = isset($_GET['height']) ? $_GET['height'] : '40';
$characters  = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';
$captchaname = isset($_GET['captcha']) ? $_GET['captcha'] : 'security_code';

$captcha = new CaptchaSecurityImages($width, $height, $characters, $captchaname);
