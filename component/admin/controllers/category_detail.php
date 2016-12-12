<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerCategory_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'category_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function save2new()
	{
		$this->save(2);
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		$category_description = $this->input->post->get('category_description', '', 'raw');
		$category_short_description = $this->input->post->get('category_short_description', '', 'raw');

		$post["category_description"] = $category_description;

		$post["category_short_description"] = $category_short_description;

		if (is_array($post["category_more_template"]))
		{
			$post["category_more_template"] = implode(",", $post["category_more_template"]);
		}

		$cid = $this->input->post->get('cid', array(0), 'array');
		$post ['category_id'] = $cid [0];
		$model = $this->getModel('category_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATEGORY_DETAIL');
		}

		if ($apply == 2)
		{
			$this->setRedirect('index.php?option=com_redshop&view=category_detail&task=add', $msg);
		}
		elseif ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=category_detail&task=edit&cid[]=' . $row->category_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('category_detail');

		if (!$model->delete($cid))
		{
			$msg = "";

			if ($model->getError() != "")
			{
				JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('category_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('category_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function orderup()
	{
		$model = $this->getModel('category_detail');
		$model->orderup();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function orderdown()
	{
		$model = $this->getModel('category_detail');
		$model->orderdown();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function saveorder()
	{
		$cid = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');
		$model = $this->getModel('category_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_CATEGORY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}
}
