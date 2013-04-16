<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
$order_functions = new order_functions();

$is_creditcard = 0;
$paymentmethod = $order_functions->getPaymentMethodInfo();
$is_company = $this->billing->is_company;

$payment_method_id = 0;
if (count($paymentmethod) == 1)
{
	$payment_method_id = $paymentmethod[0]->element;
}?>
<div>
	<?php
	if (SPLITABLE_PAYMENT == 1)
	{
		?>
		<input type="checkbox" name="issplit" value="1"> <?php echo JText::_('COM_REDSHOP_SPLIT_PAYMENT'); ?>?
	<?php
	}    ?>
	<br>
	<?php
	if (count($paymentmethod) > 0)
	{
		for ($p = 0; $p < count($paymentmethod); $p++)
		{
			$paymentparams = new JRegistry($paymentmethod[$p]->params);

			$checked = "";
			if ($payment_method_id == $paymentmethod[$p]->element)
			{
				$checked = "checked";
			}
			$private_person = $paymentparams->get('private_person', '');
			$is_creditcard = $paymentparams->get('is_creditcard', '');
			$business = $paymentparams->get('business', '');
			if ($paymentmethod[$p]->element == 'rs_payment_eantransfer' || $paymentmethod[$p]->element == 'rs_payment_cashtransfer' || $paymentmethod[$p]->element == 'rs_payment_banktransfer' || $paymentmethod[$p]->element == "rs_payment_banktransfer2" || $paymentmethod[$p]->element == "rs_payment_banktransfer3" || $paymentmethod[$p]->element == "rs_payment_banktransfer4" || $paymentmethod[$p]->element == "rs_payment_banktransfer5")
			{
				if ($is_company == 0 && $private_person == 1)
				{
					?>
					<input type="radio" name="payment_method_class"
					       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
					<?php        echo JText::_($paymentmethod[$p]->name) . '<br><br>';
				}
				else
				{
					if ($is_company == 1 && $business == 1)
					{
						?>
						<input type="radio" name="payment_method_class"
						       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
						<?php        echo JText::_($paymentmethod[$p]->name) . '<br><br>';
					}
				}
			}
			else
			{
				?>
				<input type="radio" name="payment_method_class"
				       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
				<?php    echo JText::_($paymentmethod[$p]->name) . '<br><br>';
			}
			if ($is_creditcard == 1)
			{
				$is_creditcard = 1;
			}
		}
	}
	else
	{
		echo JText::_('COM_REDSHOP_NO_PAYMENT_METHOD_TO_DISPLAY');
	}    ?>
</div>
