<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerSample_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'sample_detail');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = $this->input->post->getArray();

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post ['sample_id'] = $cid [0];
		$link               = 'index.php?option=com_redshop&view=sample';

		$model = $this->getModel('sample_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SAMPLE_DETAIL');
		}

		$this->setRedirect($link, $msg);
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('sample_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=sample', $msg);
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('sample_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_PUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=sample', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('sample_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_UNPUBLISHED_SUCCESFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=sample', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_SAMPLE_DETAIL_EDITING_CANCELLED');

		$this->setRedirect('index.php?option=com_redshop&view=sample', $msg);
	}
}
