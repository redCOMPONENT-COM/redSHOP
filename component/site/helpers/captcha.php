<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
class RedshopSiteCaptcha
{
	/**
	 * Fort patch to be used in printing captcha code.
	 *
	 * @var  string
	 */
	public $font = 'components/com_redshop/assets/captcha/monofont.ttf';

	/**
	 * Generate captcha code to offer
	 *
	 * @param   integer  $characters  Howmany characters should be in captcha code.
	 *
	 * @return  string  Final prepared captcha code
	 */
	public function generateCode($characters)
	{
		// list all possible characters, similar looking characters and vowels have been removed
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

	/**
	 * Captcha code image to dispay
	 *
	 * @param  integer  $width        Width for showing captcha image
	 * @param  integer  $height       Height for showing captcha image
	 * @param  integer  $characters   Number of characters should be in captcha.
	 * @param  string   $name         Name of the captcha.
	 */
	public function __construct($width = 120, $height = 40, $characters = 6, $name = 'security_code')
	{
		$code = $this->generateCode($characters);

		if ($name == 'pri_security_code')
		{
			setcookie('pri_security_code', $code);
		}
		else
		{
			setcookie('security_code', $code);
		}

		// Font size will be 75% of the image height
		$font_size = $height * 0.75;
		$image     = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		// Set the colours
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
		ob_clean();

		header('Content-Type: image/jpeg');

		imagejpeg($image);
		imagedestroy($image);

		exit;
	}
}
