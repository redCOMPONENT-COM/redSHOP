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

$redTemplate      = Redtemplate::getInstance();
$carthelper       = rsCarthelper::getInstance();
$model            = $this->getModel('checkout');
$payment_template = $redTemplate->getTemplate("redshop_payment");

if (count($payment_template) > 0 && $payment_template[0]->template_desc)
{
	$template_desc = $payment_template[0]->template_desc;
}
else
{
	$template_desc = "<fieldset class=\"adminform\"><legend><strong>{payment_heading}</strong></legend>\r\n<div>{payment_loop_start}\r\n<div>{payment_method_name}</div>\r\n<div>{creditcard_information}</div>\r\n{payment_loop_end}</div></fieldset>";
}

// Get billing info for check is_company
$billingaddresses = $model->billingaddresses();
$is_company       = $billingaddresses->is_company;

$eanNumber 	  = (int) $billingaddresses->ean_number;

$template_desc = $carthelper->replacePaymentTemplate($template_desc, $this->element, $is_company, $eanNumber);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
