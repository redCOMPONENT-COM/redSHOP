<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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


	require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );
	require_once ( JPATH_SITE . DS. 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'cart.php');
	require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php');
	
	
	
	$request=JRequest::get('request');
	
	
	if(isset($request['adinfo']) && $request['adinfo']==1)
	{
		$post = JRequest::get('post');
		$this->reserveAmountrs_payment_klarna("rs_payment_klarna",$post);
		
	} else {
		
		$this->onAdditionalInformationrs_payment_klarna("rs_payment_klarna" , $data);
	
	}
     // end by me

?>

