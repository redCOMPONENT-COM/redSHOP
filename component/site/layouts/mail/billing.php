<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ==========================
 * @var  array   $displayData       Layout data
 * @var  object  $billingAddresses  Billing address
 */
extract($displayData);

$order_functions = order_functions::getInstance();
$extra_field = extra_field::getInstance();
$extra_section = ($billingAddresses->is_company == 1) ?
    RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS : RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS;
?>

<table border="0">
	<?php if ($billingAddresses->is_company == 1) : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</td>
		<td><?php echo $billingAddresses->company_name;?></td>
	</tr>
	<?php endif; ?>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</td>
		<td><?php echo $billingAddresses->firstname;?></td>
	</tr>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</td>
		<td><?php echo $billingAddresses->lastname;?></td>
	</tr>

	<?php if ($billingAddresses->address != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</td>
		<td><?php echo $billingAddresses->address;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->zipcode != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ZIP');?>:</td>
		<td><?php echo $billingAddresses->zipcode;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->city != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CITY');?>:</td>
		<td><?php echo $billingAddresses->city;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->country_code != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COUNTRY');?>:</td>
		<td><?php echo JText::_(RedshopHelperOrder::getCountryName($billingAddresses->country_code));?></td>
	</tr>
	<?php endif; ?>

	<?php $state = RedshopHelperOrder::getStateName($billingAddresses->state_code, $billingAddresses->country_code); ?>
	<?php if ($state != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_STATE');?>:</td>
		<td><?php echo $state;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->phone != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_PHONE');?>:</td>
		<td><?php echo $billingAddresses->phone;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->user_email != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_EMAIL');?>:</td>
		<td><?php echo $billingAddresses->user_email ? $billingAddresses->user_email : $user->email;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($billingAddresses->is_company == 1) : ?>

		<?php if ($billingAddresses->ean_number != "") : ?>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_EAN_NUMBER');?>:</td>
			<td><?php echo $billingAddresses->ean_number;?></td>
		</tr>

		<tr>
			<td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER');?>:</td>
			<td><?php echo $billingAddresses->requisition_number;?></td>
		</tr>
		<?php endif; ?>

		<?php if (USE_TAX_EXEMPT == 1) : ?>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_VAT_NUMBER');?>:</td>
			<td><?php echo $billingAddresses->vat_number;?></td>
		</tr>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT')) : ?>
			<?php
			if ($billingAddresses->tax_exempt == 1)
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
	<?php echo $extrafields = RedshopHelperExtrafields::listAllFieldDisplay($extra_section, $billingAddresses->users_info_id, 0, "", "", true); ?>
</table>
