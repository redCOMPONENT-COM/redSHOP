<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

// Redirection after full page load
JHtml::_('redshopjquery.framework');
JHtml::_('bootstrap.framework');
$session = JFactory::getSession();
$ccData = $session->get('ccdata');

extract ($displayData);

// Submit form
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	'
	document.addEventListener("DOMContentLoaded", function(event) { 
        window.document.authodpmfrm.submit();
	});
	'
);
?>
<form method="post" action="<?php echo $postUrl; ?>" name="authodpmfrm" id="authodpmfrm">
	<?php echo $hiddenFields; ?>
	<fieldset>
		<div>
			<label>Credit Card Number</label>
			<input type="text" class="text" size="15" name="x_card_num" value="<?php echo $ccData['order_payment_number']; ?>"></input>
		</div>
		<div>
			<label>Exp.</label>
			<input type="text" class="text" size="4" name="x_exp_date" value="<?php echo $ccData['order_payment_expire_month'] . '/' . $ccData['order_payment_expire_year']; ?>"></input>
		</div>
		<div>
			<label>CCV</label>
			<input type="text" class="text" size="4" name="x_card_code" value="<?php echo $ccData['credit_card_code']; ?>"></input>
		</div>
	</fieldset>
	<fieldset>
		<div>
			<label>First Name</label>
			<input type="text" class="text" size="15" name="x_first_name" value="<?php echo $ccData['order_payment_name']; ?>"></input>
		</div>
		<div>
			<label>Last Name</label>
			<input type="text" class="text" size="14" name="x_last_name" value="<?php echo $preFill ? 'Doe' : ''; ?>"></input>
		</div>
	</fieldset>
	<fieldset>
		<div>
			<label>Address</label>
			<input type="text" class="text" size="26" name="x_address" value="<?php echo $preFill ? '123 Main Street' : ''; ?>"></input>
		</div>
		<div>
			<label>City</label>
			<input type="text" class="text" size="15" name="x_city" value="<?php echo $preFill ? 'Boston' : ''; ?>"></input>
		</div>
	</fieldset>
	<fieldset>
		<div>
			<label>State</label>
			<input type="text" class="text" size="4" name="x_state" value="<?php echo $preFill ? 'MA' : ''; ?>"></input>
		</div>
		<div>
			<label>Zip Code</label>
			<input type="text" class="text" size="9" name="x_zip" value="<?php echo $preFill ? '02142' : ''; ?>"></input>
		</div>
		<div>
			<label>Country</label>
			<input type="text" class="text" size="22" name="x_country" value="<?php echo $preFill ? 'US' : ''; ?>"></input>
		</div>
	</fieldset>
	<input type="submit" value="BUY" class="submit buy">
</form>