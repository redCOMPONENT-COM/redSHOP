<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerDiscount_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$layout = JRequest::getVar('layout');

		JRequest::setVar('view', 'discount_detail');

		if ($layout == 'product')
		{
			JRequest::setVar('layout', 'product');

		}
		else
		{
			JRequest::setVar('layout', 'default');
		}

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
		$cid  = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['start_date'] = strtotime($post['start_date']);
		$post['end_date']   = strtotime($post['end_date']) + (23 * 59 * 59);

		$model = $this->getModel('discount_detail');

		$layout = JRequest::getVar('layout');

		$post['category_ids'] = ($post['category_ids']) ? implode(',', $post['category_ids']) : '';

		if (isset($post['shopper_group_id']) === true)
		{
			if (isset($layout) && $layout == 'product')
			{
				$post['discount_product_id'] = $cid[0];
				$row                         = $model->storeDiscountProduct($post);
				$did                         = $row->discount_product_id;
			}
			else
			{
				$post['discount_id'] = $cid[0];
				$row                 = $model->store($post);
				$did                 = $row->discount_id;
			}

			if ($row)
			{
				$model->saveShoppers($did, $post['shopper_group_id']);
				$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL');
			}
		}
		else
		{
			$row                      = new stdClass;
			$row->discount_product_id = $cid[0];
			$msg                      = JText::_('COM_REDSHOP_SELECT_SHOPPER_GROUP');
		}

		if ($apply == 1)
		{
			if (isset($layout) && $layout == 'product')
			{
				$this->setRedirect('index.php?option=com_redshop&view=discount_detail&layout=product&task=edit&cid[]=' . $row->discount_product_id, $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=com_redshop&view=discount_detail&task=edit&cid[]=' . $row->discount_id, $msg);
			}
		}

		else
		{
			if (isset($layout) && $layout == 'product')
			{
				$this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
			}

			else
			{
				$this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
			}
		}
	}

	public function cancel()
	{
		$layout = JRequest::getVar('layout');
		$msg    = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
		}
	}
}
