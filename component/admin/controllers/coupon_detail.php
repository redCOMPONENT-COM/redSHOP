<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerCoupon_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'coupon_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		$model = $this->getModel('coupon_detail');
		$userslist = $model->getuserslist();
		$this->input->set('userslist', $userslist);

		$product = $model->getproducts();
		$this->input->set('product', $product);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$app             = JFactory::getApplication();
		$post            = $this->input->post->getArray();
		$comment         = $this->input->post->get('comment', '', 'raw');
		$post["comment"] = $comment;

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post ['coupon_id']  = $cid [0];
		$post ['start_date'] = strtotime($post ['start_date']);

		if ($post ['end_date'])
		{
			$post ['end_date'] = strtotime($post ['end_date']) + (24 * 60 * 60) - 1;
		}

		$model = $this->getModel('coupon_detail');

		if ($post['old_coupon_code'] != $post['coupon_code'])
		{
			if ($model->checkduplicate($post['coupon_code']))
			{
				$msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
				$app->Redirect('index.php?option=com_redshop&view=coupon_detail&task=edit&cid=' . $post ['coupon_id'], $msg);
			}
		}

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUPON_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=coupon_detail&task=edit&cid[]=' . $row->coupon_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=coupon', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('coupon_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=coupon', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_COUPON_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=coupon', $msg);
	}
}
