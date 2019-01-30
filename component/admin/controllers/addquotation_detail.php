<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Add quotation detail controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Addquotation_Detail
 * @since       2.0.2
 */
class RedshopControllerAddquotation_Detail extends RedshopController
{
	/**
	 * @var JDatabaseDriver
	 */
	protected $_db;

	/**
	 * RedshopControllerAddquotation_Detail constructor.
	 *
	 * @param   array $default
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->input->set('hidemainmenu', 1);
		$this->_db = JFactory::getDbo();
	}

	/**
	 * Method for apply
	 *
	 * @return  void
	 */
	public function apply()
	{
		$this->save(0, 1);
	}

	/**
	 * Method for save
	 *
	 * @param   integer  $send
	 * @param   integer  $apply
	 *
	 * @return   boolean
	 */
	public function save($send = 0, $apply = 0)
	{
		$post = $this->input->post->getArray();

		$cid                   = $this->input->post->get('cid', array(0), 'array');
		$post ['quotation_id'] = $cid [0];

		/** @var RedshopModelAddquotation_detail $model */
		$model = $this->getModel('addquotation_detail');

		if (!$post['users_info_id'])
		{
			$name             = $post['firstname'] . ' ' . $post['lastname'];
			$post['usertype'] = "Registered";
			$post['email']    = $post['user_email'];
			$post['username'] = $this->input->post->getUsername('username', '');
			$post['name']     = $name;
			$this->input->set('password1', $post['password']);

			$post['groups'] = array(0 => 2);

			$date                 = JFactory::getDate();
			$post['registerDate'] = $date->toSql();
			$post['block']        = 0;

			// Get Admin order detail Model Object
			/** @var RedshopModelUser_detail $userModel */
			$userModel = RedshopModel::getInstance('User_detail', 'RedshopModel');

			// Call Admin order detail Model store function for Billing
			$user = $userModel->storeUser($post);

			if (!$user)
			{
				$errorMsg = $this->_db->getErrorMsg();
				$link     = JRoute::_('index.php?option=com_redshop&view=addquotation_detail', false);
				$this->setRedirect($link, $errorMsg);

				return false;
			}

			$post['user_id'] = $user->user_id;
			$user_id         = $user->user_id;

			$user_data             = new stdClass;
			$post['users_info_id'] = $user_data->users_info_id;

			if (count($user) <= 0)
			{
				$this->setRedirect('index.php?option=com_redshop&view=quotaion_detail&user_id=' . $user_id);
			}
		}

		$orderItem          = Redshop\Order\Helper::redesignProductItem($post);
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

			if ($apply == 1)
			{
				$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&task=edit&cid[]=' . $row->quotation_id, $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
			$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
		}
	}

	public function send()
	{
		$this->save(1);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
	}

	public function displayOfflineSubProperty()
	{
		$get = $this->input->get->getArray();

		/** @var RedshopModelAddquotation_detail $model */
		$model = $this->getModel('Addquotation_Detail');

		$productId   = $get['product_id'];
		$accessoryId = $get['accessory_id'];
		$attributeId = $get['attribute_id'];
		$uniqueId    = $get['unique_id'];

		$propertiesId = explode(",", $get['property_id']);

		$response = '';

		foreach ($propertiesId as $propertyId)
		{
			$response .= $model->replaceSubPropertyData($productId, $accessoryId, $attributeId, $propertyId, $uniqueId);
		}

		echo $response;

		JFactory::getApplication()->close();
	}
}
