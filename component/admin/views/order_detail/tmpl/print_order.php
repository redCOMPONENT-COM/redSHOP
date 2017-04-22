<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$carthelper = rsCarthelper::getInstance();
$uri = JURI::getInstance();
$url = $uri->root();
$redTemplate = Redtemplate::getInstance();
$OrdersDetail = $this->detail;
$order_print_template = $redTemplate->getTemplate("order_print");

if (count($order_print_template) > 0 && $order_print_template[0]->template_desc != "")
{
	$ordersprint_template = $order_print_template[0]->template_desc;
}
else
{
	$ordersprint_template = JLayoutHelper::render('product.attributes');
}

$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
	. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='" . JText::_('COM_REDSHOP_PRINT') . "' /></a>";

$message = str_replace("{print}", $print_tag, $ordersprint_template);
echo eval("?>" . $message . "<?php ");
?>
