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

class user_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
		$this->_table_prefix = '#__redshop_';
		$this->redhelper = new redhelper();
	}

	function edit()
	{
		JRequest::setVar ( 'view', 'user_detail' );
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
		$option = JRequest::getVar('option','','request','string');
		$post = JRequest::get( 'post' );

	   	$model = $this->getModel('user_detail');
	   	$shipping = isset($post["shipping"])? true : false;

		if ($row = $model->store ( $post ))
		{
			$msg = JText::_('COM_REDSHOP_USER_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_USER_DETAIL' );
		}

		if($shipping)
		{
			$info_id = JRequest::getVar('info_id', '', 'request', 'string');
			$link = 'index.php?option='.$option.'&view=user_detail&task=edit&cancel=1&cid[]='.$info_id;
		}
		else
		{
			if($apply==1)
			{
				$link = 'index.php?option='.$option.'&view=user_detail&task=edit&cid[]='.$row->users_info_id;
				$link = $this->redhelper->sslLink($link);
			}
			else
			{
				$link = 'index.php?option='.$option.'&view=user';
				$link = $this->redhelper->sslLink($link,0);
			}
		}
		$this->setRedirect ( $link, $msg );
	}

	function remove()
	{
		$option = JRequest::getVar('option','','request','string');
		$shipping = JRequest::getVar('shipping','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'request', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'user_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_USER_DETAIL_DELETED_SUCCESSFULLY' );
		if($shipping)
		{
			$info_id = JRequest::getVar('info_id', '', 'request', 'int');
			$this->setRedirect ( 'index.php?option='.$option.'&view=user_detail&task=edit&cancel=1&cid[]='.$info_id, $msg );
		}else{
			$this->setRedirect ( 'index.php?option='.$option.'&view=user',$msg );
		}
	}

	function publish()
	{
		$option = JRequest::getVar('option','','request','string');
//		$shipping = JRequest::getVar('shipping','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel ( 'user_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_USER_DETAIL_PUBLISHED_SUCCESSFULLY' );
//		if($shipping)
//		{
//			$info_id = JRequest::getVar('info_id', '', 'request', 'int');
//			$this->setRedirect ( 'index.php?option='.$option.'&view=user&shipping=1&cancel=1&cid[]='.$info_id, $msg );
//		}
//		else
//		{
			$this->setRedirect ( 'index.php?option='.$option.'&view=user',$msg );
//		}
	}

	function unpublish()
	{
		$option = JRequest::getVar('option','','request','string');
		$shipping = JRequest::getVar('shipping','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'user_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_USER_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
//		if($shipping)
//		{
//			$info_id = JRequest::getVar('info_id', '', 'request', 'int');
//			$this->setRedirect ( 'index.php?option='.$option.'&view=user&shipping=1&cancel=1&cid[]='.$info_id, $msg );
//		}
//		else
//		{
			$this->setRedirect ( 'index.php?option='.$option.'&view=user',$msg );
//		}
	}

	function cancel()
	{
		$option = JRequest::getVar('option','','request','string');
		$shipping = JRequest::getVar('shipping','','request','string');
		$info_id = JRequest::getVar('info_id', '', 'request', 'string');

		$msg = JText::_('COM_REDSHOP_USER_DETAIL_EDITING_CANCELLED' );
		if($shipping)
		{
			$link = 'index.php?option='.$option.'&view=user_detail&task=edit&cancel=1&cid[]='.$info_id;
		}
		else{
			$link = 'index.php?option='.$option.'&view=user';
		}
		$link = $this->redhelper->sslLink($link,0); //not to apply ssl (passed Zero)
		$this->setRedirect($link, $msg);
	}

	function order()
	{
		$option = JRequest::getVar('option','','request','string');
		$user_id = JRequest::getVar('user_id',0,'request','string');
		$this->setRedirect ( 'index.php?option='.$option.'&view=addorder_detail&user_id='.$user_id );
	}

	function validation()
	{
		$json = JRequest::getVar( 'json', '');
		$decoded = json_decode($json);
		$model = $this->getModel ( 'user_detail' );
		$username = $model->validate_user($decoded->username,$decoded->userid);
		$email = $model->validate_email($decoded->email,$decoded->userid);
		$json = array();
		$json['ind'] = $decoded->ind;
		$json['username'] = $username;
		$json['email'] = $email;
		$encoded = json_encode($json);
		die($encoded);
	}
}	?>