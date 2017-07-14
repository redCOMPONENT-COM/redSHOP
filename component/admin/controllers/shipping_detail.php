<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerShipping_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'shipping_detail');
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
		$model = $this->getModel('shipping_detail');
		$row   = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_shipping');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping_detail&task=edit&cid[]=' . $post['extension_id'], $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
		}
	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_redshop&view=shipping');
	}
}
