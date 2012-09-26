<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');

class manufacturer_detailController extends RedshopCoreController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'manufacturer_detail');
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
		$post                      = $this->input->getArray($_POST);
		$post["manufacturer_desc"] = $this->input->post->getString('manufacturer_desc', '');
		$option                    = $this->input->get('option');
		$cid                       = $this->input->post->get('cid', array(0), 'array');

		$post ['manufacturer_id'] = $cid [0];

		$model = $this->getModel('manufacturer_detail');

		if ($row = $model->store($post))
		{

			$field = new extra_field();
			$field->extra_field_save($post, "10", $row->manufacturer_id); /// field_section 6 :Userinformations

			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');
		}
		else
		{

			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MANUFACTURER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
		}
	}

	public function remove()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	public function publish()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	public function unpublish()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	public function cancel()
	{
		$option = $this->input->get('option');

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	public function copy()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		$model = $this->getModel('manufacturer_detail');

		if ($model->copy($cid))
		{

			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_COPIED');
		}
		else
		{

			$msg = JText::_('COM_REDSHOP_ERROR_COPING_MANUFACTURER_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
		$option = $this->input->get('option');

		$model = $this->getModel('manufacturer_detail');
		$model->move(-1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	public function orderdown()
	{
		$option = $this->input->get('option');
		$model  = $this->getModel('manufacturer_detail');
		$model->move(1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	public function saveorder()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(), 'array');
		$order  = $this->input->post->get('order', array(), 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('manufacturer_detail');
		$model->saveorder($cid);

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
	}
}
