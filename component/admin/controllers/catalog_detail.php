<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerCatalog_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'catalog_detail');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');



		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['catalog_id'] = $cid [0];
		$link = 'index.php?option=com_redshop&view=catalog';


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

	public function remove()
	{


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
		$this->setRedirect('index.php?option=com_redshop&view=catalog', $msg);

	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_EDITING_CANCELLED');

		$this->setRedirect('index.php?option=com_redshop&view=catalog', $msg);
	}
}
