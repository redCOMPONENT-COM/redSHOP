<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerXmlimport_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'xmlimport_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function xmlimport()
	{
		$this->save(1);
	}

	public function save($import = 0)
	{
		$post = $this->input->post->getArray();

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post['xmlimport_id'] = $cid [0];
		$model = $this->getModel('xmlimport_detail');

		if ($post['xmlimport_id'] == 0)
		{
			$post['xmlimport_date'] = time();
		}

		$row = $model->store($post, $import);

		if ($row)
		{
			if ($import == 1)
			{
				$msg = JText::_('COM_REDSHOP_XMLIMPORT_FILE_SUCCESSFULLY_SYNCHRONIZED');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_SAVED');
			}
		}
		else
		{
			if ($import == 1)
			{
				$msg = JText::_('COM_REDSHOP_ERROR_XMLIMPORT_FILE_SYNCHRONIZED');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_XMLIMPORT_DETAIL');
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	public function remove()
	{

		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('xmlimport_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	public function auto_syncpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE'));
		}

		$model = $this->getModel('xmlimport_detail');

		if (!$model->auto_syncpublish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_ENABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	public function auto_syncunpublish()
	{

		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE'));
		}

		$model = $this->getModel('xmlimport_detail');

		if (!$model->auto_syncpublish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_DISABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	/**
	 * logic for publish
	 *
	 * @access public
	 * @return void
	 */
	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('xmlimport_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_XMLIMPORT_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}

	/**
	 * logic for unpublish
	 *
	 * @access public
	 * @return void
	 */
	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('xmlimport_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_XMLIMPORT_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=xmlimport', $msg);
	}
}
