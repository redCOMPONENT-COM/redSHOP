<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.creditcard.min.js', false, true);
/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.onestep.min.js', false, true);

JPluginHelper::importPlugin('redshop_shipping');
$dispatcher = RedshopHelperUtility::getDispatcher();
$dispatcher->trigger('onRenderCustomField');
$url  = JUri::base();
$user = JFactory::getUser();
$app  = JFactory::getApplication();
$session = JFactory::getSession();
$auth    = $session->get('auth');
$carthelper      = rsCarthelper::getInstance();
$itemId = RedshopHelperRouter::getCheckoutItemId();

/** @var RedshopModelCheckout $model */
$model = $this->getModel('checkout');

$cart  = RedshopHelperCartSession::getCart();
$billingAddresses = $model->billingaddresses();

if ($billingAddresses == new stdClass)
{
	$billingAddresses = null;
}

$paymentMethods          = RedshopHelperUtility::getPlugins('redshop_payment');
$selectedPaymentMethodId = 0;

if (count($paymentMethods) > 0)
{
	$selectedPaymentMethodId = $paymentMethods[0]->element;
}

$shippingBoxes         = RedshopHelperShipping::getShippingBox();
$selectedShippingBoxId = 0;

if (count($shippingBoxes) > 0)
{
	$selectedShippingBoxId = $shippingBoxes[0]->shipping_box_id;
}

$usersInfoId       = $app->input->getInt('users_info_id', $this->users_info_id);
$paymentMethodId   = $app->input->getCmd('payment_method_id', $selectedPaymentMethodId);
$shippingBoxPostId = $app->input->getInt('shipping_box_id', $selectedShippingBoxId);
$shippingRateId    = $app->input->getInt('shipping_rate_id', 0);

if ($usersInfoId == 0 && !empty($billingAddresses) && !empty($billingAddresses->users_info_id))
{
	$usersInfoId = $billingAddresses->users_info_id;
}

$loginTemplate = "";
$input = JFactory::getApplication()->input;

if (!$usersInfoId && Redshop::getConfig()->getInt('REGISTER_METHOD') != 1
	&& Redshop::getConfig()->getInt('REGISTER_METHOD') != 3)
{
	$loginTemplate = RedshopLayoutHelper::render(
		'checkout.login',
		null,
		'',
		array(
			'component' => 'com_redshop'
		)
	);
}

$oneStepTemplateHtml = "";
$oneStepTemplate     = RedshopHelperTemplate::getTemplate("onestep_checkout");

if (count($oneStepTemplate) > 0 && $oneStepTemplate[0]->template_desc)
{
	$oneStepTemplateHtml = "<div id='divOnestepCheckout'>" . $oneStepTemplate[0]->template_desc . "</div>";
}
else
{
	$oneStepTemplateHtml = JText::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS");
}

if (strpos($oneStepTemplateHtml, '{billing_address_information_lbl}') !== false)
{
	$oneStepTemplateHtml = str_replace(
		"{billing_address_information_lbl}",
		JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'),
		$oneStepTemplateHtml
	);
}

$paymentTemplate     = "";
$paymentTemplateHtml = "";
$templates           = RedshopHelperTemplate::getTemplate("redshop_payment");

foreach ($templates as $template)
{
	if (strpos($oneStepTemplateHtml, "{payment_template:" . $template->name . "}") === false)
	{
		continue;
	}

	$paymentTemplate     = "{payment_template:" . $template->name . "}";
	$paymentTemplateHtml = $template->template_desc;
	$oneStepTemplateHtml = str_replace($paymentTemplate, "<div id='divPaymentMethod'>" . $paymentTemplate . "</div>", $oneStepTemplateHtml);
}

$templates = RedshopHelperTemplate::getTemplate("checkout");

