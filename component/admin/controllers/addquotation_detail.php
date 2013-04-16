<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/product.php';

class addquotation_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		JRequest::setVar('hidemainmenu', 1);
	}

	public function save($send = 0)
	{
		$post = JRequest::get('post');
		$adminproducthelper = new adminproducthelper;

		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['quotation_id'] = $cid [0];
		$model = $this->getModel('addquotation_detail');

		if (!$post['users_info_id'])
		{
			$name = $post['firstname'] . ' ' . $post['lastname'];
			$post['usertype'] = "Registered";
			$post['email'] = $post['user_email'];
			$post['username'] = JRequest::getVar('username', '', 'post', 'username');
			$post['name'] = $name;
			JRequest::getVar('password1', $post['password']);

			$post['groups'] = array(0 => 2);

			$date = JFactory::getDate();
			$post['registerDate'] = $date->toMySQL();
			$post['block'] = 0;

			// Get Admin order detail Model Object
			$usermodel = JModel::getInstance('user_detail', 'user_detailModel');

			// Call Admin order detail Model store function for Billing
			$user = $usermodel->storeUser($post);

			if (!$user)
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$post['user_id'] = $user->user_id;
			$user_id = $user->user_id;

			$user_data = new stdClass;
			$post['users_info_id'] = $user_data->users_info_id;

			if (count($user) <= 0)
			{
				$this->setRedirect('index.php?option=' . $option . '&view=quotaion_detail&user_id=' . $user_id);
			}
		}

		$orderItem = $adminproducthelper->redesignProductItem($post);
		$post['order_item'] = $orderItem;

		$post['user_info_id'] = $post['users_info_id'];

		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SAVED');

			if ($send == 1)
			{
				if ($model->sendQuotationMail($row->quotation_id))
				{
					$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
				}
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=quotation', $msg);
	}

	public function send()
	{
		$this->save(1);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option', '', 'request', 'string');
		$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=quotation', $msg);
	}

	public function displayOfflineSubProperty()
	{
		$get = JRequest::get('get');
		$model = $this->getModel('addquotation_detail');

		$product_id = $get['product_id'];
		$accessory_id = $get['accessory_id'];
		$attribute_id = $get['attribute_id'];
		$user_id = $get['user_id'];
		$unique_id = $get['unique_id'];

		$propid = explode(",", $get['property_id']);

		$response = "";

		for ($i = 0; $i < count($propid); $i++)
		{
			$property_id = $propid[$i];
			$response .= $model->replaceSubPropertyData($product_id, $accessory_id, $attribute_id, $property_id, $user_id, $unique_id);
		}

		echo $response;
		exit;
	}
}
