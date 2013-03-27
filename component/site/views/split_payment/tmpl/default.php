<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper;
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
$order_functions = new order_functions;

$url = JURI::base();

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$oid = JRequest::getInt('oid');

$model = $this->getModel('split_payment');
$user = JFactory::getUser();

$orderdetails = $model->getordersdetail($oid);

$order_number = $orderdetails->order_number . "_2";

$partial_paid = $order_functions->getOrderPartialPayment($oid);

$remaningtopay = $orderdetails->order_total - $partial_paid;
$remaningtopay = number_format($remaningtopay, 2);
?>
<?php
if ($this->params->get('show_page_heading', 1))
{ ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape(JText::_('COM_REDSHOP_SPLIT_PAYMENT')); ?>
	</div>
<?php
}
?>
<?php

$url = JURI::base();
$user = JFactory::getUser();
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$document = JFactory::getDocument();

$is_creditcard = 0;

?>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD'); ?></legend>
	<div>

		<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=split_payment') ?>" method="post"
		      name="adminForm" id="adminForm">

			<?php
			$paymentmethod = $order_functions->getPaymentMethodInfo();

			$adminpath = JPATH_ADMINISTRATOR . '/components/com_redshop';

			for ($p = 0; $p < count($paymentmethod); $p++)
			{
				$paymentpath = $adminpath . '/helpers/payments/' . $paymentmethod[$p]->plugin . '/' . $paymentmethod[$p]->plugin . '.php';
				include_once $paymentpath;

				?>
				<input type="radio" name="payment_method_id"
				       value="<?php echo $paymentmethod[$p]->payment_method_id; ?>"  <?php

						if ($this->payment_method_id == $paymentmethod[$p]->payment_method_id || !$this->payment_method_id)
						{
						?> checked="checked" <?php
						}
						?> />
				<?php
				echo        $paymentmethod[$p]->payment_method_name;

				if ($paymentmethod[$p]->is_creditcard == 1)
				{
					$is_creditcard = 1;
				}
			}

			if ($is_creditcard)
			{
				$thisyear = date('Y');
				?>
				<table cellpadding="2" cellspacing="2" border="0">
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="right" nowrap="nowrap">

							<table width="100%" border="0" cellspacing="2" cellpadding="2">
								<tr>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/visa.jpg" alt=""
											border="0"/></td>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/master.jpg" alt=""
											border="0"/></td>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/mastero.jpg" alt=""
											border="0"/></td>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/jcb.jpg" alt=""
											border="0"/></td>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/blue.jpg" alt=""
											border="0"/></td>
									<td align="center"><img
											src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>checkout/dinnersclub.jpg"
											alt="" border="0"/></td>
								</tr>
								<tr>
									<td align="center">
										<input type="radio" name="creditcard_code"
											value="Visa"  <?php
											if (empty($_SESSION['ccdata']['creditcard_code']) || $_SESSION['ccdata']['creditcard_code'] == 'Visa')
											{
												?>  checked="checked" <?php
											}
											?> /></td>
									<td align="center">
										<input type="radio"
												name="creditcard_code"
												value="MC"  <?php
											if (!empty($_SESSION['ccdata']['creditcard_code']) && $_SESSION['ccdata']['creditcard_code'] == 'MC')
											{
												?>  checked="checked" <?php
											}
											?> /></td>
									<td align="center">
										<input type="radio"
												name="creditcard_code"
												value="Maestro" <?php
												if (!empty($_SESSION['ccdata']['creditcard_code']) && $_SESSION['ccdata']['creditcard_code'] == 'MasterCard')
												{
													?>  checked="checked" <?php
												}
												?>  /></td>
									<td align="center">
										<input type="radio"
												name="creditcard_code"
												value="JCB" <?php
												if (!empty($_SESSION['ccdata']['creditcard_code']) && $_SESSION['ccdata']['creditcard_code'] == 'JCB')
												{
													?>  checked="checked" <?php
												}
												?>  /></td>
									<td align="center">
										<input type="radio"
												name="creditcard_code"
												value="amex"  <?php
												if (!empty($_SESSION['ccdata']['creditcard_code']) && $_SESSION['ccdata']['creditcard_code'] == 'amex')
												{
													?>  checked="checked" <?php
												}
												?> /></td>
									<td align="center">
										<input type="radio" name="creditcard_code"
												value="diners"  <?php
												if (!empty($_SESSION['ccdata']['creditcard_code']) && $_SESSION['ccdata']['creditcard_code'] == 'diners')
												{
												?>  checked="checked" <?php
												}
												?> /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr valign="top">
						<td align="right" nowrap="nowrap" width="10%">
							<label for="order_payment_name"><?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?></label>
						</td>
						<td>
							<input class="inputbox" id="order_payment_name" name="order_payment_name"
							       value="<?php
											if (!empty($_SESSION['ccdata']['order_payment_name']))
												echo $_SESSION['ccdata']['order_payment_name'] ?>"
							       autocomplete="off" type="text">
						</td>

					</tr>
					<tr valign="top">
						<td align="right" nowrap="nowrap" width="10%">
							<label for="order_payment_number"><?php echo JText::_('COM_REDSHOP_CARD_NUM'); ?></label>
						</td>
						<td>
							<input class="inputbox" id="order_payment_number" name="order_payment_number"
							       value="<?php
											if (!empty($_SESSION['ccdata']['order_payment_number']))
												echo $_SESSION['ccdata']['order_payment_number'] ?>"
							       autocomplete="off" type="text">
						</td>

					</tr>

					<tr>
						<td align="right" nowrap="nowrap"
						    width="10%"><?php echo JText::_('COM_REDSHOP_EXPIRY_DATE'); ?></td>
						<td>
							<?php
							$value = @$_SESSION['ccdata']['order_payment_expire_month'];

							if ($value == '')
							{
								$value = date('m');
							}

							$arr = array("Month",
								"01" => JText::_('COM_REDSHOP_JAN'),
								"02" => JText::_('COM_REDSHOP_FEB'),
								"03" => JText::_('COM_REDSHOP_MAR'),
								"04" => JText::_('COM_REDSHOP_APR'),
								"05" => JText::_('COM_REDSHOP_MAY'),
								"06" => JText::_('COM_REDSHOP_JUN'),
								"07" => JText::_('COM_REDSHOP_JUL'),
								"08" => JText::_('COM_REDSHOP_AUG'),
								"09" => JText::_('COM_REDSHOP_SEP'),
								"10" => JText::_('COM_REDSHOP_OCT'),
								"11" => JText::_('COM_REDSHOP_NOV'),
								"12" => JText::_('COM_REDSHOP_DEC'));

							$html = "<select class=\"inputbox\" name=\"order_payment_expire_month\" size=\"1\" >\n";

							while (list($key, $val) = each($arr))
							{
								$selected = "";

								if (is_array($value))
								{
									if (in_array($key, $value))
									{
										$selected = "selected=\"selected\"";
									}
								}
								else
								{
									if (strtolower($value) == strtolower($key))
									{
										$selected = "selected=\"selected\"";
									}
								}

								$html .= "<option value=\"$key\" $selected>$val";
								$html .= "</option>\n";
							}

							echo $html .= "</select>\n";

							?>
							<?php $thisyear = date('Y'); ?>
							/<select class="inputbox" name="order_payment_expire_year" size="1">
								<?php

								for ($y = $thisyear; $y < ($thisyear + 10); $y++)
								{
									?>
									<option
										value="<?php echo $y; ?>" <?php

											if (!empty($_SESSION['ccdata']['order_payment_expire_year']) && $_SESSION['ccdata']['order_payment_expire_year'] == $y)
											{
												?> selected="selected" <?php
											}
?> ><?php echo $y; ?></option>
								<?php
								}
								?> </select>
						</td>
					</tr>

					<tr valign="top">
						<td align="right" nowrap="nowrap" width="10%">
							<label for="credit_card_code">
								<?php echo JText::_('COM_REDSHOP_CARD_SECURITY_CODE'); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" id="credit_card_code" name="credit_card_code"
							       value="<?php
											if (!empty($_SESSION['ccdata']['credit_card_code']))
												echo $_SESSION['ccdata']['credit_card_code'] ?>"
							       autocomplete="off" type="text">

						</td>
					</tr>

					<tr valign="top">
						<td align="right" nowrap="nowrap" width="10%">
							<label for="credit_card_code">
								<?php echo JText::_('COM_REDSHOP_ORDERTOTAL'); ?>
							</label>
						</td>
						<td>
							<?php echo $producthelper->getProductFormattedPrice($orderdetails->order_total); ?>
						</td>
					</tr>

					<tr valign="top">
						<td align="right" nowrap="nowrap" width="10%">
							<label for="credit_card_code">
								<?php echo JText::_('COM_REDSHOP_REMAININGTOPAY'); ?>
							</label>
						</td>
						<td>
							<?php echo $producthelper->getProductFormattedPrice($remaningtopay); ?>
						</td>
					</tr>

				</table>
				<input type="hidden" name="ccinfo" value="1"/>

			<?php
			}

			?>

			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
			<input type="hidden" name="task" value="payremaining"/>
			<input type="hidden" name="view" value="split_payment"/>
			<input type="submit" name="submit" value="payremaining"/>

			<input type="hidden" name="oid" value="<?php echo $oid; ?>"/>
			<input type="hidden" name="order_id" value="<?php echo $oid; ?>"/>
			<input type="hidden" name="order_total" value="<?php echo $orderdetails->order_total; ?>"/>
			<input type="hidden" name="remaningtopay" value="<?php echo $remaningtopay; ?>"/>
			<input type="hidden" name="order_number" value="<?php echo $order_number; ?>"/>
		</form>
	</div>
</fieldset>
