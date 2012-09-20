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

class attributeprices_detailController extends JController {

	function __construct($default = array()) { 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() 
	{
		JRequest::setVar ( 'view', 'attributeprices_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		$model = $this->getModel ( 'attributeprices_detail' );

		parent::display ();
	}
	function save() 
	{		
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar ('option');
		$section_id = JRequest::getVar ('section_id');
		$section = JRequest::getVar ('section');
		$price_quantity_start = JRequest::getVar('price_quantity_start');
		$price_quantity_end = JRequest::getVar('price_quantity_end');
		
		$post['product_currency'] = CURRENCY_CODE;
		$post['cdate'] = time();
		$post['discount_start_date'] = strtotime($post ['discount_start_date']);
		if($post['discount_end_date'])
		{
			$post ['discount_end_date'] = strtotime($post['discount_end_date'])+(23*59*59);
		}
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['price_id'] = $cid [0];

		$model = $this->getModel ( 'attributeprices_detail' );
		if ($model->store ( $post )) {
			$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_DETAIL' );
		}
		$this->setRedirect ( 'index.php?tmpl=component&option='.$option.'&view=attributeprices&section='.$section.'&section_id='.$section_id, $msg );
	}
	function remove() {
		
		$option = JRequest::getVar ('option');
		$section_id = JRequest::getVar ('section_id');
		$section = JRequest::getVar ('section');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'attributeprices_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_PRICE_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?tmpl=component&option='.$option.'&view=attributeprices&section='.$section.'&section_id='.$section_id,$msg );
	}

	function cancel() {
		
		$option = JRequest::getVar ('option');
		$section_id = JRequest::getVar ('section_id');
		
		$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=attributeprices&section_id='.$section_id,$msg );
	}
}	?>