<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$redTemplate      = new Redtemplate;
$carthelper       = new rsCarthelper;
$model            = $this->getModel('checkout');
$payment_template = $redTemplate->getTemplate("redshop_payment");

if (count($payment_template) > 0 && $payment_template[0]->template_desc)
{
	$template_desc = $payment_template[0]->template_desc;
}
else
{
	$template_desc = "<fieldset class=\"adminform\"><legend><strong>{payment_heading}</strong></legend><div>{split_payment}</div>\r\n<div>{payment_loop_start}\r\n<div>{payment_method_name}</div>\r\n<div>{creditcard_information}</div>\r\n{payment_loop_end}</div></fieldset>";
}

// Get billing info for check is_company
$billingaddresses = $model->billingaddresses();
$is_company       = $billingaddresses->is_company;

if ((int) $billingaddresses->ean_number == 0)
{
	$ean_number = 1;
}

$template_desc = $carthelper->replacePaymentTemplate($template_desc, $this->element, $is_company, $ean_number);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
