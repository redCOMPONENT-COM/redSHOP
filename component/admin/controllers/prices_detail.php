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

jimport ( 'joomla.application.component.controller' );

class prices_detailController extends JController {

	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit()
	{
		JRequest::setVar ( 'view', 'prices_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		$model = $this->getModel ( 'prices_detail' );

		parent::display ();
	}
	function save()
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		$price_quantity_start = JRequest::getVar('price_quantity_start');
		$price_quantity_end = JRequest::getVar('price_quantity_end');

		$post['product_currency'] = CURRENCY_CODE;
		$post['cdate'] = time();//date("Y-m-d");

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['price_id'] = $cid [0];
		
		$post['discount_start_date'] = strtotime($post ['discount_start_date']);
		if($post['discount_end_date'])
		{
			$post ['discount_end_date'] = strtotime($post['discount_end_date'])+(23*59*59);
		}

		$model = $this->getModel ( 'prices_detail' );
		if($price_quantity_start==0 && $price_quantity_end==0)
		{
			if ($model->store ( $post )) {
				$msg = JText::_ ( 'PRICE_DETAIL_SAVED' );
			} else {
				$msg = JText::_ ( 'ERROR_SAVING_PRICE_DETAIL' );
			}
		}	else {
			if($price_quantity_start < $price_quantity_end ){
					if ($model->store ( $post )) {
						$msg = JText::_ ( 'PRICE_DETAIL_SAVED' );
					} else {
						$msg = JText::_ ( 'ERROR_SAVING_PRICE_DETAIL' );
					}
			}else{
					$msg = JText::_ ( 'ERROR_SAVING_PRICE_QUNTITY_DETAIL' );
	
			}
		}
		$this->setRedirect ( 'index.php?option=' . $option . '&view=prices&product_id='.$product_id, $msg );
	}
	function remove() {

		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO DELETE' ) );
		}

		$model = $this->getModel ( 'prices_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'PRICE_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=prices&product_id='.$product_id,$msg );
	}

	function cancel() {

		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');

		$msg = JText::_ ( 'PRICE_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=prices&product_id='.$product_id,$msg );
	}
}	?>