<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin.Rs_Payment_Epayv2
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<script
	charset="UTF-8"
	src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js"
	type="text/javascript">		
</script>
<div id="payment-div"></div>
<script type="text/javascript">
	paymentwindow = new PaymentWindow(<?php echo $jsonPassString ?>);
	paymentwindow.append('payment-div');
	paymentwindow.open();
</script>';
<input onclick="javascript: paymentwindow.open()" type="button" value="Go to payment">';

