<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php';
require_once JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'cart.php';
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';


$request = JRequest::get('request');

if (isset($request['ccinfo']) && $request['ccinfo'] == 1)
{
	$post = JRequest::get('post');
	$Itemid = JRequest::getInt('Itemid');
	$post['Itemid'] = $Itemid;

	$this->getOrderAndCcdata("rs_payment_braintree", $post);

}
else
{
	$this->getCredicardForm("rs_payment_braintree", $data);

}
// end by me

?>
<script type="text/javascript" src="<?php echo JURI::base() ?>components/com_redshop/assets/js/credit_card.js"></script>