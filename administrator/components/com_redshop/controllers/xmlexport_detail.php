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

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'xmlhelper.php');
class xmlexport_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}

	function edit() {
		JRequest::setVar ( 'view', 'xmlexport_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();

	}

	function xmlexport() {
		$this->save(1);
	}

	function save($export=0)
	{
		$session = JFactory::getSession();
		$xmlhelper = new xmlHelper();
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$post['xmlexport_id'] = $cid [0];
		$model = $this->getModel ( 'xmlexport_detail' );

		if($post['xmlexport_id']==0)
		{
			$post['xmlexport_date'] = time();
		}

		$childelement = $session->get('childelement');

		if(isset($childelement['orderdetail']))
		{
			$post['element_name'] = ($childelement['orderdetail'][0]) ? $childelement['orderdetail'][0] : "orderdetail";
			$post['xmlexport_filetag'] = $childelement['orderdetail'][1];
		}
		elseif(isset($childelement['productdetail']))
		{
			$post['element_name'] = ($childelement['productdetail'][0]) ? $childelement['productdetail'][0] : "productdetail";
			$post['xmlexport_filetag'] = $childelement['productdetail'][1];
		}

		if(isset($childelement['billingdetail']))
		{
			$post['billing_element_name'] = ($childelement['billingdetail'][0]) ? $childelement['billingdetail'][0] : "billingdetail";
			$post['xmlexport_billingtag'] = $childelement['billingdetail'][1];
		}
		if(isset($childelement['shippingdetail']))
		{
			$post['shipping_element_name'] = ($childelement['shippingdetail'][0]) ? $childelement['shippingdetail'][0] : "shippingdetail";
			$post['xmlexport_shippingtag'] = $childelement['shippingdetail'][1];
		}
		if(isset($childelement['orderitem']))
		{
			$post['orderitem_element_name'] = ($childelement['orderitem'][0]) ? $childelement['orderitem'][0] : "orderitem";
			$post['xmlexport_orderitemtag'] = $childelement['orderitem'][1];
		}
		if(isset($childelement['stockdetail']))
		{
			$post['stock_element_name'] = ($childelement['stockdetail'][0]) ? $childelement['stockdetail'][0] : "stockdetail";
			$post['xmlexport_stocktag'] = $childelement['stockdetail'][1];
		}
		if(isset($childelement['prdextrafield']))
		{
			$post['prdextrafield_element_name'] = ($childelement['prdextrafield'][0]) ? $childelement['prdextrafield'][0] : "prdextrafield";
			$post['xmlexport_prdextrafieldtag'] = $childelement['prdextrafield'][1];
		}

		$row = $model->store ( $post, $export );
		if ($row)
		{
			if($export==1)
			{
				$msg = JText::_('COM_REDSHOP_XMLEXPORT_FILE_SUCCESSFULLY_SYNCHRONIZED' );
			} else {
				$msg = JText::_('COM_REDSHOP_XMLEXPORT_DETAIL_SAVED' );
			}
		} else {
			if($export==1)
			{
				$msg = JText::_('COM_REDSHOP_ERROR_XMLEXPORT_FILE_SYNCHRONIZED' );
			} else {
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_XMLEXPORT_DETAIL' );
			}
		}

		$session->set ( 'childelement', NULL );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport', $msg );
	}

	function setChildElement()
	{
		JHTMLBehavior::modal();

		$xmlhelper = new xmlHelper();
		$post = JRequest::get ( 'post' );
		$session = JFactory::getSession();
		$childelement = $session->get('childelement');

		$model = $this->getModel ( 'xmlexport_detail' );

		$resarray = array();
		$uarray = array();
		$columns = $xmlhelper->getSectionColumnList($post['section_type'],$post['parentsection']);

		for($i=0;$i<count($columns);$i++)
		{
			if(trim($post[$columns[$i]->Field])!="")
			{
				$xmltag = str_replace(" ","_",strtolower(trim($post[$columns[$i]->Field])));
				$uarray[] = $xmltag;
				$resarray[] = $columns[$i]->Field."=".$xmltag;
			}
		}
		$firstlen = count($uarray);
		$uarray1 = array_unique($uarray);
		sort($uarray1);
		$seclen = count($uarray1);
//		if(count($resarray)<=0)
//		{
//			echo $msg = JText::_('COM_REDSHOP_SELECT_FIELDNAME' );
////			$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport_detail&task=edit&cid[]='.$cid[0], $msg );
//			return;
//		}
		if($seclen!=$firstlen)
		{
			echo $msg = JText::_('COM_REDSHOP_DUPLICATE_FIELDNAME' );
//			$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport_detail&task=edit&cid[]='.$cid[0], $msg );
			return;
		}

//		if(count($resarray)>0)
//		{
			$childelement[$post['parentsection']] = array($post['element_name'],implode(";",$resarray));
//			print_r($childelement);die();
//		}
		$session->set('childelement', $childelement);	?>
		<script language="javascript">
			window.parent.SqueezeBox.close();
		</script>
<?php
	}

	function removeIpAddress()
	{
		$xmlexport_ip_id = JRequest::getVar ( 'xmlexport_ip_id',0 );

		$model = $this->getModel ( 'xmlexport_detail' );
		$model->deleteIpAddress ( $xmlexport_ip_id );
		die();
	}

	function remove()
	{
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLEXPORT_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
	}

	function cancel()
	{
		$option = JRequest::getVar('option','','request','string');
		$session = JFactory::getSession();
		$session->set ( 'childelement', NULL );
		$msg = JText::_('COM_REDSHOP_XMLEXPORT_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
	}
	/**
	 * logic for auto synchronize
	 *
	 * @access public
	 * @return void
	 */
	function auto_syncpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE' ) );
		}
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->auto_syncpublish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_ENABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
	}

	/**
	 * logic for disable auto sync
	 *
	 * @access public
	 * @return void
	 */
	function auto_syncunpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE' ) );
		}
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->auto_syncpublish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_DISABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
	}

	/**
	 * logic for use to all user
	 *
	 * @access public
	 * @return void
	 */
	function usetoallpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_USE_EXPORTFILE_TO_ALL' ) );
		}
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->usetoallpublish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_EXPORTFILE_USE_TO_ALL_ENABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
	}

	/**
	 * logic for disable use to all user
	 *
	 * @access public
	 * @return void
	 */
	function usetoallunpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_USE_EXPORTFILE_TO_ALL' ) );
		}
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->usetoallpublish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_EXPORTFILE_USE_TO_ALL_DISABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=xmlexport',$msg );
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
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLEXPORT_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=xmlexport',$msg );
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
		$model = $this->getModel ( 'xmlexport_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_XMLEXPORT_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=xmlexport',$msg );
	}
}?>
