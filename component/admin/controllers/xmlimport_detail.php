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

class xmlimport_detailController extends JController 
{
	function __construct($default = array()) 
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function edit() 
	{
		JRequest::setVar ( 'view', 'xmlimport_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	
	function xmlimport() 
	{
		$this->save(1);
	}
	
	function save($import=0) 
	{
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$post['xmlimport_id'] = $cid [0];
		$model = $this->getModel ( 'xmlimport_detail' );
		
		if($post['xmlimport_id']==0)
		{
			$post['xmlimport_date'] = time();
		}
		$row = $model->store ( $post, $import );
		if ($row) 
		{
			if($import==1)
			{
				$msg = JText::_('COM_REDSHOP_XMLIMPORT_FILE_SUCCESSFULLY_SYNCHRONIZED' );
			} else {
				$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_SAVED' );
			}
		} else {
			if($import==1)
			{
				$msg = JText::_('COM_REDSHOP_ERROR_XMLIMPORT_FILE_SYNCHRONIZED' );
			} else {
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_XMLIMPORT_DETAIL' );
			}
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlimport', $msg );
	}
	
	function remove() 
	{
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'xmlimport_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlimport',$msg );
	}
	
	function cancel() 
	{
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlimport',$msg );
	}
	
	function auto_syncpublish() 
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE' ) );
		}
		$model = $this->getModel ( 'xmlimport_detail' );
		if (! $model->auto_syncpublish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_ENABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlimport',$msg );
	}
	
	function auto_syncunpublish() 
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE' ) );
		}
		$model = $this->getModel ( 'xmlimport_detail' );
		if (! $model->auto_syncpublish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_DISABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlimport',$msg );
	}
	
	/**
	 * logic for publish
	 *
	 * @access public
	 * @return void
	 */
	function publish()
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		$model = $this->getModel ( 'xmlimport_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLIMPORT_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=xmlimport',$msg );
	}
	/**
	 * logic for unpublish
	 *
	 * @access public
	 * @return void
	 */
	function unpublish()
	{
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		$model = $this->getModel ( 'xmlimport_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLIMPORT_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=xmlimport',$msg );
	}
}?>