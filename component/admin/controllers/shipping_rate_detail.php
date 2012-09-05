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

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class shipping_rate_detailController extends JController 
{
	function __construct($default = array()) 
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}

	function edit() 
	{
		JRequest::setVar ( 'view', 'shipping_rate_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();

	}

	function apply()
	{
       $this->save(1);
	}

	function save($apply=0) 
	{
		$post = JRequest::get ( 'post' );

		// include extra field class
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );

		$option = JRequest::getVar ('option');
		$post['shipping_rate_on_product']=$post['container_product'];
		$post["shipping_location_info"] = JRequest::getVar( 'shipping_location_info', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel ( 'shipping_rate_detail' );

		if ($row = $model->store ( $post )) 
		{
			$field = new extra_field();
			$field->extra_field_save($post,"11", $row->shipping_rate_id); // field_section 11 :Shipping
			$msg = JText::_ ( 'SHIPPING_LOCATION_SAVED' );
		} else {
			$msg = JText::_ ( 'ERROR_SAVING_SHIPPING' );
		}
		if($apply){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=shipping_rate_detail&cid='.$row->shipping_rate_id.'&id='.$post['id'], $msg );
		}else{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=shipping_rate&id='.$post['id'], $msg );
		}
	}

	function remove() 
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$model = $this->getModel ( 'shipping_rate_detail' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) 
		{
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		$model = $this->getModel ( 'shipping_rate_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_rate&id='.$post['id'] );
	}
	
	function publish() 
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		$model = $this->getModel ( 'shipping_rate_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_rate' );
	}

	function unpublish() 
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'shipping_rate_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_rate' );
	}

	function cancel() 
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar ('option');
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_rate&id='.$post['id'] );
	}

	function copy()
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$model = $this->getModel ( 'shipping_rate_detail' );
		if ($model->copy($cid)) {
			$msg = JText::_ ( 'SHIPPING_RATE_SAVED' );
		} else {
			$msg = JText::_ ( 'ERROR_SAVING_SHIPPING' );
		}
		$this->setRedirect ( 'index.php?option=' .$option. '&view=shipping_rate&id='.$post['id'], $msg );
	}

	function GetStateDropdown()
	{
		$get = JRequest::get ( 'get' );
		$model = $this->getModel ( 'shipping_rate_detail' );
		$shipping_rate_state = $model->GetStateDropdown($get);
		exit;
	}
}
?>