<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

class CaptchaSecurityImages
{

	var $font = 'components/com_redshop/assets/captcha/monofont.ttf';

	function generateCode($characters)
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
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

		if ($captchaname == 'pri_security_code')
		{
			//$session->set('pri_security_code','');
			//	$session->set('pri_security_code',$code);
			setcookie('pri_security_code', $code);
			//echo $_SESSION['pri_security_code'] = $code; exit;
		}
		else
		{
			//$session->set('security_code','');
			//$session->set('security_code',$code);
			setcookie('security_code', $code);
		}
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image     = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color       = imagecolorallocate($image, 20, 40, 100);
		$noise_color      = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for ($i = 0; $i < ($width * $height) / 3; $i++)
		{
			imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for ($i = 0; $i < ($width * $height) / 150; $i++)
		{
			imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4]) / 2;
		$y = ($height - $textbox[5]) / 2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font, $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		ob_clean();
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		exit;

		//$session->clear($captchaname);
		//$session->clear('pri_security_code');
		//$session->set( $captchaname, $code );
		//$_SESSION['security_code'] = $code;
	}

}


?>