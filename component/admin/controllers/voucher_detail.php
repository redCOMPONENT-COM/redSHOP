<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerVoucher_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'voucher_detail');
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

		$cid = $this->input->post->get('cid', array(0), 'array');
		$post['start_date'] = strtotime($post['start_date']);

		if ($post ['end_date'])
		{
			$post ['end_date'] = strtotime($post ['end_date']) + (23 * 59 * 59);
		}

		if ('' == trim($post['voucher_code']))
		{
			$app->redirect('index.php?option=com_redshop&view=voucher_detail&task=edit&cid=' . $post ['voucher_id'], JText::_('COM_REDSHOP_VOUCHER_CODE_IS_EMPTY'));
		}

		if ('' == trim($post['container_product']))
		{
			$app->redirect('index.php?option=com_redshop&view=voucher_detail&task=edit&cid=' . $post ['voucher_id'], JText::_('COM_REDSHOP_VOUCHER_PRODUCT_IS_EMPTY'));
		}

		$post ['voucher_id'] = $cid[0];
		$model = $this->getModel('voucher_detail');

		if ($post['old_voucher_code'] != $post['voucher_code'])
		{
			$code = $model->checkduplicate($post['voucher_code']);

			if ($code)
			{
				$msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
				$app->Redirect('index.php?option=com_redshop&view=voucher_detail&task=edit&cid=' . $post ['voucher_id'], $msg);
			}
		}

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_SAVED');

		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_VOUCHER_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=voucher_detail&task=edit&cid[]=' . $row->voucher_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=voucher', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('voucher_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=voucher', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=voucher', $msg);
	}
}
