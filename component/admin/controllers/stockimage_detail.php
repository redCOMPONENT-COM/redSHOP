<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerStockimage_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'stockimage_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = $this->input->post->getArray();

		$cid = $this->input->post->get('cid', array(0), 'array');
		$post ['stock_amount_id'] = $cid [0];

		$model = $this->getModel('stockimage_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKIMAGE_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=stockimage', $msg);
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('stockimage_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=stockimage', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=stockimage', $msg);
	}
}
