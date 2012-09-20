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

class discountarea_detailController extends JController 
{
	function __construct($default = array()) 
	{ 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function edit() 
	{
		JRequest::setVar ( 'view', 'discountarea_detail' );  		
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
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );

		$post ['product_id']=$post['container_product'];
		$post ['discountstart_date'] = strtotime($post ['discountstart_date']);
		$post ['discountend_date'] = strtotime($post ['discountend_date'])+(23*59*59);
		
		$model = $this->getModel ( 'discountarea_detail' );

		$post ['discountAreaid'] = $cid[0];
		$row = $model->store ( $post );
		if ($row) 
		{
			$msg = JText::_ ( 'DISCOUNT_DETAIL_SAVED' );
		} 
		else 
		{
			$msg = JText::_ ( 'ERROR_SAVING_DISCOUNT_DETAIL' );
		}
		if($apply ==1 )
		{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=discountarea_detail&task=edit&cid[]='.$row->discountAreaid, $msg );
		}
		else 
		{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=discountarea', $msg );
		}
	}
	
	function remove() 
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'discountarea_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DISCOUNT_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=discountarea',$msg );
	}
	
	function publish() 
	{ 
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) 
		{
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'discountarea_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DISCOUNT_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=discountarea',$msg );
	}
	
	function unpublish() 
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		$model = $this->getModel ( 'discountarea_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DISCOUNT_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=discountarea',$msg );
	}
	
	function cancel() 
	{
		$option = JRequest::getVar ('option');
		$msg = JText::_ ( 'DISCOUNT_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=discountarea',$msg );
	}
}	?>