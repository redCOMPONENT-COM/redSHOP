<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Category controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Category
 * @since       2.0.4
 */
class RedshopControllerCategory extends RedshopControllerForm
{
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   2.0.0.2
	 */
	public function save($key = null, $urlVar = null)
	{
		$task                             = $this->getTask();
		$input                            = JFactory::getApplication()->input;
		$post                             = $input->post->get('jform', array(), 'array');
		$post['product_accessory']        = $input->post->get('product_accessory', array(), 'array');
		$post['old_image']                = $input->post->getString('old_image');
		$post['image_delete']             = $input->post->getString('image_delete');
		$post['image_back_delete']        = $input->post->getString('image_back_delete');
		$post['category_full_image']      = $input->post->getString('category_full_image');
		$post['category_back_full_image'] = $input->post->getString('category_back_full_image');

		if (!empty($post["more_template"]) && is_array($post["more_template"]))
		{
			$post["more_template"] = implode(",", $post["more_template"]);
		}

		$model = $this->getModel();

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATEGORY_DETAIL');
		}

		if ($task == 'save2new')
		{
			$this->setRedirect('index.php?option=com_redshop&task=category.add', $msg);
		}
		elseif ($task == 'apply')
		{
			$this->setRedirect('index.php?option=com_redshop&task=category.edit&id=' . ($post['id'] ? $post['id'] : $row->id), $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
		}
	}

	/**
	 * Method to remove records.
	 *
	 * @return  mixed
	 */
	public function remove()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel();

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

		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}

	/**
	 * Method to order up.
	 *
	 * @return  mixed
	 */
	public function orderUp()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');
		$model = $this->getModel('category');
		$model->orderUp($cid);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}

	/**
	 * Method to order down.
	 *
	 * @return  mixed
	 */
	public function orderDown()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');
		$model = $this->getModel('category');
		$model->orderDown($cid);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}

	/**
	 * Method to save order.
	 *
	 * @return  mixed
	 */
	public function saveorder()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}

	/**
	 * Method to copy record.
	 *
	 * @return  mixed
	 */
	public function copy()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->post->get('cid', array(), 'array');
		$model = $this->getModel('category');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_CATEGORY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=categories', $msg);
	}
}
