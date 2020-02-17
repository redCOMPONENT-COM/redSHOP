<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.creditcard.min.js', false, true);
/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.onestep.min.js', false, true);
/** @var RedshopModelCheckout $model */
$model = $this->getModel('checkout');


$oneStepTemplate     = RedshopHelperTemplate::getTemplate("onestep_checkout");

if (count($oneStepTemplate) > 0 && $oneStepTemplate[0]->template_desc)
{
	$oneStepTemplateHtml = $oneStepTemplate[0]->template_desc;
}
else
{
	$oneStepTemplateHtml = JText::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS");
}

echo RedshopTagsReplacer::_(
	'onestepcheckout',
	$oneStepTemplateHtml,
	array(
		'users_info_id' => $this->users_info_id,
		'shippingAddresses' => $model->shippingaddresses(),
		'billingAddress' => $model->billingaddresses()
	)
);
