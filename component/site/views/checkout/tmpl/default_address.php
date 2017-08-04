<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$model     = $this->getModel('checkout');
$uri       = JURI::getInstance();
$url       = $uri->root();

$redhelper = redhelper::getInstance();
$Itemid    = RedshopHelperUtility::getCheckoutItemId();
$session   = JFactory::getSession();
$cart      = $session->get('cart');
?>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '') ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name='l' value='0'>
	<?php
	$billingaddresses = $model->billingaddresses();
	$editbill = $url . "index.php?option=com_redshop&view=account_billto&return=checkout&tmpl=component&Itemid=" . $Itemid;        ?>

	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo JText::_('COM_REDSHOP_BILL_TO_INFORMATION');?></h3>
				</div>

				<div class="panel-body">
					<?php
					if ($billingaddresses)
					{
						?>
							<a class="modal btn btn-primary" href="<?php echo $editbill; ?>"
							   rel="{handler: 'iframe', size: {x: 720, y: 470}}"><?php echo JText::_('COM_REDSHOP_EDIT');?></a>
						<?php
						echo $this->loadTemplate('billing');
					}
					else
					{
						?>
						<div class="billnotice"><?php echo JText::_('COM_REDSHOP_FILL_BILLING_ADDRESS');    ?></div>
						<a class="modal" href="<?php echo $editbill; ?>"
						   rel="{handler: 'iframe', size: {x: 800, y: 550}}"> <?php echo JText::_('COM_REDSHOP_ADD');?></a>
					<?php
					}    ?>
				</div>
			</div>
		</div>

		<?php
		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
		?>
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES');?></h3>
				</div>

				<div class="panel-body">
					<?php
					if ($billingaddresses && Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
					{
						$checked = ((!isset($this->users_info_id) || $this->users_info_id == 0) || $this->users_info_id == $billingaddresses->users_info_id) ? 'checked' : '';    ?>

						<div class="radio">
							<label for="users_info_id_default">
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
									       type="radio" name="users_info_id" id="users_info_id_default"
									       value="<?php echo $billingaddresses->users_info_id; ?>"
									<?php echo $checked;?> />
								<?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS');?>
							</label>
						</div>
					<?php
					}

					$shippingaddresses = $model->shippingaddresses();
					$add_addlink = $url . "index.php?option=com_redshop&view=account_shipto&task=addshipping&return=checkout&tmpl=component&is_company=" . $billingaddresses->is_company . "&Itemid=" . $Itemid;

					for ($i = 0, $in = count($shippingaddresses); $i < $in; $i++)
					{
						if ($this->users_info_id != "")
						{
							$checked = ($this->users_info_id == $shippingaddresses [$i]->users_info_id) ? 'checked' : '';
						}

						$edit_addlink   = $url . "index.php?option=com_redshop&view=account_shipto&task=addshipping&return=checkout&tmpl=component&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;
						$delete_addlink = $url . "index.php?option=com_redshop&view=account_shipto&return=checkout&tmpl=component&task=remove&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;    ?>

						<div class="radio">
							<label for="users_info_id_<?php echo $i ?>">
								<input onclick="document.adminForm.task.value = '';document.adminForm.submit();"
									       type="radio" name="users_info_id" id="users_info_id_<?php echo $i;?>"
									       value="<?php echo $shippingaddresses[$i]->users_info_id; ?>"
								<?php echo $checked;?>/>

								<?php if (Redshop::getConfig()->get('ENABLE_ADDRESS_DETAIL_IN_SHIPPING'))
								{
									echo $shippingaddresses [$i]->address . " ";
								}

								echo $shippingaddresses[$i]->text; ?>
								<a class="modal" href="<?php echo $edit_addlink; ?>"
								   rel="{handler: 'iframe', size: {x: 570, y: 470}}">(<?php echo JText::_('COM_REDSHOP_EDIT_LBL');    ?>
									)</a>
								<a href="<?php echo $delete_addlink; ?>"
								   title="">(<?php echo JText::_('COM_REDSHOP_DELETE_LBL');    ?>)</a>
							</label>
						</div>
					<?php } ?>

					<a class="modal btn btn-primary" href="<?php echo $add_addlink; ?>" rel="{handler: 'iframe', size: {x: 570, y: 470}}">
					 	<?php echo JText::_('COM_REDSHOP_ADD_ADDRESS');?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>

	<?php
	if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE') && $cart['free_shipping'] != 1)
	{
		echo $this->loadTemplate('shipping');
	}
	?>

	<br/>

	<div id="paymentblock"><?php echo $this->loadTemplate('payment');?></div>
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="order_id" value="<?php echo JFactory::getApplication()->input->getInt('order_id'); ?>"/>
	<input type="hidden" name="task" value="checkoutnext"/>
	<input type="hidden" name="view" value="checkout"/>

	<div align="right"><input type="submit" class="greenbutton btn btn-primary" name="checkoutnext"
	                          value="<?php echo JText::_("COM_REDSHOP_CHECKOUT") ?>"/></div>
</form>
