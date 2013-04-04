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

class zipcode_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'zipcode_detail');
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

		$city_name = JRequest::getVar('city_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["city_name"] = $city_name;
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['zipcode_id'] = $cid [0];
		$model = $this->getModel('zipcode_detail');

		if ($post["zipcode_to"] == "")
		{
			$row = $model->store($post);
		}
		else
		{
			for ($i = $post["zipcode"]; $i <= $post["zipcode_to"]; $i++)
			{
				$post['zipcode'] = $i;
				$row = $model->store($post);
			}
		}

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_IN_ZIPCODE_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=zipcode_detail&task=edit&cid[]=' . $row->zipcode_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=zipcode', $msg);
		}
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=zipcode', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('zipcode_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=zipcode', $msg);
	}
}
