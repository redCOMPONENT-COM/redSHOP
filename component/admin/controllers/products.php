<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerProducts extends RedshopControllerAdmin
{
	/**
	 *
	 */
	public function ins_product()
	{
		$this->input->set('layout', 'ins_product');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	/**
	 *
	 */
	public function importeconomic()
	{
		RedshopProducts::getInstance()->importEconomic($this->input->getInt('cnt', 0));
	}

	/**
	 *
	 */
	public function importatteco()
	{
		RedshopProducts::getInstance()->importAtteco($this->input->getInt('cnt', 0));
	}

	/**
	 *
	 */
	public function saveprice()
	{
		JSession::checkToken() or die();

		$productIds     = $this->input->post->get('pid', array(), 'array');
		$discountPrices = $this->input->post->get('price', array(), 'array');

		/** @var RedshopModelProduct $model */
		$model = $this->getModel('Product');
		$model->savePrices($productIds, $discountPrices);

		$this->setRedirect('index.php?option=com_redshop&view=product&layout=listing');
	}

	/**
	 * Save all discount price
	 *
	 * @return void
	 */
	public function savediscountprice()
	{
		JSession::checkToken() or die();

		$productIds     = $this->input->post->get('pid', array(), 'array');
		$discountPrices = $this->input->post->get('discount_price', array(), 'array');

		/** @var RedshopModelProduct $model */
		$model = $this->getModel('Product');
		$model->saveDiscountPrices($productIds, $discountPrices);

		$this->setRedirect('index.php?option=com_redshop&view=product&layout=listing');
	}

	/**
	 *
	 */
	public function template()
	{
		$template_id = $this->input->get('template_id', '');
		$product_id  = $this->input->get('product_id', '');
		$section     = $this->input->get('section', '');
		$model       = $this->getModel('product');

		$data_product = $model->product_template($template_id, $product_id, $section);

		if (is_array($data_product))
		{
			for ($i = 0, $in = count($data_product); $i < $in; $i++)
			{
				echo $data_product[$i];
			}
		}

		else
		{
			echo $data_product;
		}

		exit;
	}

	/**
	 *
	 */
	public function assignTemplate()
	{
		$post = $this->input->post->getArray();

		$model = $this->getModel('product');

		if ($model->assignTemplate($post))
		{
			$msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
		}

		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 *
	 */
	public function saveorder()
	{
		$cid   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('product');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Check in of one or more records.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   12.2
	 */
	public function checkin()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids = $this->input->post->get('cid', array(), 'array');

		$model  = $this->getModel('Product');
		$return = $model->checkin($ids);

		if ($return === false)
		{
			// Checkin failed.
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=product', false), $message, 'error');

			return false;
		}
		else
		{
			// Checkin succeeded.
			$message = JText::plural('COM_REDSHOP_PRODUCT_N_ITEMS_CHECKED_IN', count($ids));
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=product', false), $message);

			return true;
		}
	}
}