foreach ($templates as $template)
{
	if (strpos($oneStepTemplateHtml, "{checkout_template:" . $template->name . "}") === false)
	{
		continue;
	}

	$cartTemplate        = "{checkout_template:" . $template->name . "}";
	$oneStepTemplateHtml = str_replace(
		$cartTemplate,
		'<div id="divRedshopCart">' . $cartTemplate . '</div>'
		. '<div id="divRedshopCartTemplateId" style="display:none">' . $template->id . '</div>',
		$oneStepTemplateHtml
	);
	$oneStepTemplateHtml = str_replace($cartTemplate, $template->template_desc, $oneStepTemplateHtml);
}

// For shipping template
$shippingBoxTemplate     = "";
$shippingBoxTemplateHtml = "";
$shippingTemplate        = "";
$shippingTemplateHtml    = "";
$templates = RedshopHelperTemplate::getTemplate("shippingbox");

foreach ($templates as $template)
{
	if (strpos($oneStepTemplateHtml, "{shippingbox_template:" . $template->name . "}") === false)
	{
		continue;
	}

	$shippingBoxTemplate     = "{shippingbox_template:" . $template->name . "}";
	$shippingBoxTemplateHtml = $template->template_desc;
}
$templates = RedshopHelperTemplate::getTemplate("redshop_shipping");

foreach ($templates as $template)
{
	if (strpos($oneStepTemplateHtml, "{shipping_template:" . $template->name . "}") === false)
	{
		continue;
	}
	$shippingTemplate     = "{shipping_template:" . $template->name . "}";
	$shippingTemplateHtml = $template->template_desc;
	$oneStepTemplateHtml  = str_replace(
		$shippingTemplate,
		'<div id="divShippingRate">' . $shippingTemplate . '</div>'
		. '<div id="divShippingRateTemplateId" style="display:none">' . $template->id . '</div>',
		$oneStepTemplateHtml
	);
}

