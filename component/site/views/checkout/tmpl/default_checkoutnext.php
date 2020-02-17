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

$dispatcher = RedshopHelperUtility::getDispatcher();


/** @var RedshopModelCheckout $model */
$model   = $this->getModel('checkout');
$cart = RedshopHelperCartSession::getCart();


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
?>

<script type="text/javascript">
	function validation() {
		<?php
		if( Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') > 0 && $cart['total'] < Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL'))
		{
		?>
		alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_HAS_TO_BE_MORE_THAN') . ' ' . Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') . '';?>");
		return false;
		<?php
		}	?>

		if (document.getElementById('termscondition')) {
			var termscondition = document.getElementById('termscondition').checked;

			if (!termscondition) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_TEMS_CONDITIONS')?>");
				return false;
			}
		}

		return true;
	}

	function checkout_disable(val) {
		document.adminForm.submit();
		document.getElementById(val).disabled = true;
		var op = document.getElementById(val);
		op.setAttribute("style", "opacity:0.3;");

		if (op.style.setAttribute) //For IE
		{
			op.style.setAttribute("filter", "alpha(opacity=30);");
		}
	}
</script>
