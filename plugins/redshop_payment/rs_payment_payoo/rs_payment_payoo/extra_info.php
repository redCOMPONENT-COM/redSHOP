<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$today = date('d/m/Y', strtotime('+2 day', time()));
$validity = date('YmdHis', strtotime('+1 day', time()));

$amount = RedshopHelperCurrency::convert($data['order']->order_total, '', 'VND');

$name = $data['billinginfo']->lastname . ' ' . $data['billinginfo']->firstname;

$urlNotify = urlencode(JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_giropay&orderid=" . $data['order_id']);

$xml = "<shops><shop><shop_id>" . $this->params->get("shopid") . "</shop_id><username>" . $this->params->get("username") . "</username><session>" . md5($data['order_id']) . "</session><shop_title>" . $this->params->get("shoptitle") . "</shop_title><shop_domain>" . JURI::base() . "</shop_domain><shop_back_url>" . $urlNotify . "</shop_back_url><notify_url>" . $urlNotify . "</notify_url><order_no>" . $data['order_id'] . "</order_no><order_cash_amount>" . $amount . "</order_cash_amount><order_ship_date>" . $today . "</order_ship_date><order_ship_days>2</order_ship_days><order_description>Payoo</order_description><validity_time>" . $validity . "</validity_time><customer><name>" . $name . "</name><phone>" . $data['billinginfo']->phone . "</phone><address>" . $data['billinginfo']->address . "</address><email>" . $data['billinginfo']->email . "</email></customer></shop></shops>";

$checkSum = hash('sha512', $this->params->get("checksumkey") . $xml);

?>
<script src="https://newsandbox.payoo.com.vn/v2/merchants/methods.js"></script>

<form id="frmPayByPayoo" class="test-form" name="frmPayByPayoo" action="https://newsandbox.payoo.com.vn/v2/paynow/" method="POST">
	<div id="payoo-methods" style="width: 710px;"></div>
		<input type="hidden" name="cmd" value="_cart" />
		<input type="hidden" name="OrdersForPayoo" value="<?php echo $xml ?>" />
		<input type="hidden" name="CheckSum" value="<?php echo $checkSum ?>" />
</form>
<script type="text/javascript">
	function validate()
	{
		return confirm("Are your sure?");
	};

	Payoo.init({
		id: '<?php echo $this->params->get("shopid") ?>',
		url: '<?php echo JURI::base() ?>',
		wrapper: 'Ecommerce',
		selector: '#payoo-methods',
		type: 'full',
		autohide: false,
		order: {
			seller: '<?php echo $this->params->get("username") ?>',
			amount: '<?php echo $amount ?>'
		},

		form: {
			selector: '.test-form',
			submit: function(form) {
				return validate(form);
			}
		}
	});
</script>
