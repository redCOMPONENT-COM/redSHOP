<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products3d
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$uri = JURI::getInstance();
$url = $uri->root();
$Itemid = JRequest::getInt('Itemid');

$document = JFactory::getDocument();
JHTML::script('modules/mod_redproducts3d/js/redproduct360.js');

$enableImageReflection = 0;
$enableimageStroke = 0;
$enableMouseOverToolTip = 0;
$enableMouseOverEffects = 0;

if ($enableImageReflection == 'yes')
{
	$enableImageReflection = 1;
}
if ($enableimageStroke == 'yes')
{
	$enableimageStroke = 1;
}
if ($enableMouseOverToolTip == 'yes')
{
	$enableMouseOverToolTip = 1;
}
if ($enableMouseOverEffects == 'yes')
{
	$enableMouseOverEffects = 1;
}

$data = "";
for ($i = 0, $in = count($rows); $i < $in; $i++)
{
	$row  = $rows[$i];
	$path = REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
	if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $row->product_full_image))
	{
		$path = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $row->product_full_image;
	}
	$link = $url . 'index.php?option=com_redshop%26view=product%26pid=' . $row->product_id;
	$data .= "thumb=" . $path . " | description=" . $row->product_name . " | name=" . $link . " | param=_self ";
}

$document->addScriptDeclaration('
	window.onerror=function(){
 		return true;
	}

	var jxtc4bff6dcf168a9params = {bgcolor:"#111111",allowfullscreen:"true",scale:"exactFit",salign:"TL",wmode:"transparent"};
	var jxtc4bff6dcf168a9flashvars = {
		stageW:"' . $stageWidth . '",
		stageH:"' . $stageHeight . '",
		menuSettings:"pictureWidth=' . $thumbwidth . ' | pictureHeight=' . $thumbheight . ' | radius=' . $radius . ' | focalBlur=' . $focalBlur . ' | elevation=' . $elevation . ' | enableImageReflection=' . $enableImageReflection . ' | enableimageStroke=' . $enableimageStroke . ' | enableMouseOverToolTip=' . $enableMouseOverToolTip . ' | enableMouseOverEffects=' . $enableMouseOverEffects . ' ",
		menuItems:"' . $data . '"
		};
		swfobject.embedSWF("modules/mod_redproducts3d/js/3Dcarousel.swf", "jxtc4bff6dcf168a9", ' . $stageWidth . ', ' . $stageHeight . ', "9.0.124", null, jxtc4bff6dcf168a9flashvars, jxtc4bff6dcf168a9params);
	');
?>
<div id=jxtc4bff6dcf168a9></div>
