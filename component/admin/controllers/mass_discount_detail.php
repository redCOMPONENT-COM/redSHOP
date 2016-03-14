<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerMass_discount_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'mass_discount_detail');
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
		$post = JRequest::get('post');



		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['discount_product'] = explode(',', $post ['container_product']);

		$post ['discount_startdate'] = strtotime($post ['discount_startdate']);
		$post ['discount_enddate'] = strtotime($post ['discount_enddate']) + (23 * 59 * 59);

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
			$this->setRedirect('index.php?option=com_redshop&view=mass_discount_detail&task=edit&cid[]=' . $row->mass_discount_id, $msg);
		}

		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=mass_discount', $msg);
		}
	}

	public function remove()
	{


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('mass_discount_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=mass_discount', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED');

		$this->setRedirect('index.php?option=com_redshop&view=mass_discount', $msg);
	}
}
