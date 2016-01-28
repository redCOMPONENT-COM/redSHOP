<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminExtra_field');

class RedshopControllerManufacturer_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'manufacturer_detail');
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
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);
		$manufacturer_desc = JRequest::getVar('manufacturer_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["manufacturer_desc"] = $manufacturer_desc;



		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['manufacturer_id'] = $cid [0];

		$model = $this->getModel('manufacturer_detail');

		if ($row = $model->store($post))
		{
			$field = new extra_field;
			$field->extra_field_save($post, "10", $row->manufacturer_id);

			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MANUFACTURER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
		}
	}

	public function remove()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function publish()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function unpublish()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('manufacturer_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}

	public function copy()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('manufacturer_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_MANUFACTURER_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=manufacturer', $msg);
	}
}
