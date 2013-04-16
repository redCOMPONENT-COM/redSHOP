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
jimport('joomla.filesystem.file');

class shopper_group_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'shopper_group_detail');
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
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post = JRequest::get('post');
		$post["shopper_group_desc"] = JRequest::getVar('shopper_group_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["shopper_group_url"] = "";
		$post["shopper_group_id"] = $cid [0];

		if (isset($post['shopper_group_categories']) && count($post['shopper_group_categories']) > 0)
		{
			$post["shopper_group_categories"] = implode(",", $post['shopper_group_categories']);
		}
		else
		{
			$post["shopper_group_categories"] = "";
		}

		if (isset($post['shopper_group_manufactures']) && count($post['shopper_group_manufactures']) > 0)
		{
			$post["shopper_group_manufactures"] = implode(",", $post['shopper_group_manufactures']);
		}
		else
		{
			$post["shopper_group_manufactures"] = "";
		}

		$model = $this->getModel('shopper_group_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHOPPER_GROUP_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shopper_group_detail&cid[]=' . $row->shopper_group_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!is_array($cid) && ($cid == 1 || $cid == 2))
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
		}

		elseif (in_array(1, $cid))
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
		}

		elseif (in_array(2, $cid))
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
		}

		else
		{
			$model = $this->getModel('shopper_group_detail');

			if (!$model->delete($cid))
			{
				echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
			}

			$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('shopper_group_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('shopper_group_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
	}
}
