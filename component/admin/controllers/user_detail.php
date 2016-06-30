<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerUser_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
		$this->_table_prefix = '#__redshop_';
		$this->redhelper = redhelper::getInstance();
	}

	public function edit()
	{
		JRequest::setVar('view', 'user_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{

		$post = JRequest::get('post');

		$model = $this->getModel('user_detail');
		$shipping = isset($post["shipping"]) ? true : false;

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_USER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_USER_DETAIL');
		}

		if ($shipping)
		{
			$info_id = JRequest::getVar('info_id', '', 'request', 'string');
			$link = 'index.php?option=com_redshop&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id;
		}
		else
		{
			if ($apply == 1)
			{
				$link = RedshopHelperUtility::getSSLLink(
					'index.php?option=com_redshop&view=user_detail&task=edit&cid[]=' . $row->users_info_id
				);

				$input = JFactory::getApplication()->input;

				if ($input->post->get('add_shipping') != null)
				{
					$link = RedshopHelperUtility::getSSLLink(
						'index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=' . $row->users_info_id . '&cid[]=0'
					);
				}
			}
			else
			{
				$link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=user', 0);
			}
		}

		$this->setRedirect($link, $msg);
	}

	public function remove()
	{

		$shipping = JRequest::getVar('shipping', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'request', 'array');
		$app = JFactory::getApplication();
		$delete_joomla_users = $app->input->getBool('delete_joomla_users', false);

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('user_detail');

		if (!$model->delete($cid, $delete_joomla_users))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USER_DETAIL_DELETED_SUCCESSFULLY');

		if ($shipping)
		{
			$info_id = JRequest::getVar('info_id', '', 'request', 'int');
			$this->setRedirect('index.php?option=com_redshop&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=user', $msg);
		}
	}

	public function publish()
	{

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('user_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USER_DETAIL_PUBLISHED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=user', $msg);
	}

	public function unpublish()
	{

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('user_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USER_DETAIL_UNPUBLISHED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=user', $msg);
	}

	public function cancel()
	{

		$shipping = JRequest::getVar('shipping', '', 'request', 'string');
		$info_id = JRequest::getVar('info_id', '', 'request', 'string');

		$msg = JText::_('COM_REDSHOP_USER_DETAIL_EDITING_CANCELLED');

		if ($shipping)
		{
			$link = 'index.php?option=com_redshop&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id;
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=user';
		}

		// Not to apply ssl (passed Zero)
		$link = RedshopHelperUtility::getSSLLink($link, 0);
		$this->setRedirect($link, $msg);
	}

	public function order()
	{

		$user_id = JRequest::getVar('user_id', 0, 'request', 'string');
		$this->setRedirect('index.php?option=com_redshop&view=addorder_detail&user_id=' . $user_id);
	}

	public function validation()
	{
		$json = JRequest::getVar('json', '');
		$decoded = json_decode($json);
		$model = $this->getModel('user_detail');
		$username = $model->validate_user($decoded->username, $decoded->userid);
		$email = $model->validate_email($decoded->email, $decoded->userid);
		$json = array();
		$json['ind'] = $decoded->ind;
		$json['username'] = $username;
		$json['email'] = $email;
		$encoded = json_encode($json);
		die($encoded);
	}

	public function ajaxValidationUsername()
	{
		$username = JFactory::getApplication()->input->getString('username', '');
		$user_id = JFactory::getApplication()->input->getInt('user_id', 0);

		$model = $this->getModel('user_detail');
		$usernameAvailability = $model->validate_user($username, $user_id);

		$message = JText::_('COM_REDSHOP_USERNAME_IS_AVAILABLE');
		$type = "success";

		if ($usernameAvailability > 0)
		{
			$message = JText::_('COM_REDSHOP_USERNAME_NOT_AVAILABLE');
			$type = "error";
		}

		if ($username == "")
		{
			$message = JText::_('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME');
			$type = "error";
		}

		$result = array();
		$result['type'] = $type;
		$result['message'] = $message;

		$result = json_encode($result);
		die($result);
	}
}
