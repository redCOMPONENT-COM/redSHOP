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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
/**
 * Account shipping Address Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class account_shiptoController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * Method to save Shipping Address
	 *
	 */
	function save()
	{
	 	$post = JRequest::get('post');
		$return = JRequest::getVar('return');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$post['users_info_id'] = $cid[0];
		$post['id'] = $post['user_id'];
		$post['address_type'] = "ST";

		$model = $this->getModel('account_shipto');
		if ($reduser = $model->store ( $post ))
		{
			$post['users_info_id'] = $reduser->users_info_id;
			$msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_SAVE');
		}else{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING_INFORMATION');
		}
		$return = JRequest::getVar('return');
		$setexit = JRequest::getInt('setexit',1);

		if($return != "")
		{
			$link = JRoute::_('index.php?option='.$option.'&view='.$return.'&users_info_id='.$post['users_info_id'].'&Itemid='.$Itemid,false);

			if(!isset($setexit) || $setexit!=0)
			{
?>
			<script language="javascript">
				window.parent.location.href="<?php echo $link ?>";
			</script>

		<?php
			exit;
			}
		}else
		{
			$link = JRoute::_('index.php?option='.$option.'&view=account_shipto&Itemid='.$Itemid,false);
		}
		$this->setRedirect( $link , $msg );
	}
	/**
	 * Method to delete shipping address
	 *
	 */
	function remove()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$infoid = JRequest::getVar('infoid', '', 'request', 'string');
		$cid[0] = $infoid;

		$model = $this->getModel('account_shipto');

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_ACCOUNT_SHIPPING_DELETED_SUCCESSFULLY' );
		$return = JRequest::getVar('return');

		if($return != "")
		{
			$link = JRoute::_('index.php?option='.$option.'&view='.$return.'&Itemid='.$Itemid,false);
		}else
		{
			$link = JRoute::_('index.php?option='.$option.'&view=account_shipto&Itemid='.$Itemid,false);
		}
		$this->setRedirect( $link , $msg );
	}
	/**
	 * Method called when user pressed cancel button
	 *
	 */
	function cancel()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$post['users_info_id'] = $cid[0];

		$msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_EDITING_CANCELLED');

		$return = JRequest::getVar('return');
		$setexit = JRequest::getInt('setexit',1);
		$link = '';
		if($return != "")
		{
			$link = JRoute::_ ('index.php?option=' . $option . '&view=' . $return . '&users_info_id='.$post['users_info_id'].'&Itemid=' . $Itemid . '',false);

			if(!isset($setexit) || $setexit!=0)
			{
?>
			<script language="javascript">
				window.parent.location.href="<?php echo $link ?>";
			</script>
		<?php
			exit;
			}
		}else
		{
			$link = 'index.php?option='.$option.'&view=account_shipto&Itemid='.$Itemid;
		}
		$this->setRedirect( $link,$msg);
	}
}?>