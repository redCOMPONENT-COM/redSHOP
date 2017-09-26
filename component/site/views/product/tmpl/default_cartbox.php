<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


$Itemid = $this->redHelper->getCartItemid();

$cart_template = "";
$ajax_template = $this->redTemplate->getTemplate("ajax_cart_box");

if (count($ajax_template) > 0 && $ajax_template[0]->template_desc)
{
	$cart_template = $ajax_template[0]->template_desc;
}
else
{
	$cart_template = "<div id=\"ajax_cart_wrapper\">\r\n<div id=\"ajax_cart_text\">{ajax_cart_box_title}<br />{show_cart_text}<br /></div>\r\n<div id=\"ajax_cart_button_wrapper\">\r\n<div id=\"ajax_cart_button_inside\">\r\n<div id=\"ajax_cart_continue_button\">{continue_shopping_button}</div>\r\n<div id=\"ajax_cart_show_button\">{show_cart_button}</div>\r\n</div>\r\n</div>\r\n</div>";
}

$cart_template = str_replace("{ajax_cart_box_title}", JText::_('COM_REDSHOP_CART_SAVE'), $cart_template);
$cart_template = str_replace("{show_cart_text}", JText::_('COM_REDSHOP_SHOW_CART_TEXT'), $cart_template);

$viewbutton = '<input type="button" name="viewcart" class="view_cart_button btn btn-primary" value="' . JText::_('COM_REDSHOP_VIEW_CART') . '" onclick="javascript:window.location.href=\'' . JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid) . '\'">';

/*
 * continue redirection link
 */
if (Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK') != '')
{
	$shopmorelink    = JRoute::_(Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK'));
	$countinuebutton = '<input type="button" name="continuecart" class="continue_cart_button btn" value="' . JText::_('COM_REDSHOP_CONTINUE_SHOPPING') . '" onclick="document.location=\'' . $shopmorelink . '\'" >';
}
else
{
	$shopmorelink    = $_SERVER['HTTP_REFERER'];
	$countinuebutton = '<input type="button" name="continuecart" class="continue_cart_button btn" value="' . JText::_('COM_REDSHOP_CONTINUE_SHOPPING') . '" onclick="document.location=\'' . $shopmorelink . '\'" >';
}

$cart_template = str_replace("{show_cart_button}", $viewbutton, $cart_template);
$cart_template = str_replace("{continue_shopping_button}", $countinuebutton, $cart_template);

echo eval("?>" . $cart_template . "<?php ");
JFactory::getApplication()->close();
