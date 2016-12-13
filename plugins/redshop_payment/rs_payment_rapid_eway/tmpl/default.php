<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

// Redirection after full page load
JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	'jQuery(document).ready(function(){
		jQuery("#ewayfrm").submit();
	});'
);
?>
<h3><?php echo JText::_('PLG_RS_PAYMENT_RAPID_EWAY_WAIT_MESSAGE'); ?></h3>
<form method="POST" action="<?php echo $result->FormActionURL ?>" id="ewayfrm" name="ewayfrm">
	<input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $result->AccessCode ?>"/>
	<input type="hidden" name="EWAY_CARDNAME" value="<?php echo $ccdata['order_payment_name'] ?>"/>
	<input type="hidden" name="EWAY_CARDNUMBER" value="<?php echo $ccdata['order_payment_number'] ?>"/>
	<input type="hidden" name="EWAY_CARDEXPIRYMONTH" value="<?php echo $ccdata['order_payment_expire_month'] ?>"/>
	<input type="hidden" name="EWAY_CARDEXPIRYYEAR" value="<?php echo $ccdata['order_payment_expire_year'] ?>"/>
	<input type="hidden" name="EWAY_CARDCVN" value="<?php echo $ccdata['credit_card_code'] ?>"/>
</form>
