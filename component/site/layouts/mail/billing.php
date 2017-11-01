<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$order_functions = order_functions::getInstance();
$extra_field = extra_field::getInstance();
$extra_section = ($billingaddresses->is_company == 1) ? extraField::SECTION_COMPANY_BILLING_ADDRESS : extraField::SECTION_PRIVATE_BILLING_ADDRESS;
?>

<table border="0">
	<?php if ($billingaddresses->is_company == 1) : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</td>
		<td><?php echo $billingaddresses->company_name;?></td>
	</tr>
	<?php endif; ?>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</td>
		<td><?php echo $billingaddresses->firstname;?></td>
	</tr>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</td>
		<td><?php echo $billingaddresses->lastname;?></td>
	</tr>

	<?php if ($billingaddresses->address != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</td>
		<td><?php echo $billingaddresses->address;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->zipcode != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ZIP');?>:</td>
		<td><?php echo $billingaddresses->zipcode;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->city != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CITY');?>:</td>
		<td><?php echo $billingaddresses->city;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->country_code != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COUNTRY');?>:</td>
		<td><?php echo JText::_($order_functions->getCountryName($billingaddresses->country_code));?></td>
	</tr>
	<?php endif; ?>

	<?php $state = $order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code); ?>
	<?php if ($state != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_STATE');?>:</td>
		<td><?php echo $state;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->phone != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_PHONE');?>:</td>
		<td><?php echo $billingaddresses->phone;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->user_email != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_EMAIL');?>:</td>
		<td><?php echo $billingaddresses->user_email ? $billingaddresses->user_email : $user->email;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingaddresses->is_company == 1) : ?>

		<?php if ($billingaddresses->ean_number != "") : ?>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_EAN_NUMBER');?>:</td>
			<td><?php echo $billingaddresses->ean_number;?></td>
		</tr>

		<tr>
			<td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER');?>:</td>
			<td><?php echo $billingaddresses->requisition_number;?></td>
		</tr>
		<?php endif; ?>

		<?php if (USE_TAX_EXEMPT == 1) : ?>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_VAT_NUMBER');?>:</td>
			<td><?php echo $billingaddresses->vat_number;?></td>
		</tr>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT')) : ?>
			<?php
			if ($billingaddresses->tax_exempt == 1)
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_YES');
			}
			else
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_NO');
			}
			?>

		<tr>
			<td><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT');?>:</td>
			<td><?php echo $tax_exempt;?></td>
		</tr>

		<?php endif; ?>

	<?php endif; ?>
	<?php echo $extrafields = $extra_field->list_all_field_display($extra_section, $billingaddresses->users_info_id, 0, "", "", true); ?>
</table>
