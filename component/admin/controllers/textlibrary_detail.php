<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerTextlibrary_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'textlibrary_detail');
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
		$post               = $this->input->post->getArray();
		$text_field         = $this->input->post->get('text_field', '', 'raw');
		$post["text_field"] = $text_field;

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post ['textlibrary_id'] = $cid [0];

		$model = $this->getModel('textlibrary_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_TEXTLIBRARY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEXTLIBRARY_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=textlibrary_detail&task=edit&cid[]=' . $row->textlibrary_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=textlibrary', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('textlibrary_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=textlibrary', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=textlibrary', $msg);
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		$model = $this->getModel('textlibrary_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_TEXTLIBRARY_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=textlibrary', $msg);
	}
}
