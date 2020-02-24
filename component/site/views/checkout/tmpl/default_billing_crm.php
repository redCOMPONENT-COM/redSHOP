<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$model = $this->getModel('checkout');

$uri = JURI::getInstance();
$url = $uri->root();

$Itemid = RedshopHelperRouter::getCheckoutItemId();

if ($Itemid == 0)
{
	$Itemid = JFactory::getApplication()->input->getInt('Itemid');
}

$session = JFactory::getSession();

$order_functions = order_functions::getInstance();
$extra_field = extra_field::getInstance();

$billingAddresses = $model->billingaddresses();

?>
<table class="admintable">
	<?php
	if ($billingAddresses->is_company == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_COMPANY_NAME');?>:</label></td>
			<td><?php echo $billingAddresses->company_name;?></td>
		</tr>
	<?php
	}?>
	<tr>
		<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_FIRSTNAME');?>:</label></td>
		<td><?php echo $billingAddresses->firstname;?></td>
	</tr>
	<tr>
		<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_LASTNAME');?>:</label></td>
		<td><?php echo $billingAddresses->lastname;?></td>
	</tr>
	<?php
	if ($billingAddresses->address != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="address"><?php echo JText::_('COM_REDSHOP_ADDRESS');?>:</label>
			</td>
			<td><?php echo $billingAddresses->address;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->zipcode != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_ZIP');?>:</label></td>
			<td><?php echo $billingAddresses->zipcode;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->city != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_CITY');?>:</label></td>
			<td><?php echo $billingAddresses->city;?></td>
		</tr>
	<?php
	}

	if (trim($billingAddresses->country_code) != "" && trim($billingAddresses->country_code))
	{
		?>
		<tr>
			<td width="100" align="left"><label for="contact_info"><?php echo JText::_('COM_REDSHOP_COUNTRY');?>
					:</label></td>
			<td><?php echo JText::_(RedshopHelperOrder::getCountryName($billingAddresses->country_code));?></td>
		</tr>
	<?php
	}

	$state = RedshopHelperOrder::getStateName($billingAddresses->state_code, $billingAddresses->country_code);

	if ($state != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="address"><?php echo JText::_('COM_REDSHOP_STATE');?>:</label></td>
			<td><?php echo $state;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->phone != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_PHONE');?>:</label></td>
			<td><?php echo $billingAddresses->phone;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->debitor_mobile_phone != 0)
	{
		?>
		<tr>
			<td width="100" align="left"><label for="city"><?php echo JText::_('COM_REDSHOP_MOBILE_PHONE');?>:</label>
			</td>
			<td><?php echo $billingAddresses->debitor_mobile_phone;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->user_email != "")
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_EMAIL');?>:</label></td>
			<td><?php echo $billingAddresses->user_email ? $billingAddresses->user_email : $user->email;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->is_company == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label><?php echo JText::_('COM_REDSHOP_EAN_NUMBER');?>:</label></td>
			<td><?php echo $billingAddresses->ean_number;?></td>
		</tr>
	<?php
	}

	if ($billingAddresses->is_company == 1 && Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
	{
		?>
		<tr>
			<td width="100" align="left"><label for="vat_number"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER');?>
					:</label></td>
			<td><?php echo $billingAddresses->vat_number;?></td>
		</tr>
		<?php
		if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
		{
		?>
			<tr>
				<td width="100" align="left"><label for="tax_exempt"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT');?>
						:</label></td>
				<td><?php
					if ($billingAddresses->tax_exempt == 1)
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

	if ($billingAddresses->debitor_money_transfer_number > 0)
	{
		?>

		<tr>
			<td width="100" align="left"><label
					for="debitor_money_transfer_number"><?php echo JText::_('COM_REDSHOP_DEBITOR_MONEY_TRANSFER_NUMBER');?>
					:</label></td>
			<td><?php echo $billingAddresses->debitor_money_transfer_number;?></td>
		</tr>

	<?php
	}

	if ($billingAddresses->is_company == 1)
	{
		echo $extrafields = RedshopHelperExtrafields::listAllFieldDisplay(8, $billingAddresses->users_info_id);
	}
	else
	{
		echo $extrafields = RedshopHelperExtrafields::listAllFieldDisplay(7, $billingAddresses->users_info_id);
	}
	?>
</table>
