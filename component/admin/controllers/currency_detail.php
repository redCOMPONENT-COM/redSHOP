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

class currency_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'currency_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		$model = $this->getModel('currency_detail');

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		$currency_name = JRequest::getVar('currency_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["currency_name"] = $currency_name;
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['currency_id'] = $cid [0];
		$model = $this->getModel('currency_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CURRENCY_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=currency_detail&task=edit&cid[]=' . $row->currency_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
		}
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('currency_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CURRENCY_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=currency', $msg);
	}
}
