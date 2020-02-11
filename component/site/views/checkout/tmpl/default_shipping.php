<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$redTemplate = Redtemplate::getInstance();
$carthelper = rsCarthelper::getInstance();

$user   = JFactory::getUser();
$jinput = JFactory::getApplication()->input;
$post   = $jinput->getArray($_POST);

$shippingboxTemplate = RedshopHelperTemplate::getTemplate("shippingbox");

if (count($shippingboxTemplate) > 0 && $shippingboxTemplate[0]->template_desc)
{
	$boxTemplateDesc = $shippingboxTemplate[0]->template_desc;
}
else
{
	$boxTemplateDesc = "<fieldset class=\"adminform\"> <legend><strong>{shipping_box_heading}</strong></legend>\r\n<div>{shipping_box_list}</div>\r\n</fieldset>";
}

$shippingTemplate = RedshopHelperTemplate::getTemplate("redshop_shipping");

if (count($shippingTemplate) > 0 && $shippingTemplate[0]->template_desc)
{
	$templateDesc = $shippingTemplate[0]->template_desc;
}
else
{
	$templateDesc = "<fieldset class=\"adminform\"><legend><strong>{shipping_heading}</strong></legend>\r\n<div>{shipping_method_loop_start}\r\n<h3>{shipping_method_title}</h3>\r\n<div>{shipping_rate_loop_start}\r\n<div>{shipping_rate_name} {shipping_rate}</div>\r\n{shipping_rate_loop_end}</div>\r\n{shipping_method_loop_end}</div>\r\n</fieldset>";
}

if ($this->users_info_id > 0)
{
	$shippingHelper          = shipping::getInstance();
	$shippingBoxes           = RedshopHelperShipping::getShippingBox();
	$selShippingBoxPostId = 0;

	if (count($shippingBoxes) > 0)
	{
		$selShippingBoxPostId = $shippingBoxes[0]->shipping_box_id;
	}

	if (isset($post['shipping_box_id']))
	{
		$shippingBoxPostId = $post['shipping_box_id'];
	}
	else
	{
		$shippingBoxPostId = $selShippingBoxPostId;
	}

	$boxTemplateDesc = RedshopTagsReplacer::_(
		'shippingbox',
		$boxTemplateDesc,
		array(
			'shippingBoxPostId' => $shippingBoxPostId
		)
	);

	echo eval("?>" . $boxTemplateDesc . "<?php ");

	$returnArr              = $carthelper->replaceShippingTemplate($templateDesc, $this->shipping_rate_id, $shippingBoxPostId, $user->id, $this->users_info_id, $this->ordertotal, $this->order_subtotal);
	$templateDesc          = $returnArr['template_desc'];
	$this->shipping_rate_id = $returnArr['shipping_rate_id'];

	echo eval("?>" . $templateDesc . "<?php ");
}
else
{
	?>
	<div class="shipnotice"><?php echo JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS'); ?></div>
	<?php
}
