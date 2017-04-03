<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerNewsletter_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'newsletter_detail');
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
		$post = $this->input->post->getArray();
		$body = $this->input->post->get('body', '', 'raw');
		$post["body"] = $body;

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post ['newsletter_id'] = $cid [0];

		$model = $this->getModel('newsletter_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_NEWSLETTER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=newsletter_detail&task=edit&cid[]=' . $row->newsletter_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		$val = 0;

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('newsletter_detail');

		foreach ($cid as $key => $value)
		{
			if ($value == 1)
			{
				unset($cid[$key]);
				$val = 1;
			}
		}

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		if ($val == 1)
		{
			$msg = JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER_CAN_NOT_BE_DELETED');
		}

		else
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_DELETED_SUCCESSFULLY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');
		$model = $this->getModel('newsletter_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_COPIED_WITH_SUBSCRIBER');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_NEWSLETTER');
		}

		$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
	}
}