if (Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
{
	$orderTotal    = $cart['total'];
	$totalDiscount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
	$orderSubTotal = Redshop::getConfig()->getString('SHIPPING_AFTER') == 'total' ?
		$cart['product_subtotal'] - $totalDiscount : $cart['product_subtotal'];
	$shippingBoxTemplateHtml = $carthelper->replaceShippingBoxTemplate($shippingBoxTemplateHtml, $shippingBoxPostId);
	$oneStepTemplateHtml     = str_replace($shippingBoxTemplate, $shippingBoxTemplateHtml, $oneStepTemplateHtml);
	$return = $carthelper->replaceShippingTemplate(
		$shippingTemplateHtml,
		$shippingRateId,
		$shippingBoxPostId,
		$user->id,
		$usersInfoId,
		$orderTotal,
		$orderSubTotal
	);
	$shippingTemplateHtml = $return['template_desc'];
	$shippingRateId       = $return['shipping_rate_id'];
	if ($shippingRateId)
	{
		$shippingList         = $model->calculateShipping($shippingRateId);
		$cart['shipping']     = $shippingList['order_shipping_rate'];
		$cart['shipping_vat'] = $shippingList['shipping_vat'];
		$cart                 = $carthelper->modifyDiscount($cart);
	}
	$oneStepTemplateHtml = str_replace($shippingTemplate, $shippingTemplateHtml, $oneStepTemplateHtml);
}
else
{
	$oneStepTemplateHtml = str_replace($shippingBoxTemplate, "", $oneStepTemplateHtml);
	$oneStepTemplateHtml = str_replace($shippingTemplate, "", $oneStepTemplateHtml);
}

$eanNumber = 0;

if (!empty($billingAddresses) && !empty($billingAddresses->ean_number))
{
	$eanNumber = 1;
}

if (strpos($oneStepTemplateHtml, '{billing_template}') === false)
{
	$oneStepTemplateHtml = str_replace('{billing_address}', '{billing_address}{billing_template}', $oneStepTemplateHtml);
}

if ($usersInfoId)
{
	$oneStepTemplateHtml = RedshopHelperBillingTag::replaceBillingAddress($oneStepTemplateHtml, $billingAddresses);
	$billingTemplate     = '';
}
else
{
	$oneStepTemplateHtml = str_replace("{billing_address}", "", $oneStepTemplateHtml);
	$billingTemplate     = RedshopLayoutHelper::render(
		'checkout.onestep.billing',
		array(),
		'',
		array(
			'component' => 'com_redshop'
		)
	);
}

$oneStepTemplateHtml = str_replace('{billing_template}', $billingTemplate, $oneStepTemplateHtml);

if (strpos($oneStepTemplateHtml, "{edit_billing_address}") !== false && $usersInfoId)
{
	$editBillingLink     = JRoute::_('index.php?option=com_redshop&view=account_billto&tmpl=component&return=checkout&setexit=1&Itemid=' . $itemId);
	$editBilling         = '<a class="modal btn btn-primary" href="' . $editBillingLink . '"'
		. 'rel="{handler: \'iframe\', size: {x: 800, y: 550}}"> ' . JText::_('COM_REDSHOP_EDIT') . '</a>';
	$oneStepTemplateHtml = str_replace("{edit_billing_address}", $editBilling, $oneStepTemplateHtml);
}
else
{
	$oneStepTemplateHtml = str_replace("{edit_billing_address}", "", $oneStepTemplateHtml);
}

$isCompany = isset($billingAddresses->is_company) ? $billingAddresses->is_company : 0;

if (strpos($oneStepTemplateHtml, "{shipping_address}") !== false)
{
	if (Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
	{
		$shippingHtml = '';

		if ($usersInfoId)
		{
			$shippingAddresses = $model->shippingaddresses();

			if ($billingAddresses)
			{
				$shippingCheck = $usersInfoId == $billingAddresses->users_info_id ? 'checked="checked"' : '';
				$shippingHtml  .= '<div class="radio"><label class="radio">'
					. '<input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');"'
					. ' name="users_info_id" value="' . $billingAddresses->users_info_id . '" ' . $shippingCheck . ' />'
					. JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS') . '</label></div>';
			}

			foreach ($shippingAddresses as $shippingAddress)
			{
				$addShippingLink = JRoute::_(
					'index.php?option=com_redshop&view=account_shipto&tmpl=component&task=addshipping'
					. '&return=checkout&Itemid=' . $itemId . '&infoid=' . $shippingAddress->users_info_id,
					false
				);
				$removeShippingLink = JRoute::_($url .
					'index.php?option=com_redshop&view=account_shipto&return=checkout'
					. '&tmpl=component&task=remove&infoid=' . $shippingAddress->users_info_id . '&Itemid=' . $itemId,
					false
				);
				$shippingCheck = $usersInfoId == $shippingAddress->users_info_id ? 'checked="checked"' : '';
				$shippingHtml .= '<div class="radio"><label class="radio inline">'
					. '<input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');"'
					. ' name="users_info_id" value="' . $shippingAddress->users_info_id . '" ' . $shippingCheck . ' />'
					. $shippingAddress->firstname . " " . $shippingAddress->lastname . '</label>'
					. '<a class="modal" href="' . $addShippingLink . '" '
					. 'rel="{handler: \'iframe\', size: {x: 570, y: 470}}">'
					. '(' . JText::_('COM_REDSHOP_EDIT_LBL') . ')</a> '
					. '<a href="' . $removeShippingLink . '" title="">'
					. '(' . JText::_('COM_REDSHOP_DELETE_LBL') . ')</a></div>';
			}

			$addLink = JRoute::_(
				'index.php?option=com_redshop&view=account_shipto&tmpl=component&task=addshipping'
				. '&return=checkout&Itemid=' . $itemId . '&infoid=0&is_company=' . $billingAddresses->is_company,
				false
			);

			$shippingHtml .= '<a class="modal btn btn-primary" href="' . $addLink . '" '
				. 'rel="{handler: \'iframe\', size: {x: 570, y: 470}}">'
				. JText::_('COM_REDSHOP_ADD_ADDRESS') . '</a>';
		}
		else
		{
      			$lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS);
			$lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS);
      
			$shippingHtml = '<div class="form-group"><label for="billisship">'
				. '<input class="toggler" type="checkbox" id="billisship" name="billisship" value="1" '
				. 'onclick="billingIsShipping(this);" checked="" />'
				. JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING') . '</label></div>'
				. '<div id="divShipping" style="display: none"><fieldset class="subTable">'
				. RedshopHelperShipping::getShippingTable(array(), $isCompany, $lists)
				. '</fieldset></div>';
		}

		$oneStepTemplateHtml = str_replace('{shipping_address}', $shippingHtml, $oneStepTemplateHtml);
		$oneStepTemplateHtml = str_replace(
			'{shipping_address_information_lbl}',
			JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL'),
			$oneStepTemplateHtml
		);
	}
	else
	{
		$oneStepTemplateHtml = str_replace('{shipping_address}', '', $oneStepTemplateHtml);
		$oneStepTemplateHtml = str_replace('{shipping_address_information_lbl}', '', $oneStepTemplateHtml);
	}
}
JPluginHelper::importPlugin('redshop_checkout');
$dispatcher->trigger('onRenderInvoiceOneStepCheckout', array(&$oneStepTemplateHtml));

