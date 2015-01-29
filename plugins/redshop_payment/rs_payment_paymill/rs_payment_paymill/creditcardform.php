<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');

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
