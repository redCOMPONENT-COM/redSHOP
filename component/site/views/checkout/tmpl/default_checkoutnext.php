<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.modal');
/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/jquery.validate.min.js', false, true);
$dispatcher = RedshopHelperUtility::getDispatcher();

/** @var RedshopModelCheckout $model */
$model    = $this->getModel('checkout');
$cart     = \Redshop\Cart\Helper::getCart();
$cartData = "";

if (Redshop::getConfig()->get('USE_AS_CATALOG'))
{
	$cartTempData = RedshopHelperTemplate::getTemplate("catalogue_cart");

	if ($cartTempData[0]->template_desc != "")
	{
		$cartData = $cartTempData[0]->template_desc;
	}
}
else
{
	$cartTempData = RedshopHelperTemplate::getTemplate("checkout");

	if ($cartTempData[0]->template_desc != "")
	{
		$cartData = $cartTempData[0]->template_desc;
	}
	else
	{
		$cartData = RedshopHelperTemplate::getDefaultTemplateContent('checkout');
	}
}

// Process the product plugin for cart item
JPluginHelper::importPlugin('redshop_product');
$results = $dispatcher->trigger('onStartCartTemplateReplace', array(& $cartData, $cart));
// End

echo JLayoutHelper::render('cart.wizard', array('step' => '2'));

$cartData = RedshopTagsReplacer::_(
	'checkout',
	$cartData,
	array(
		'usersInfoId' => $this->users_info_id,
		'shippingRateId' => $this->shipping_rate_id,
		'paymentMethodId' => $this->payment_method_id,
		'isCreditcard' => $this->is_creditcard,
		'cart' => $cart
	)
);

echo eval("?>" . $cartData . "<?php ");


