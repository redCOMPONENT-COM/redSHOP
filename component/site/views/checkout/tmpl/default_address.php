<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$option = JRequest::getCmd('option', 'redshop');
$model = $this->getModel('checkout');
$uri = JURI::getInstance();
$url = $uri->root();

$redhelper = new redhelper;
$Itemid = $redhelper->getCheckoutItemid();
$session = JFactory::getSession();
$cart = $session->get('cart');
?>

<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=checkout&Itemid=' . $Itemid . '') ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name='l' value='0'>
	<?php
	$billingaddresses = $model->billingaddresses();
	$editbill = $url . "index.php?option=" . $option . "&view=account_billto&return=checkout&tmpl=component&for=true&Itemid=" . $Itemid;        ?>

	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_BILL_TO_INFORMATION');?></legend>
		<?php
		if ($billingaddresses)
		{
			// Dont allow to edit if redCRM Contact Person is logged in
			if (!$session->get('isredcrmuser'))
			{
				?>
				<a class="modal" href="<?php echo $editbill; ?>"
				   rel="{handler: 'iframe', size: {x: 720, y: 470}}"><?php echo JText::_('COM_REDSHOP_EDIT');?></a>
			<?php
			}

			// Billing or Shipping address
			if ($redhelper->isredCRM())
			{
				echo $this->loadTemplate('billing_crm');
			}
			else
			{
				echo $this->loadTemplate('billing');
			}
		}
		else
		{
			?>
			<div class="billnotice"><?php echo JText::_('COM_REDSHOP_FILL_BILLING_ADDRESS');    ?></div>
			<a class="modal" href="<?php echo $editbill; ?>"
			   rel="{handler: 'iframe', size: {x: 800, y: 550}}"> <?php echo JText::_('COM_REDSHOP_ADD');?></a>
		<?php
		}    ?>
	</fieldset>
	<?php
	if (SHIPPING_METHOD_ENABLE)
	{
		?>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES');?></legend>
			<table cellpadding="3" cellspacing="0" border="0" width="100%">
				<?php
				if ($billingaddresses && OPTIONAL_SHIPPING_ADDRESS)
				{
					$checked = ((!isset($this->users_info_id) || $this->users_info_id == 0) || $this->users_info_id == $billingaddresses->users_info_id) ? 'checked' : '';    ?>
					<tr>
						<td>
							<?php
							if (!$session->get('isredcrmuser'))
							{
								?>
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
								       type="radio" name="users_info_id" id="users_info_id_default"
								       value="<?php echo $billingaddresses->users_info_id; ?>"
								<?php echo "checked";?> />
							<?php
							}
							else
							{
								?>
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
								       type="radio" name="users_info_id" id="users_info_id_default"
								       value="<?php echo $billingaddresses->users_info_id; ?>"
								<?php echo $checked;?> />
							<?php
							}
							?>
							- <?php echo '<label for="users_info_id_default">' . JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS') . '</label>';?></td>
					</tr>
				<?php
				}

				$shippingaddresses = $model->shippingaddresses();
				$add_addlink = $url . "index.php?option=" . $option . "&view=account_shipto&task=addshipping&return=checkout&tmpl=component&for=true&is_company=" . $billingaddresses->is_company . "&Itemid=" . $Itemid;

				for ($i = 0; $i < count($shippingaddresses); $i++)
				{
					if ($this->users_info_id != "")
					{
						$checked = ($this->users_info_id == $shippingaddresses [$i]->users_info_id) ? 'checked' : '';
					}

					$edit_addlink   = $url . "index.php?option=" . $option . "&view=account_shipto&task=addshipping&return=checkout&tmpl=component&for=true&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;
					$delete_addlink = $url . "index.php?option=" . $option . "&view=account_shipto&return=checkout&tmpl=component&task=remove&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;    ?>
					<tr>
						<td>
							<?php
							if (!$session->get('isredcrmuser'))
							{
								?>
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
								       type="radio" name="users_info_id" id="users_info_id_<?php echo $i;?>"
								       value="<?php echo $shippingaddresses[$i]->users_info_id; ?>"
								<?php echo $checked;?> />
							<?php
							}
							else
							{
								?>
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
								       type="radio" name="users_info_id" id="users_info_id_<?php echo $i;?>"
								       value="<?php echo $shippingaddresses[$i]->users_info_id; ?>"
								<?php echo $checked;?>/>

							<?php
							}

							if (ENABLE_ADDRESS_DETAIL_IN_SHIPPING)
							{
								echo $shippingaddresses [$i]->address . " ";
							}

							echo '<label for="users_info_id_' . $i . '">' . $shippingaddresses[$i]->text . '</label>';

							if (!$session->get('isredcrmuser'))
							{
								?>
								<a class="modal" href="<?php echo $edit_addlink; ?>"
								   rel="{handler: 'iframe', size: {x: 570, y: 470}}">(<?php echo JText::_('COM_REDSHOP_EDIT_LBL');    ?>
									)</a>
								<a href="<?php echo $delete_addlink; ?>"
								   title="">(<?php echo JText::_('COM_REDSHOP_DELETE_LBL');    ?>)</a>
							<?php
							}
							?>
						</td>
					</tr>
				<?php
				}    ?>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php
				if (!$session->get('isredcrmuser'))
				{
					?>
					<tr>
						<td><a class="modal" href="<?php echo $add_addlink; ?>"
						       rel="{handler: 'iframe', size: {x: 570, y: 470}}"><?php echo JText::_('COM_REDSHOP_ADD_ADDRESS');?> </a>
						</td>
					</tr>
				<?php
				}    ?>
			</table>
		</fieldset>
		<br/>
		<div><?php
			if ($cart['free_shipping'] != 1)
			{
				echo $this->loadTemplate('shipping');
			}
			?></div>
	<?php
	} ?>
	<br/>

	<div id="paymentblock"><?php echo $this->loadTemplate('payment');?></div>
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="order_id" value="<?php echo JRequest::getVar('order_id'); ?>"/>
	<input type="hidden" name="task" value="checkoutnext"/>
	<input type="hidden" name="view" value="checkout"/>

	<div align="right"><input type="submit" class="greenbutton" name="checkoutnext"
	                          value="<?php echo JText::_("COM_REDSHOP_CHECKOUT") ?>"/></div>
</form>
