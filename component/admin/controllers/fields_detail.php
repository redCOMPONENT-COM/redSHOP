<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerFields_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'fields_detail');
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
		$post               = $this->input->post->getArray();
		$field_desc         = $this->input->post->get('field_desc', '', 'raw');
		$post["field_desc"] = $field_desc;

		$cid                = $this->input->post->get('cid', array(0), 'array');

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
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_FIELDS_ALLREADY_EXIST'), 'error');
			$this->setRedirect('index.php?option=com_redshop&view=fields_detail&task=edit&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_redshop&view=fields_detail&task=edit&cid[]=' . $row->field_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('fields_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_FIELD_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}
}
