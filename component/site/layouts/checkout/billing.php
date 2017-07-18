<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('redshop_checkout');
$openToStretcher = 0;
$company = "";
$customer = "";

if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
{
	$company = "hide";
	$openToStretcher = 0;
}
elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
{
	$customer = "hide";
	$openToStretcher = 1;
}

$isCompany = $openToStretcher == 1 ? 1 : 0;

$lists['shipping_customer_field'] = RedshopHelperExtrafields::listAllField(14);
$lists['shipping_company_field']  = RedshopHelperExtrafields::listAllField(15);
?>

<div class="form-group">
	<label class="radio-inline <?php echo $customer;?>">
		<input type="radio" name="togglerchecker" class="toggler" onclick="getBillingTemplate(this);" value="0" <?php echo ($isCompany == 0) ? 'checked="checked"' : '' ?> billing_type="private"/>
		<?php echo JText::_('COM_REDSHOP_USER_REGISTRATION');?>
	</label>
	<label class="radio-inline <?php echo $company;?>">
		<input type="radio" name="togglerchecker" class="toggler" onclick="getBillingTemplate(this);" value="1"  <?php echo ($isCompany == 1) ? 'checked="checked"' : '' ?> billing_type="company"/>
		<?php echo JText::_('COM_REDSHOP_COMPANY_REGISTRATION');?>
	</label>
	<?php $dispatcher->trigger('onRenderOnstepCheckout'); ?>
</div>
<div id="wrapper-billing"></div>
<?php if (!JFactory::getUser()->id && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) : ?>
<div class="form-group">
	<label for="billisship">
		<input class="toggler" type="checkbox" id="billisship" name="billisship" value="1" onclick="billingIsShipping(this);" checked="" />
		<?php echo JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING'); ?>
	</label>
</div>
<div id="divShipping" style="display: none">
	<?php echo rsUserHelper::getInstance()->getShippingTable(array(), $isCompany, $lists); ?>
</div>
<?php endif; ?>
