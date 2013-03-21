<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
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