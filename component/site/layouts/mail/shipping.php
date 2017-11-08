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
$extra_section = ($shippingaddresses->is_company == 1) ? extraField::SECTION_COMPANY_SHIPPING_ADDRESS : extraField::SECTION_PRIVATE_SHIPPING_ADDRESS;
?>

<table border="0">
	<?php if ($shippingaddresses->is_company == 1) : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</td>
		<td><?php echo $shippingaddresses->company_name;?></td>
	</tr>
	<?php endif; ?>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</td>
		<td><?php echo $shippingaddresses->firstname;?></td>
	</tr>

	<tr>
		<td><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</td>
		<td><?php echo $shippingaddresses->lastname;?></td>
	</tr>

	<?php if ($shippingaddresses->address != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</td>
		<td><?php echo $shippingaddresses->address;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($shippingaddresses->zipcode != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_ZIP');?>:</td>
		<td><?php echo $shippingaddresses->zipcode;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($shippingaddresses->city != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_CITY');?>:</td>
		<td><?php echo $shippingaddresses->city;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($shippingaddresses->country_code != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_COUNTRY');?>:</td>
		<td><?php echo JText::_($order_functions->getCountryName($shippingaddresses->country_code));?></td>
	</tr>
	<?php endif; ?>

	<?php $state = $order_functions->getStateName($shippingaddresses->state_code, $shippingaddresses->country_code); ?>
	<?php if ($state != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_STATE');?>:</td>
		<td><?php echo $state;?></td>
	</tr>
	<?php endif; ?>

	<?php if ($shippingaddresses->phone != "") : ?>
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_PHONE');?>:</td>
		<td><?php echo $shippingaddresses->phone;?></td>
	</tr>
	<?php endif; ?>

	<?php echo $extrafields = $extra_field->list_all_field_display($extra_section, $shippingaddresses->users_info_id, 0, "", "", true);
	?>
</table>

