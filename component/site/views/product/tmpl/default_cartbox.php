<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$cartTemplate = "";
$ajaxTemplate = $this->redTemplate->getTemplate("ajax_cart_box");

if (count($ajaxTemplate) > 0 && $ajaxTemplate[0]->template_desc)
{
	$cartTemplate = $ajaxTemplate[0]->template_desc;
}
else
{
	$cartTemplate = "<div id=\"ajax_cart_wrapper\">\r\n<div id=\"ajax_cart_text\">{ajax_cart_box_title}<br />{show_cart_text}<br /></div>\r\n<div id=\"ajax_cart_button_wrapper\">\r\n<div id=\"ajax_cart_button_inside\">\r\n<div id=\"ajax_cart_continue_button\">{continue_shopping_button}</div>\r\n<div id=\"ajax_cart_show_button\">{show_cart_button}</div>\r\n</div>\r\n</div>\r\n</div>";
}

$cartTemplate = RedshopTagsReplacer::_(
					'ajaxcartbox',
					$cartTemplate,
					array()
				);

echo eval("?>" . $cartTemplate . "<?php ");
JFactory::getApplication()->close();
