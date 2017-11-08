<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$model = $this->getModel('checkout');

$uri = JURI::getInstance();
$url = $uri->root();

$redhelper = redhelper::getInstance();
$Itemid = RedshopHelperUtility::getCheckoutItemId();

if ($Itemid == 0)
{
	$Itemid = JFactory::getApplication()->input->getInt('Itemid');
}

$session = JFactory::getSession();

$order_functions = order_functions::getInstance();
$extra_field = extra_field::getInstance();

$billingaddresses = $model->billingaddresses();

?>
<table class="admintable">
	<?php
	if ($billingaddresses->is_company == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</label></td>
			<td><?php echo $billingaddresses->company_name;?></td>
		</tr>
	<?php
	}?>
	<tr>
		<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</label></td>
		<td><?php echo $billingaddresses->firstname;?></td>
	</tr>
	<tr>
		<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</label></td>
		<td><?php echo $billingaddresses->lastname;?></td>
	</tr>
	<?php
	if ($billingaddresses->address != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="address"><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</label>
			</td>
			<td><?php echo $billingaddresses->address;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->zipcode != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_ZIP');?>:</label></td>
			<td><?php echo $billingaddresses->zipcode;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->city != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_CITY');?>:</label></td>
			<td><?php echo $billingaddresses->city;?></td>
		</tr>
	<?php
	}

	if (trim($billingaddresses->country_code) != "" && trim($billingaddresses->country_code))
	{
		?>
		<tr>
			<td width="100" align="left"><label for="contact_info"><?php echo JText::_('COM_REDSHOP_COUNTRY');?>
					:</label></td>
			<td><?php echo JText::_($order_functions->getCountryName($billingaddresses->country_code));?></td>
		</tr>
	<?php
	}

	$state = $order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code);

	if ($state != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="address"><?php echo JText::_('COM_REDSHOP_STATE');?>:</label></td>
			<td><?php echo $state;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->phone != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_PHONE');?>:</label></td>
			<td><?php echo $billingaddresses->phone;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->debitor_mobile_phone != 0)
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_MOBILE_PHONE');?>:</label>
			</td>
			<td><?php echo $billingaddresses->debitor_mobile_phone;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->user_email != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_EMAIL');?>:</label></td>
			<td><?php echo $billingaddresses->user_email ? $billingaddresses->user_email : $user->email;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->is_company == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_EAN_NUMBER');?>:</label></td>
			<td><?php echo $billingaddresses->ean_number;?></td>
		</tr>
	<?php
	}

	if ($billingaddresses->is_company == 1 && Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label for="vat_number"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER');?>
					:</label></td>
			<td><?php echo $billingaddresses->vat_number;?></td>
		</tr>
		<?php
		if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
		{
		?>
			<tr>
				<td width="100" align="left"><label for="tax_exempt"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT');?>
						:</label></td>
				<td><?php
					if ($billingaddresses->tax_exempt == 1)
					{
						echo JText::_('COM_REDSHOP_YES');
					}
					else
					{
						echo JText::_('COM_REDSHOP_NO');
					}
					?>
				</td>
			</tr>
		<?php
		}
		?>
	<?php
	}

	if ($billingaddresses->debitor_money_transfer_number > 0)
	{
		?>

		<tr>
			<td width="100" align="left"><label
					for="debitor_money_transfer_number"><?php echo JText::_('COM_REDSHOP_DEBITOR_MONEY_TRANSFER_NUMBER');?>
					:</label></td>
			<td><?php echo $billingaddresses->debitor_money_transfer_number;?></td>
		</tr>

	<?php
	}

	if ($billingaddresses->is_company == 1)
	{
		echo $extrafields = $extra_field->list_all_field_display(8, $billingaddresses->users_info_id);
	}
	else
	{
		echo $extrafields = $extra_field->list_all_field_display(7, $billingaddresses->users_info_id);
	}
	?>
</table>
