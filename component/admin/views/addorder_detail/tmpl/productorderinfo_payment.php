<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$order_functions = order_functions::getInstance();

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
	if (count($paymentmethod) > 0)
	{
		for ($p = 0, $pn = count($paymentmethod); $p < $pn; $p++)
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

			// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
			$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentmethod[$p]->element);

			if ($paymentmethod[$p]->element == 'rs_payment_eantransfer' || $isBankTransferPaymentType)
			{
				if ($is_company == 0 && $private_person == 1)
				{
					?>
					<label><input type="radio" name="payment_method_class"
					       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
					<?php        echo JText::_($paymentmethod[$p]->name); ?>
					</label><br>
					<?php
				}
				else
				{
					if ($is_company == 1 && $business == 1)
					{
						?>
						<label><input type="radio" name="payment_method_class"
						       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
						<?php        echo JText::_($paymentmethod[$p]->name) ; ?>
						</label><br>
					<?php
					}
				}
			}
			else
			{
				?>
				<label><input type="radio" name="payment_method_class"
				       value="<?php echo $paymentmethod[$p]->element; ?>" <?php echo $checked; ?> />
				<?php    echo JText::_($paymentmethod[$p]->name); ?>
				</label><br>
					<?php
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
