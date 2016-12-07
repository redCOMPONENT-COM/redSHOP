<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerWrapper_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'wrapper_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$showall = JRequest::getVar('showall', '0');
		$tmpl = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$post = JRequest::get('post');
		$post['product_id'] = (isset($post['container_product'])) ? explode(',', $post['container_product']) : 0;
		$product_id = JRequest::getInt('product_id', 0);

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['wrapper_id'] = $cid [0];

		$model = $this->getModel('wrapper_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_WRAPPER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=wrapper_detail&task=edit&cid[]=' . $row->wrapper_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
		}
	}

	public function cancel()
	{
		$showall = JRequest::getVar('showall', '0');
		$tmpl = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = JRequest::getVar('product_id');

		$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}
}
