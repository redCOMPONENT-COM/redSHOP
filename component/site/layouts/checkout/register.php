<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

?>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId); ?>" method="post"
      name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php if (Redshop::getConfig()->get('REGISTER_METHOD') == 2) :
		$checked_style = (Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX') == 1) ? 'checked="checked"' : "''";
		?>
		<div class="checkbox">
			<label>
				<input type="checkbox" name="createaccount" <?php echo $checked_style; ?> id="createaccount"
				       value="1" onclick="createUserAccount(this);"/>
				<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT'); ?>
			</label>
		</div>
	<?php endif; ?>

	<fieldset>
		<legend><?php echo JText::_('COM_REDSHOP_ADDRESS_INFORMATION'); ?></legend>

		<?php echo RedshopHelperBilling::render($post, $isCompany, $lists, Redshop::getConfig()
			->get('OPTIONAL_SHIPPING_ADDRESS'), 1, Redshop::getConfig()->get('CREATE_ACCOUNT_CHECKBOX')); ?>
	</fieldset>

	<?php if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) : ?>

		<?php
		$billingisshipping = "";

		if (count($_POST) > 0)
		{
			if (isset($post['billisship']) && $post['billisship'] == 1)
			{
				$billingisshipping = "style='display:none'";
			}
		}
		elseif (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
		{
			$billingisshipping = "style='display:none'";
		}
		?>

		<div id="divShipping" <?php echo $billingisshipping; ?>>
			<fieldset class="adminform subTable">
				<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
				<?php echo $userhelper->getShippingTable($post, $isCompany, $this->lists); ?>
			</fieldset>
		</div>

	<?php endif; ?>

	<?php echo RedshopLayoutHelper::render('registration.captcha'); ?>

	<div class="btn-group">
		<input type="button" class="btn btn-default btn-lg" name="back"
		       value="<?php echo JText::_('COM_REDSHOP_BACK'); ?>" onclick="javascript:window.history.go(-1);">
		<input type="submit" class="btn btn-primary btn-lg" name="submitbtn" id="submitbtn"
		       value="<?php echo JText::_('COM_REDSHOP_PROCEED'); ?>">
	</div>

	<div class="clr"></div>
	<input type="hidden" name="l" value="0">
	<input type="hidden" name="address_type" value="BT"/>
	<input type="hidden" name="user_id" id="user_id" value="0"/>
	<input type="hidden" name="usertype" value="Registered"/>
	<input type="hidden" name="groups[]" value="2"/>
	<input type="hidden" name="is_company" id="is_company" value="<?php echo $isCompany; ?>"/>
	<input type="hidden" name="shopper_group_id" value="1"/>
	<input type="hidden" name="task" value="checkoutprocess"/>
</form>