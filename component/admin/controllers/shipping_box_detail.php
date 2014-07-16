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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/template.php';

/**
 * shipping_box_detailController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class shipping_box_detailController extends JController
{
	/**
	 * __construct
	 *
	 * @param $default
	 *
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * edit
	 */
	public function edit()
	{
		JRequest::setVar('view', 'shipping_box_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * apply
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * save
	 *
	 * @param $apply
	 *
	 */
	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		$option = JRequest::getVar('option');

		$model = $this->getModel('shipping_box_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_BOX_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_BOX');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping_box_detail&task=edit&cid[]=' . $row->shipping_box_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=shipping_box', $msg);
		}
	}

	/**
	 * remove
	 */
	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('shipping_box_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_box');
	}

	/**
	 * publish
	 */
	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('shipping_box_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_box');
	}

	/**
	 * unpublish
	 */
	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('shipping_box_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_box');
	}

	/**
	 * cancel
	 */
	public function cancel()
	{
		$option = JRequest::getVar('option');

		$this->setRedirect('index.php?option=' . $option . '&view=shipping_box');
	}
}
