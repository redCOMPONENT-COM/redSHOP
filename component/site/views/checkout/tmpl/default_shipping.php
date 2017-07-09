<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$redTemplate = Redtemplate::getInstance();
$carthelper = rsCarthelper::getInstance();

$user   = JFactory::getUser();
$jinput = JFactory::getApplication()->input;
$post   = $jinput->getArray($_POST);

$shippingbox_template = $redTemplate->getTemplate("shippingbox");

if (count($shippingbox_template) > 0 && $shippingbox_template[0]->template_desc)
{
	$box_template_desc = $shippingbox_template[0]->template_desc;
}
else
{
	$box_template_desc = "<fieldset class=\"adminform\"> <legend><strong>{shipping_box_heading}</strong></legend>\r\n<div>{shipping_box_list}</div>\r\n</fieldset>";
}

$shipping_template = $redTemplate->getTemplate("redshop_shipping");

if (count($shipping_template) > 0 && $shipping_template[0]->template_desc)
{
	$template_desc = $shipping_template[0]->template_desc;
}
else
{
	$template_desc = "<fieldset class=\"adminform\"><legend><strong>{shipping_heading}</strong></legend>\r\n<div>{shipping_method_loop_start}\r\n<h3>{shipping_method_title}</h3>\r\n<div>{shipping_rate_loop_start}\r\n<div>{shipping_rate_name} {shipping_rate}</div>\r\n{shipping_rate_loop_end}</div>\r\n{shipping_method_loop_end}</div>\r\n</fieldset>";
}

if ($this->users_info_id > 0)
{
	$shippinghelper          = shipping::getInstance();
	$shippingBoxes           = $shippinghelper->getShippingBox();
	$selshipping_box_post_id = 0;

	if (count($shippingBoxes) > 0)
	{
		$selshipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
	}

	if (isset($post['shipping_box_id']))
	{
		$shipping_box_post_id = $post['shipping_box_id'];
	}
	else
	{
		$shipping_box_post_id = $selshipping_box_post_id;
	}

	$box_template_desc = $carthelper->replaceShippingBoxTemplate($box_template_desc, $shipping_box_post_id);
	echo eval("?>" . $box_template_desc . "<?php ");

	$returnarr              = $carthelper->replaceShippingTemplate($template_desc, $this->shipping_rate_id, $shipping_box_post_id, $user->id, $this->users_info_id, $this->ordertotal, $this->order_subtotal);
	$template_desc          = $returnarr['template_desc'];
	$this->shipping_rate_id = $returnarr['shipping_rate_id'];

	echo eval("?>" . $template_desc . "<?php ");
}
else
{
	?>
	<div class="shipnotice"><?php echo JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS'); ?></div>
<?php
}
