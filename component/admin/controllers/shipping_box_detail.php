<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerShipping_box_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'shipping_box_detail');
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
		$post  = $this->input->post->getArray();
		$model = $this->getModel('shipping_box_detail');
		$row   = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_BOX_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_BOX');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping_box_detail&task=edit&cid[]=' . $row->shipping_box_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping_box', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('shipping_box_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping_box');
	}

	public function cancel()
	{


		$this->setRedirect('index.php?option=com_redshop&view=shipping_box');
	}
}
