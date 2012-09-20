<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined ('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
$helper = new redhelper();
$redTemplate = new Redtemplate();

$Itemid = $helper->getCheckoutItemid();
$Itemid = JRequest::getVar('Itemid',$Itemid);
$Itemid = $helper->getCartItemid($Itemid);

$cart_template = "";
$ajax_template = $redTemplate->getTemplate ( "ajax_cart_box" );
if(count($ajax_template)>0 && $ajax_template[0]->template_desc)
{
	$cart_template = $ajax_template[0]->template_desc;
} else {
	$cart_template = "<div id=\"ajax_cart_wrapper\">\r\n<div id=\"ajax_cart_text\">{ajax_cart_box_title}<br />{show_cart_text}<br /></div>\r\n<div id=\"ajax_cart_button_wrapper\">\r\n<div id=\"ajax_cart_button_inside\">\r\n<div id=\"ajax_cart_continue_button\">{continue_shopping_button}</div>\r\n<div id=\"ajax_cart_show_button\">{show_cart_button}</div>\r\n</div>\r\n</div>\r\n</div>";
}

$cart_template = str_replace("{ajax_cart_box_title}",JText::_('CART_SAVE'),$cart_template);
$cart_template = str_replace("{show_cart_text}",JText::_('SHOW_CART_TEXT'),$cart_template);
?>


<?php 
$viewbutton = '<input type="button" name="viewcart" class="view_cart_button" value="'.JText::_('VIEW_CART').'" onclick="javascript:window.location.href=\''.JRoute::_('index.php?option=com_redshop&view=cart&Itemid='.$Itemid).'\'">';

/*
 * continue redirection link
 */
if(CONTINUE_REDIRECT_LINK != '')
{
	$shopmorelink = JRoute::_( CONTINUE_REDIRECT_LINK );
	$countinuebutton 	= '<input type="button" name="continuecart" class="continue_cart_button" value="'.JText::_('CONTINUE_SHOPPING').'" onclick="document.location=\''.$shopmorelink.'\'" >';
}
else
{	
	$shopmorelink = $_SERVER['HTTP_REFERER'];
	$countinuebutton = '<input type="button" name="continuecart" class="continue_cart_button" value="'.JText::_('CONTINUE_SHOPPING').'" onclick="document.location=\''.$shopmorelink.'\'" >';
}

$cart_template = str_replace("{show_cart_button}",$viewbutton,$cart_template);
$cart_template = str_replace("{continue_shopping_button}",$countinuebutton,$cart_template);


//echo $cart_template;
echo eval("?>".$cart_template."<?php ");
exit;
?>
