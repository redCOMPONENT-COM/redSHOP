<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerFields extends RedshopController
{
	/**
	 * Save custom field ordering
	 *
	 * @return  void
	 */
	public function saveorder()
	{
		$cid   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('fields');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}

	/**
	 * Move Ordering up
	 *
	 * @return    void
	 */
	public function orderup()
	{
		$model = $this->getModel('fields');
		$model->orderup();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}

	/**
	 * Move Ordering Down
	 *
	 * @return    void
	 */
	public function orderdown()
	{
		$model = $this->getModel('fields');
		$model->orderdown();

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}

	/**
	 * Publish the fields
	 *
	 * @return  void
	 */
	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('fields');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}

	/**
	 * Unpublish the fields
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('fields');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_FIELD_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=fields', $msg);
	}
}
