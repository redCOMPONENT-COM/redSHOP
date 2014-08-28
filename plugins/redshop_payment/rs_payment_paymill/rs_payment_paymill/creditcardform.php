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

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperCart');


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
// end by me

?>

<script type="text/javascript"
        src="<?php echo JURI::base() ?>plugins/redshop_payment/rs_payment_paymill/rs_payment_paymill/js/paymill.js"></script>
