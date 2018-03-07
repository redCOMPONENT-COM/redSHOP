<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerPrices_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'prices_detail');
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

		$product_id           = $this->input->get('product_id');
		$price_quantity_start = $this->input->get('price_quantity_start');
		$price_quantity_end   = $this->input->get('price_quantity_end');

		$post['product_currency'] = Redshop::getConfig()->get('CURRENCY_CODE');
		$post['cdate']            = time();

		$cid               = $this->input->post->get('cid', array(0), 'array');
		$post ['price_id'] = $cid [0];

		$post['discount_start_date'] = strtotime($post ['discount_start_date']);

		if ($post['discount_end_date'])
		{
			$post ['discount_end_date'] = strtotime($post['discount_end_date']) + (23 * 59 * 59);
		}

		/** @var RedshopModelPrices_detail $model */
		$model = $this->getModel('prices_detail');

		$row = $model->store($post);

		if ($price_quantity_start == 0 && $price_quantity_end == 0)
		{
			if ($row)
			{
				$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_DETAIL');
			}
		}
		else
		{
			if ($price_quantity_start < $price_quantity_end)
			{
				if ($row)
				{
					$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED');
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_DETAIL');
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_QUNTITY_DETAIL');
			}
		}

		if ($apply == 0)
		{
			$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $product_id, $msg);
		}

		$this->setRedirect('index.php?option=com_redshop&view=prices_detail&task=edit&product_id=' . $product_id . '&cid[]=' . $row->price_id, $msg);
	}

	public function remove()
	{
		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelPrices_detail $model */
		$model = $this->getModel('prices_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $product_id, $msg);
	}

	public function cancel()
	{
		$product_id = $this->input->get('product_id');

		$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $product_id, $msg);
	}
}
