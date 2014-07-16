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

/**
 * country_detailController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class country_detailController extends JController
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
		JRequest::setVar('view', 'country_detail');
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

		$country_name = JRequest::getVar('country_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["country_name"] = $country_name;
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['country_id'] = $cid [0];
		$model = $this->getModel('country_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUNTRY_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=country_detail&task=edit&cid[]=' . $row->country_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=country', $msg);
		}
	}

	/**
	 * cancel
	 */
	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=country', $msg);
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

		$model = $this->getModel('country_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=country', $msg);
	}
}
