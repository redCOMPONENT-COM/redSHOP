<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerTax_group_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'tax_group_detail');
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
		$post = $this->input->post->getArray();

		$model = $this->getModel('tax_group_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAX_GROUP_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=tax_group_detail&task=edit&cid[]=' . $row->tax_group_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=tax_group', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!is_array($cid) && $cid == 1)
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
		}

		elseif (in_array(1, $cid))
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED');
		}

		else
		{
			$model = $this->getModel('tax_group_detail');

			if (!$model->delete($cid))
			{
				echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
			}

			$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=tax_group', $msg);
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('tax_group_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_PUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=tax_group', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('tax_group_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_UNPUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=tax_group', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_TAX_GROUP_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=tax_group', $msg);
	}
}
