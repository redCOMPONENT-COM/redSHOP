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

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );

jimport( 'joomla.application.component.controller' );
/**
 * Account Billing Address Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class account_billtoController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
		$this->registerTask ( 'add', 'edit' );
		$this->registerTask ( '', 'edit' );
	}
	/**
	 * Method to edit billing Address
	 *
	 */
	function edit()
	{
		$user = &JFactory::getUser();
		$order_functions = new order_functions();
		$billingaddresses = $order_functions->getBillingAddress($user->id);
		$GLOBALS['billingaddresses'] = $billingaddresses;

		$task = JRequest::getVar('submit','post');
		if($task == 'Cancel'){
			$this->registerTask( 'save'  , 'cancel' );
		}
		parent::display ();
	}
	/**
	 * Method to save Billing Address
	 *
	 */
	function save()
	{
		$user = &JFactory::getUser();
		$post = JRequest::get('post');
		$return = JRequest::getVar('return');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$post['users_info_id'] = $cid[0];
		$post['id'] = $post['user_id'];
		$post['address_type'] = "BT";
		$post['email'] = $post['email1'];
		$post['password'] = JRequest::getVar ( 'password1', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['password2'] = JRequest::getVar ( 'password2', '', 'post', 'string', JREQUEST_ALLOWRAW );
		if(isset($user->username))
		{
			$post['username'] = $user->username;
		}

		$model = $this->getModel('account_billto');
		if ($reduser = $model->store ( $post ))
		{
			$msg = JText::_( 'BILLING_INFORMATION_SAVE');
		}else{
			$msg = JText::_( 'ERROR_SAVING_BILLING_INFORMATION');
		}
		$setexit = JRequest::getInt('setexit',1);
		$link ='';
		if($return != "")
		{
			$link = JRoute::_('index.php?option='.$option.'&view='.$return.'&Itemid='.$Itemid,false);
			if(!isset($setexit) || $setexit!=0)
			{
			?>
			<script language="javascript">
				window.parent.location.href="<?php echo $link ?>";
			</script>
			<?php
			exit;
			}
		}
		else
		{
			$link = JRoute::_('index.php?option='.$option.'&view=account&Itemid='.$Itemid,false);
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

		$msg = JText::_( 'BILLING_INFORMATION_EDITING_CANCELLED');

	    $return = JRequest::getVar('return');
	    $setexit = JRequest::getInt('setexit',1);
	    $link = '';
		if($return != "")
		{
			$link = JRoute::_('index.php?option='.$option.'&view='.$return.'&Itemid='.$Itemid,false);
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
			$link = 'index.php?option='.$option.'&view=account&Itemid='.$Itemid;
		}
		$this->setRedirect( $link,$msg);
	}
}	?>