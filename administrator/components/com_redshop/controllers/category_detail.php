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

class category_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit()
	{
		JRequest::setVar ( 'view', 'category_detail' );
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


		$category_description = JRequest::getVar( 'category_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$category_short_description = JRequest::getVar( 'category_short_description', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$post["category_description"]=$category_description;

		$post["category_short_description"] = $category_short_description;

		if (is_array($post["category_more_template"]))
			$post["category_more_template"] = implode(",",$post["category_more_template"]);

		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['category_id'] = $cid [0];
		$model = $this->getModel ( 'category_detail' );
		////////// include extra field class  /////////////////////////////////////
//		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
		////////// include extra field class  /////////////////////////////////////

		if ($row=$model->store ( $post )) {
			$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATEGORY_DETAIL' );
		}
		if($apply==1){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=category_detail&task=edit&cid[]='.$row->category_id, $msg );
		// index.php?option=com_redshop&view=product_detail&task=edit&cid[]=12
		} else {
			$this->setRedirect ( 'index.php?option=' . $option . '&view=category', $msg );
		}
	}
	function remove() {

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'category_detail' );

		if (!$model->delete ( $cid )) {
			$msg = "";
			if ($model->getError ()!= "")
				JError::raiseWarning ( 500, $model->getError () );
		}else {
			$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_DELETED_SUCCESSFULLY' );
		}

		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	function publish() {

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel ( 'category_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	function unpublish() {

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'category_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	function cancel() {

		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}

	function orderup()
	{
	    $option = JRequest::getVar('option');

		$model = $this->getModel('category_detail');
		//$model->move(-1);
 		$model->orderup();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );

	}

	function orderdown()
	{
		 $option = JRequest::getVar('option');

		$model = $this->getModel('category_detail');
		//$model->move(1);
		$model->orderdown();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );

	}

	function saveorder()
	{
		$option = JRequest::getVar('option');

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );

	}
	function copy()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$model = $this->getModel ( 'category_detail' );
		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_COPIED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_CATEGORY' );
		}
		$this->setRedirect ( 'index.php?option=' .$option. '&view=category', $msg );
	}
}?>
