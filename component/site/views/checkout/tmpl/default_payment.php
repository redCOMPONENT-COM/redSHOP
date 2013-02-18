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

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$redTemplate = new Redtemplate();
$carthelper 	= new rsCarthelper();
$model = $this->getModel('checkout');
$payment_template = $redTemplate->getTemplate ("redshop_payment" );
if(count($payment_template)>0 && $payment_template[0]->template_desc)
{
	$template_desc = $payment_template[0]->template_desc;
} else {
	$template_desc = "<fieldset class=\"adminform\"><legend><strong>{payment_heading}</strong></legend><div>{split_payment}</div>\r\n<div>{payment_loop_start}\r\n<div>{payment_method_name}</div>\r\n<div>{creditcard_information}</div>\r\n{payment_loop_end}</div></fieldset>";
}

// get billing info for check is_company
$billingaddresses = $model->billingaddresses();
$is_company = $billingaddresses->is_company;
if($billingaddresses->ean_number!="")
{
	$ean_number = 1;
}
$template_desc = $carthelper->replacePaymentTemplate($template_desc,$this->element,$is_company,$ean_number);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>".$template_desc."<?php ");
?>