<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$order_functions = new order_functions;
$extra_field = new extra_field;
$model = $this->getModel('checkout');
$billingaddresses = $model->billingaddresses();    ?>
<table class="admintable">
	<?php

	if ($billingaddresses->is_company == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</td>
			<td><?php echo $billingaddresses->company_name;?></td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</td>
		<td><?php echo $billingaddresses->firstname;?></td>
	</tr>
	<tr>
		<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</td>
		<td><?php echo $billingaddresses->lastname;?></td>
	</tr>
	<?php
	if ($billingaddresses->address != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</td>
			<td><?php echo $billingaddresses->address;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->zipcode != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_ZIP');?>:</td>
			<td><?php echo $billingaddresses->zipcode;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->city != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_CITY');?>:</td>
			<td><?php echo $billingaddresses->city;?></td>
		</tr>
	<?php
	}

	if (trim($billingaddresses->country_code) != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_COUNTRY');?>:</td>
			<td><?php echo JText::_($order_functions->getCountryName($billingaddresses->country_code));?></td>
		</tr>
	<?php
	}

	$state = $order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code);

	if ($state != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_STATE');?>:</td>
			<td><?php echo $state;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->phone != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_PHONE');?>:</td>
			<td><?php echo $billingaddresses->phone;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->user_email != "")
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_EMAIL');?>:</td>
			<td><?php echo $billingaddresses->user_email ? $billingaddresses->user_email : $user->email;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->is_company == 1)
	{
		if ($billingaddresses->ean_number != "")
		{
			?>
			<tr>
				<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER');?>:</td>
				<td><?php echo $billingaddresses->ean_number;?></td>
			</tr>
			<!-- <tr><td width="100" align="left"><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER' );?>:</td>
		<td><?php echo $billingaddresses->requisition_number;?></td></tr>-->
		<?php
		}
	}

	if ($billingaddresses->is_company == 1 && USE_TAX_EXEMPT == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER');?>:</td>
			<td><?php echo $billingaddresses->vat_number;?></td>
		</tr>
		<?php
		if (SHOW_TAX_EXEMPT_INFRONT)
		{
			if ($billingaddresses->tax_exempt == 1)
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_YES');
			}
			else
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_NO');
			}    ?>
			<tr>
				<td width="100" align="left"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT');?>:</td>
				<td><?php echo $tax_exempt;    ?></td>
			</tr>
		<?php
		}
	}

	if ($billingaddresses->is_company == 1)
	{
		echo $extrafields = $extra_field->list_all_field_display(8, $billingaddresses->users_info_id);
	}
	else
	{
		echo $extrafields = $extra_field->list_all_field_display(7, $billingaddresses->users_info_id);
	}    ?>
</table>
