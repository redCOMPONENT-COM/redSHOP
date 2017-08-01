<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

?>

<div id="popupSendDiscountCode" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo JText::_('PLG_SYSTEM_REDSHOP_SEND_EMAIL_BUTTON') ?></h4>
			</div>
			<div class="modal-body">
				Email: <input type="email" name="sendDiscountCodeEmail" id="sendDiscountCodeEmail" required="required">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="sendDiscountCode()">Send</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
	function sendDiscountCode()
	{
		var discountId = jQuery('input[name="cid[]"]:checked').val();

		if (typeof discountId === "undefined")
		{
			alert('<?php echo JText::_('PLG_SYSTEM_REDSHOP_SEND_DISCOUNT_CODE_CHOOSE_DISCOUNT_CODE') ?>');
			return;
		}

		var sendDiscountCodeEmail = jQuery('#sendDiscountCodeEmail').val();

		if (sendDiscountCodeEmail == "")
		{
			alert('<?php echo JText::_('PLG_SYSTEM_REDSHOP_SEND_DISCOUNT_CODE_INPUT_EMAIL') ?>');
			return;
		}

		$.ajax({
			url: "<?php echo JURI::root() ?>index.php?option=com_ajax&plugin=RedShop_SendDiscountCodeByMail&format=json&email=" + sendDiscountCodeEmail + "&discountId=" + discountId + "&view=<?php echo $view ?>"
		}).done(function(data) {
			alert('<?php echo JText::_('PLG_SYSTEM_REDSHOP_SEND_DISCOUNT_CODE_SENT') ?>');
			jQuery("[data-dismiss=modal]").trigger({ type: "click" });
		})
	}
</script>
