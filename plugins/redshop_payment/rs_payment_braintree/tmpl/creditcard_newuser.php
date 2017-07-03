<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template.Extra_Info
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=<?php echo (int) $data['order_id'] ?>&Itemid=<?php echo $itemId ?>"
	method="post"
	name="adminForm"
	id="adminForm"
	enctype="multipart/form-data"
	onsubmit="return CheckCardNumber(this);">

	<fieldset class="adminform"><legend><?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_INFORMATION') ?></legend>
		<table class="admintable">
			<tr>
				<td colspan="2" align="right" nowrap="nowrap">
					<table width="100%" border="0" cellspacing="2" cellpadding="2">';
						<tr>
							<?php for ($i = 0; $i < count($acceptedCredictCard); $i++): ?>
								<td align="center"><img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $creditCards[$acceptedCredictCard[$i]]->img ?>" alt="" border="0" /></td>
							<?php endfor ?>
						</tr>
						<tr>

							<?php for ($i = 0; $i < count($acceptedCredictCard); $i++): ?>
								<?php $value   = $acceptedCredictCard[$i]; ?>
								<?php $checked = ""; ?>

								<?php if (!isset($ccdata['creditcard_code']) && $i == 0): ?>
									<?php $checked = "checked"; ?>
								<?php elseif (isset($ccdata['creditcard_code'])): ?>
									<?php $checked = ($ccdata['creditcard_code'] == $value) ? "checked" : ""; ?>
								<?php endif ?>

								<td align="center"><input type="radio" name="creditcard_code" value="<?php echo $value ?>" <?php echo $checked ?> /></td>
							<?php endfor ?>

						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<td align="right" nowrap="nowrap" width="10%">
					<label for="order_payment_name">
						<?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_NAME_ON_CARD') ?>
					</label>
				</td>
				<td>
					<input class="inputbox" id="order_payment_name" name="order_payment_name" value="<?php echo $orderPaymentName ?>" autocomplete="off" type="text">
				</td>
			</tr>
			<tr valign="top">
				<td align="right" nowrap="nowrap" width="10%">
					<label for="order_payment_number">
						<?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_NUM') ?>
					</label>
				</td>
				<td>
					<input class="inputbox" id="order_payment_number" name="order_payment_number" value="<?php echo $orderPaymentNumber ?>" autocomplete="off" type="text">
				</td>
			</tr>
			<tr>
				<td align="right" nowrap="nowrap" width="10%"><?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_EXPIRY_DATE') ?></td>';
				<td>
					<?php echo JHTML::_('select.genericlist', $months, 'order_payment_expire_month', 'size="1" class="inputbox" ', 'value', 'text', $value); ?>
					<select class="inputbox" name="order_payment_expire_year" size="1">
						<?php for ($i = $thisYear; $i < ($thisYear + 10); $i++): ?>
							<?php $selected = (!empty($ccdata['order_payment_expire_year']) && $ccdata['order_payment_expire_year'] == $i) ? "selected" : ""; ?>
							<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?></option>
						<?php endfor ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<td align="right" nowrap="nowrap" width="10%">
					<label for="credit_card_code">
						<?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_CARD_SECURITY_CODE') ?>
					</label>
				</td>
				<td>
					<input class="inputbox" id="credit_card_code" name="credit_card_code" value="<?php echo $creditCardCode ?>" autocomplete="off" type="password">
				</td>
			</tr>
		</table>
	</fieldset>

	<input type="hidden" name="option" value="com_redshop" />
	<input type="hidden" name="Itemid" value="<?php echo $itemId ?>" />
	<input type="submit" name="submit" class="greenbutton" value="<?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_BTN_CHECKOUTNEXT') ?>" />
	<input type="hidden" name="ccinfo" value="1" />
	<input type="hidden" name="payment_method_id" value="<?php echo $paymentMethodId ?>" />
	<input type="hidden" name="new_vault_user" value="<?php echo $newUser ?>" />
	<input type="hidden" name="order_id" value="<?php echo $data['order_id'] ?>" />
</form>
<script
	type="text/javascript"
	src="<?php echo JURI::base() ?>media/com_redshop/js/credit_card.js">
</script>
