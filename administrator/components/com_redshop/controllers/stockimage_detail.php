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

class stockimage_detailController extends JController 
{
	function __construct($default = array()) 
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() 
	{
		JRequest::setVar ( 'view', 'stockimage_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	
	function save() 
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['stock_amount_id'] = $cid [0];
		
		$model = $this->getModel ( 'stockimage_detail' );

		if ($row=$model->store ( $post )) 
		{
			$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKIMAGE_DETAIL' );
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=stockimage', $msg );
	}
	
	function remove() 
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		$model = $this->getModel ( 'stockimage_detail' );
		if (! $model->delete ( $cid )) 
		{
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
	}
	
	/*function publish() 
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		$model = $this->getModel ( 'stockimage_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
	}
	function unpublish() 
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		$model = $this->getModel ( 'stockimage_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
	}*/
	function cancel() 
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=stockimage',$msg );
	}
}
?>