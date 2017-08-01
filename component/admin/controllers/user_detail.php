<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
		$this->input->set('view', 'user_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$app      = JFactory::getApplication();
		$post     = $this->input->post->getArray();
		$model    = $this->getModel('user_detail');
		$shipping = isset($post["shipping"]) ? true : false;
		$app->setUserState('com_redshop.user_detail.data', $post);

		if ($row = $model->store($post))
		{
			$this->setMessage(JText::_('COM_REDSHOP_USER_DETAIL_SAVED'));
			$app->setUserState('com_redshop.fields_detail.data', "");
		}
		else
		{
			$this->setMessage(JText::_('COM_REDSHOP_ERROR_SAVING_USER_DETAIL'), 'error');
		}

		if ($shipping)
		{
			$info_id = $this->input->getString('info_id', '');
			$link    = 'index.php?option=com_redshop&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id;
		}
		else
		{
			if ($apply == 1)
			{
				$link = RedshopHelperUtility::getSSLLink(
					'index.php?option=com_redshop&view=user_detail&task=edit&cid[]=' . $row->users_info_id
				);

				if ($this->input->post->get('add_shipping') != null)
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

		$this->setRedirect($link);
	}

	public function remove()
	{
		$shipping            = $this->input->getString('shipping', '');
		$cid                 = $this->input->get('cid', array(0), 'array');
		$delete_joomla_users = $this->input->getBool('delete_joomla_users', false);

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
			$info_id = $this->input->getInt('info_id');
			$this->setRedirect('index.php?option=com_redshop&view=user_detail&task=edit&cancel=1&cid[]=' . $info_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=user', $msg);
		}
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

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
		$cid = $this->input->post->get('cid', array(0), 'array');

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

	/**
	 * Cancel edit user_detail
	 *
	 * @return   void
	 *
	 * @since version
	 */
	public function cancel()
	{
		$shipping = $this->input->getString('shipping', '');
		$info_id  = $this->input->getString('info_id', '');
		JFactory::getApplication()->setUserState('com_redshop.user_detail.data', "");

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
		$this->setRedirect($link);
	}

	public function order()
	{
		$user_id = $this->input->getInt('user_id', 0);
		$this->setRedirect('index.php?option=com_redshop&view=addorder_detail&user_id=' . $user_id);
	}

	public function validation()
	{
		$json             = $this->input->get('json', '');
		$decoded          = json_decode($json);
		$model            = $this->getModel('user_detail');
		$username         = $model->validate_user($decoded->username, $decoded->userid);
		$email            = $model->validate_email($decoded->email, $decoded->userid);
		$json             = array();
		$json['ind']      = $decoded->ind;
		$json['username'] = $username;
		$json['email']    = $email;
		$encoded          = json_encode($json);
		die($encoded);
	}

	/**
	 * Validate username method
	 *
	 * @return  void
	 *
	 * @since   2.0.0.4
	 */
	public function ajaxValidationUsername()
	{
		RedshopHelperAjax::validateAjaxRequest('get');

		$username = $this->input->getString('username', '');
		$user_id = $this->input->getInt('user_id', 0);

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
