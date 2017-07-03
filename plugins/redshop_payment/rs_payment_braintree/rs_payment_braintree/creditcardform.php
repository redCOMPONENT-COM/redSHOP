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
	$itemId         = JRequest::getInt('Itemid');
	$post['Itemid'] = $itemId;

	$this->getOrderAndCcdata("rs_payment_braintree", $post);
}
else
{
	$this->getCredicardForm("rs_payment_braintree", $data);
}
