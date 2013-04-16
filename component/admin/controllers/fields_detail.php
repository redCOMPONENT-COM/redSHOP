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

class fields_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'fields_detail');
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
		$field_desc = JRequest::getVar('field_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["field_desc"] = $field_desc;

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['field_name'] = strtolower($post['field_name']);

		$post['field_name'] = str_replace(" ", "_", $post['field_name']);

		// Set 'rs' prefix to field name
		list($key) = explode("_", $post['field_name']);

		if ($key != 'rs')
		{
			$post['field_name'] = "rs_" . $post['field_name'];
		}

		$post['field_id'] = $cid[0];

		$model = $this->getModel('fields_detail');

		$fieldexists = $model->checkFieldname($post['field_name'], $post ['field_id']);

		if ($fieldexists)
		{
			$msg = JText::_('COM_REDSHOP_FIELDS_ALLREADY_EXIST');
			$this->setRedirect('index.php?option=' . $option . '&view=fields_detail&task=edit&cid[]=' . $cid[0], $msg);

			return;
		}
		elseif ($row = $model->store($post))
		{
			if ($post["field_type"] == 0 || $post["field_type"] == 1 || $post["field_type"] == 2)
			{
				$aid[] = $row->field_id;
				$model->field_delete($aid, 'field_id');
			}
			else
			{
				$model->field_save($row->field_id, $post);
			}

			$msg = JText::_('COM_REDSHOP_FIELDS_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_FIELDS_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=fields_detail&task=edit&cid[]=' . $row->field_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
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

		$model = $this->getModel('fields_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('fields_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('fields_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_FIELD_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	public function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('fields_detail');

		if ($model->saveorder($cid))
		{
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
		$option = JRequest::getVar('option');

		$model = $this->getModel('fields_detail');
		$model->move(-1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}

	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderdown()
	{
		$option = JRequest::getVar('option');

		$model = $this->getModel('fields_detail');
		$model->move(1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
	}
}
