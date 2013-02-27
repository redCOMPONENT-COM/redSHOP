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

class discount_detailController extends JController {
	function __construct($default = array()) { 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() 
	{
		$layout = JRequest::getVar('layout');
		
		JRequest::setVar ( 'view', 'discount_detail' );  		
			
		if($layout == 'product')
		{
			JRequest::setVar ( 'layout', 'product' );
			
		}else{
			JRequest::setVar ( 'layout', 'default' );
		}
		JRequest::setVar ( 'hidemainmenu', 1 );
		
		parent::display ();
	}
	function apply(){
		$this->save(1);
	}
	function save($apply=0) 
	{
		$post = JRequest::get ( 'post' );
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );
			
		
		$post ['start_date'] = strtotime($post ['start_date']);
		$post ['end_date'] = strtotime($post ['end_date'])+(23*59*59);
		
		$model = $this->getModel ( 'discount_detail' );

		$layout = JRequest::getVar('layout');
		
		
		$post ['category_ids'] = ($post ['category_ids'])? implode(',',$post ['category_ids']) : '';

		if(isset($layout) && $layout == 'product')
		{
			$post ['discount_product_id'] = $cid[0];
			$row = $model->storeDiscountProduct ( $post );
			$did = $row->discount_product_id;
		}else{
		
			$post ['discount_id'] = $cid[0];
			$row = $model->store ( $post );
			$did = $row->discount_id;	
		}
		if ($row) 
		{
			$model->saveShoppers($did , $post['shopper_group_id']);
			$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED' );
		
		} 
		else 
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL' );
		}
		if($apply ==1 )
			if (isset($layout) && $layout == 'product')
			{
				$this->setRedirect('index.php?option=' . $option . '&view=discount_detail&layout=product&task=edit&cid[]=' . $row->discount_product_id, $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=' . $option . '&view=discount_detail&task=edit&cid[]=' . $row->discount_id, $msg);
			}
		else {		
		if(isset($layout) && $layout == 'product')
			$this->setRedirect ( 'index.php?option=' . $option . '&view=discount&layout=product', $msg );
		else
			$this->setRedirect ( 'index.php?option=' . $option . '&view=discount', $msg );
		}
			
	}
	function remove() 
	{
		
		$option = JRequest::getVar ('option');
		
		$layout = JRequest::getVar('layout');
		
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'discount_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY' );
		
		if(isset($layout) && $layout == 'product')
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount&layout=product',$msg );
		else
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount',$msg );
	}
	function publish() 
	{ 
		$layout = JRequest::getVar('layout');
			
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'discount_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_PUBLISHED_SUCCESSFULLY' );
		
		if(isset($layout) && $layout == 'product')
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount&layout=product',$msg );
		else
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount',$msg );
	}
	function unpublish() 
	{
		$layout = JRequest::getVar('layout');
	
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'discount_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		
		if(isset($layout) && $layout == 'product')
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount&layout=product',$msg );
		else
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount',$msg );
		
	}
	function cancel() 
	{
		$layout = JRequest::getVar('layout');	
		$option = JRequest::getVar ('option');
		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED' );
		
		if(isset($layout) && $layout == 'product')
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount&layout=product',$msg );
		else
			$this->setRedirect ( 'index.php?option='.$option.'&view=discount',$msg );
	}

}
