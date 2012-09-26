<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class mass_discount_detailController extends RedshopCoreController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'mass_discount_detail');
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
		$post   = $this->input->getArray($_POST);
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		$post ['discount_product'] = $post ['container_product'];

		$post ['discount_startdate'] = strtotime($post ['discount_startdate']);
		$post ['discount_enddate']   = strtotime($post ['discount_enddate']) + (23 * 59 * 59);

		$model = $this->getModel('mass_discount_detail');

		$post ['mass_discount_id'] = $cid[0];

		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL');
		}
		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=mass_discount_detail&task=edit&cid[]=' . $row->mass_discount_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=mass_discount', $msg);
		}
	}

	public function remove()
	{
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('mass_discount_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=' . $option . '&view=mass_discount', $msg);
	}

	public function cancel()
	{
		$option = $this->input->get('option');

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=mass_discount', $msg);
	}
}
