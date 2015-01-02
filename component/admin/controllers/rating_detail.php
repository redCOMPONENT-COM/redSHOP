<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerRating_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'rating_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		$model = $this->getModel('rating_detail');
		$userslist = $model->getuserslist();
		JRequest::setVar('userslist', $userslist);

		$product = $model->getproducts();
		JRequest::setVar('product', $product);

		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');
		$comment = JRequest::getVar('comment', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["comment"] = $comment;

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['rating_id'] = $cid [0];

		$model = $this->getModel('rating_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_RATING_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_RATING_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('rating_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
	}
}
