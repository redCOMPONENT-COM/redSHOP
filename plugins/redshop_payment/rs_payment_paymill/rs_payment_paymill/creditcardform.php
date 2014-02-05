<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

$request = JRequest::get('request');

if (isset($request['ccinfo']) && $request['ccinfo'] == 1)
{
	$post = JRequest::get('post');
	$Itemid = JRequest::getInt('Itemid');
	$post['Itemid'] = $Itemid;

	$this->getOrderAndCcdata("rs_payment_paymill", $post);
}
else
{
	$this->getCredicardForm("rs_payment_paymill", $data);
}
?>
<script type="text/javascript"
    src="<?php echo JURI::base() ?>plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/js/paymill.js"></script>
