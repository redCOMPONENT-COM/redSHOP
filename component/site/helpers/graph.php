<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$bar_w     = 30;
$max_y_arr = array();
$a         = 1;
$pt        = "pt" . $a;

while (isset($_GET[$pt]))
{
	$max_y_arr[] = $_GET[$pt];
	$a++;
	$pt = "pt" . $a;
}

if (count($max_y_arr) > 0)
{
	$max_y = max($max_y_arr) + 100;
}
else
{
	$max_y = 1500;
}

$margin_top    = 20;
$margin_bottom = 20;
$margin_left   = 20;
$margin_right  = 10;
$y_div         = 10;
$rects         = array();
$data          = array();
$last_x2       = $margin_left;
$i             = 1;
$pt            = "pt" . $i;

while (isset($_GET[$pt]))
{
	$x1 = $last_x2 + 1;
	$x2 = $x1 + $bar_w;
	$y1 = $margin_top + $max_y - $_GET[$pt];
	$y2 = $margin_top + $max_y;
	$ar = array($x1, $y1, $x2, $y2);
	array_push($rects, $ar);
	$data[$i - 1] = $_GET[$pt];
	$i++;
	$last_x2 = $x2;
	$pt      = "pt" . $i;
}

$img_w = $last_x2 + $margin_right;
$img_h = $margin_top + $max_y + $margin_bottom;

$ih = imagecreate($img_w, $img_h);

$black = imagecolorallocate($ih, 0, 0, 0);
$white = imagecolorallocate($ih, 255, 255, 255);

imagefill($ih, 0, 0, $white);

for ($r = 0; $r < count($rects); $r++)
{
	$red   = rand(0, 255);
	$green = rand(0, 255);
	$blue  = rand(0, 255);

	if (($r % 2) == 0)
	{
		$hist_color = imagecolorallocate($ih, 190, 10, 10);
	}
	else
	{
		$hist_color = imagecolorallocate($ih, 210, 210, 0);
	}

	imagefilledrectangle($ih, $rects[$r][0] + 8, $rects[$r][1], $rects[$r][2], $rects[$r][3], $hist_color);

	imagettftext($ih, 14, 90, $rects[$r][2] - 5, $rects[$r][1], $black, 'fonts/ARIAL.TTF', $data[$r]);

	imageline($ih, $rects[$r][2], $margin_top + $max_y, $rects[$r][2], $margin_top + $max_y + 3, $black);
	$ttfbox  = imagettfbbox(8, 0, 'fonts/ARIAL.TTF', "pt" . ($r + 1));
	$half_pt = ($bar_w / 2) - ceil(($ttfbox[4] - $ttfbox[6]) / 2);

	if (($r % 2) == 0)
	{
		imagettftext($ih, 8, 0, $rects[$r][0] + $half_pt, $rects[$r][3] + 10, $black, 'fonts/ARIAL.TTF', "nl" . ($r + 1));
	}
	else
	{
		imagettftext($ih, 8, 0, $rects[$r][0] + $half_pt, $rects[$r][3] + 10, $black, 'fonts/ARIAL.TTF', "rnl");
	}
}

imageline($ih, $margin_left, $margin_top, $margin_left, $margin_top + $max_y + 3, $black);
imageline($ih, $margin_left, $margin_top + $max_y, $last_x2, $margin_top + $max_y, $black);
$tick = 0;

while ($tick < $max_y)
{
	$tick += $y_div;
	$tick_y = $margin_top + $max_y - $tick;
	imageline($ih, $margin_left - 3, $tick_y, $margin_left, $tick_y, $black);
}

imagepng($ih);
imagedestroy($ih);
