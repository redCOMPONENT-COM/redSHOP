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

class coupon_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'coupon_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		$model = $this->getModel('coupon_detail');
		$userslist = $model->getuserslist();
		JRequest::setVar('userslist', $userslist);

		$product = $model->getproducts();
		JRequest::setVar('product', $product);

		parent::display();
	}

	public function save()
	{
		$app = JFactory::getApplication();
		$post = JRequest::get('post');
		$comment = JRequest::getVar('comment', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["comment"] = $comment;

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['coupon_id'] = $cid [0];
		$post ['start_date'] = strtotime($post ['start_date']);

		if ($post ['end_date'])
		{
			$post ['end_date'] = strtotime($post ['end_date']) + (23 * 59 * 59);
		}

		$model = $this->getModel('coupon_detail');

		if ($post['old_coupon_code'] != $post['coupon_code'])
		{
			if ($model->checkduplicate($post['coupon_code']))
			{
				$msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
				$app->Redirect('index.php?option=' . $option . '&view=coupon_detail&task=edit&cid=' . $post ['coupon_id'], $msg);
			}
		}

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUPON_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('coupon_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('coupon_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('coupon_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=coupon', $msg);
	}
}
