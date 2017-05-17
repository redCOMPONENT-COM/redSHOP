<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopControllerTemplate_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'template_detail');
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
		$app  = JFactory::getApplication();
		$post = $this->input->post->getArray();

		$post["template_desc"] = $this->input->post->get('template_desc', '', 'raw');

		$model = $this->getModel('template_detail');
		$row   = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_TEMPLATE_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEMPLATE');
		}

		$showbuttons  = $app->input->getInt('showbuttons', 0);

		if ($apply || $showbuttons)
		{
			$returnUrl = 'index.php?option=com_redshop&view=template_detail&task=edit&cid[]=' . $row->template_id;

			if ($app->input->getInt('tmodeClicked'))
			{
				if ($showbuttons)
				{
					$returnUrl .= '&showbuttons=1&tmpl=component';
				}

				$returnUrl .= '&templateMode=' . $post['templateMode'] . '#editor';
			}

			$this->setRedirect($returnUrl, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=template', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('template_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setMessage(JText::plural('COM_REDSHOP_N_ITEMS_DELETED', count($cid)));

		$this->setRedirect('index.php?option=com_redshop&view=template');
	}

	public function cancel()
	{


		$model = $this->getModel('template_detail');
		$model->checkin();

		$this->setRedirect('index.php?option=com_redshop&view=template');
	}

	public function copy()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		$model = $this->getModel('template_detail');

		if ($model->copy($cid))
		{
			$msg = array();

			foreach($model->names As $names)
			{
				$msg[] = JText::sprintf('COM_REDSHOP_TEMPLATE_COPIED', $names[0], $names[1]);
			}

			$msg = implode('<br />',$msg);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_TEMPLATE');
		}

		$this->setRedirect('index.php?option=com_redshop&view=template', $msg);
	}
}
