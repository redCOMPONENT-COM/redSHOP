<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$request = JRequest::get('request');

if (isset($request['ccinfo']) && $request['ccinfo'] == 1)
{
	$post           = JRequest::get('post');
	$Itemid         = JRequest::getInt('Itemid');
	$post['Itemid'] = $Itemid;

	$this->getOrderAndCcdata("rs_payment_braintree", $post);
}
else
{
	$this->getCredicardForm("rs_payment_braintree", $data);
}
?>
<script
	type="text/javascript"
	src="<?php echo JURI::base() ?>media/com_redshop/js/credit_card.js">
</script>
