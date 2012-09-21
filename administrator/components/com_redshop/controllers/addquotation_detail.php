<?php
/**
 * @copyright  Copyright (C) 2010-2012 redCOMPONENT.com. All rights reserved.
 * @license    GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *
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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'product.php' );

class addquotation_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct ( $default );
		JRequest::setVar ( 'hidemainmenu', 1 );
	}

	function save($send=0)
	{
		$post = JRequest::get ( 'post' );
		$adminproducthelper = new adminproducthelper();

		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['quotation_id'] = $cid [0];
		$model = $this->getModel ( 'addquotation_detail' );

		global $mainframe;

		$acl	= & JFactory::getACL();

		if(!$post['users_info_id'])
		{
			$name = $post['firstname'].' '.$post['lastname'];
			$post['usertype'] = "Registered";
			$post['email'] = $post['user_email'];
			$post['username']	= JRequest::getVar('username', '', 'post', 'username');
			$post['name']	= $name;
			$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$post['gid'] = $acl->get_group_id( '', 'Registered', 'ARO' );

			$date =& JFactory::getDate();
			$post['registerDate'] = $date->toMySQL();
			$post['block'] = 0;

			# get Admin order detail Model Object
			$usermodel = & JModel::getInstance('user_detail', 'user_detailModel');

			# call Admin order detail Model store function for Billing
			$user = $usermodel->storeUser($post);

			if(!$user){
				$this->setError ( $this->_db->getErrorMsg () );
				return false;
			}

			$post['user_id'] = $user->id;
			$user_id = $user->id;

			$user_data = $model->storeShipping($post);
			$post['users_info_id'] = $user_data->users_info_id;
			if(count($user_data)<=0)
			{
				$this->setRedirect ( 'index.php?option='.$option.'&view=quotaion_detail&user_id='.$user_id );
			}
		}

		$orderItem = $adminproducthelper->redesignProductItem($post);
		$post['order_item'] = $orderItem;

		$post['user_info_id'] = $post['users_info_id'];

		$row = $model->store ( $post );
		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SAVED' );
			if($send==1)
			{
				if ($model->sendQuotationMail($row->quotation_id))
				{
					$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT' );
				}
			}
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL' );
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=quotation', $msg );
	}

	function send()
	{
		$this->save(1);
	}

	function cancel()
	{
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=quotation',$msg );
	}


	function displayOfflineSubProperty()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('addquotation_detail');

		$product_id = $get['product_id'];
		$accessory_id = $get['accessory_id'];
		$attribute_id = $get['attribute_id'];
		$user_id = $get['user_id'];
		$unique_id = $get['unique_id'];

		$propid = explode(",",$get['property_id']);

		$response = "";
		for($i=0;$i<count($propid);$i++)
		{
			$property_id = $propid[$i];
			$response .= $model->replaceSubPropertyData($product_id,$accessory_id,$attribute_id,$property_id,$user_id,$unique_id);
		}
		echo $response;
		exit;
	}
}
