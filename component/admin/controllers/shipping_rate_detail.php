<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerShipping_rate_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'shipping_rate_detail');
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

		// Include extra field class

		$post['shipping_rate_on_product'] = explode(',', $post['container_product']);
		$post["shipping_location_info"] = $this->input->post->get('shipping_location_info', '', 'raw');
		$model = $this->getModel('shipping_rate_detail');

		if ($row = $model->store($post))
		{
			$field = extra_field::getInstance();

			// Field_section 11 :Shipping
			$field->extra_field_save($post, "11", $row->shipping_rate_id);
			$msg = JText::_('COM_REDSHOP_SHIPPING_LOCATION_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
		}

		if ($apply)
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping_rate_detail&cid=' . $row->shipping_rate_id
				. '&id=' . $post['id'], $msg
			);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $post['id'], $msg);
		}
	}

	public function remove()
	{
		$post = $this->input->post->getArray();

		$cid = $this->input->post->get('cid', array(0), 'array');
		$count = count($cid);
		$model = $this->getModel('shipping_rate_detail');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}
		elseif ($count > 0)
		{
			$this->setMessage(JText::plural('COM_REDSHOP_N_ITEMS_DELETED', $count));
		}
		else
		{
			$this->setMessage(JText::_('COM_REDSHOP_N_ITEMS_DELETED_1'));
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $post['id']);
	}

	public function publish()
	{

		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('shipping_rate_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping_rate');
	}

	public function unpublish()
	{

		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('shipping_rate_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping_rate');
	}

	public function cancel()
	{
		$post = $this->input->post->getArray();

		$this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $post['id']);
	}

	public function copy()
	{
		$post = $this->input->post->getArray();

		$cid = $this->input->post->get('cid', array(0), 'array');
		$model = $this->getModel('shipping_rate_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_RATE_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $post['id'], $msg);
	}

	public function GetStateDropdown()
	{
		$get = $this->input->get->getArray();
		$model = $this->getModel('shipping_rate_detail');
		$model->GetStateDropdown($get);
		JFactory::getApplication()->close();
	}
}