if ($usersInfoId && !empty($billingAddresses))
{
	$paymentTemplateHtml = $carthelper->replacePaymentTemplate(
		$paymentTemplateHtml, $paymentMethodId, $isCompany, $eanNumber
	);
	$oneStepTemplateHtml = str_replace($paymentTemplate, $paymentTemplateHtml, $oneStepTemplateHtml);
}
else
{
	$oneStepTemplateHtml = str_replace($paymentTemplate, "", $oneStepTemplateHtml);
}

$oneStepTemplateHtml = $model->displayShoppingCart($oneStepTemplateHtml, $usersInfoId, $shippingRateId, $paymentMethodId, $itemId);
$oneStepTemplateHtml = RedshopHelperTemplate::parseRedshopPlugin($oneStepTemplateHtml);
?>

<?php if (!$user->id && empty($auth['users_info_id']) && Redshop::getConfig()->getInt('REGISTER_METHOD') != 1 && Redshop::getConfig()->getInt('REGISTER_METHOD') != 3): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo JText::_('COM_REDSHOP_RETURNING_CUSTOMERS') ?></h3>
        </div>
        <div class="panel-body">
			<?php echo $loginTemplate ?>
        </div>
    </div>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout', false) ?>" method="post"
      name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">
	<?php echo $oneStepTemplateHtml ?>
    <div style="display:none" id="responceonestep"></div>
</form>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("input[name='togglerchecker']").each(function (idx, el) {
                if ($(el).is(':checked')) {
                    getBillingTemplate($(el));
                }
            });

            if ($('#createaccount') && $('#createaccount').is(':checked')) {
                $('#onestep-createaccount-wrapper').css('display', 'block');
            }

            var settings = $('#adminForm').validate().settings;

            // Modify validation settings
            $.extend(true, settings, {
                rules: {
                    payment_method_id: {
                        required: function () {
                            if ($("#adminForm [name='payment_method_id']") && !$("#adminForm [name='payment_method_id']").is(':checked')) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    payment_method_id: "<?php echo JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD') ?>"
                },
                errorPlacement: function(error, element) {
                    if ((element.is(":radio") && element.attr('name') == "payment_method_id")) {
                        error.appendTo( element.parents('#divPaymentMethod') );
                    }
                    else if(element.is(":checkbox") && element.attr('name') == "termscondition") {
                        error.appendTo( element.closest('.checkbox') );
                    } else { // This is the default behavior
                        error.insertAfter( element );
                    }
                }
            });
        });
    })(jQuery);
</script>

<script type="text/javascript">
    function validation() {

		<?php if (Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL') > 0
	&& $cart['total'] < Redshop::getConfig()->get('MINIMUM_ORDER_TOTAL')): ?>

        alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_HAS_TO_BE_MORE_THAN');?>");

        return false;
		<?php endif    ?>

        return true;
    }

    function checkout_disable(val) {
        if (jQuery('#adminForm').valid()) {
            document.adminForm.submit();
            document.getElementById(val).disabled = true;
            var op = document.getElementById(val);
            op.setAttribute("style", "opacity:0.3;");

            if (op.style.setAttribute) //For IE
                op.style.setAttribute("filter", "alpha(opacity=30);");
        }
    }
</script>
