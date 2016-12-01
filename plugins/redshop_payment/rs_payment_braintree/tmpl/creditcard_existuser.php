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
<form action="index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=<?php echo $data['order_id'] ?>&Itemid=<?php echo $itemId
				?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >';
	<table height="100">
		<tr>
			<td>
				<?php echo JText::_('PLG_RS_PAYMENT_BRAINTREE_USER_IS_ALREADY_REGISTERED_IN_BRAINTREE_VAULT') ?>
			</td>
		</tr>
	</table>';


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
