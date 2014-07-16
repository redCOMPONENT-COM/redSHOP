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
 * catalog_detailController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class catalog_detailController extends JController
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
		JRequest::setVar('view', 'catalog_detail');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * save
	 */
	public function save()
	{
		$post = JRequest::get('post');

		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['catalog_id'] = $cid [0];
		$link = 'index.php?option=' . $option . '&view=catalog';


		$model = $this->getModel('catalog_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATALOG_DETAIL');
		}

		$this->setRedirect($link, $msg);
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

		$model = $this->getModel('catalog_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=catalog', $msg);

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

		$model = $this->getModel('catalog_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=catalog', $msg);
	}

	/**
	 * unpublish
	 */
	public function unpublish()
	{
		$option = JRequest::getVar('option');
		$layout = JRequest::getVar('layout');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('catalog_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=catalog', $msg);
	}

	/**
	 * cancel
	 */
	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_EDITING_CANCELLED');

		$this->setRedirect('index.php?option=' . $option . '&view=catalog', $msg);
	}
}
