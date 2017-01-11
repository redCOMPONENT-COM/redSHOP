<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;


class RedshopControllerProduct_price extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function listing()
	{
		$this->input->set('layout', 'listing');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * Method for save prices of products.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function saveprice()
	{
		$db                   = JFactory::getDbo();
		$query                = $db->getQuery(true);
		$product_id           = $this->input->get('pid');
		$shopper_group_id     = $this->input->post->get('shopper_group_id', array(), 'array');
		$prices               = $this->input->post->get('price', array(), 'array');
		$price_quantity_start = $this->input->post->get('price_quantity_start', array(), 'array');
		$price_quantity_end   = $this->input->post->get('price_quantity_end', array(), 'array');
		$price_id             = $this->input->post->get('price_id', array(), 'array');

		$shopper_group_id     = ArrayHelper::toInteger($shopper_group_id);
		$price_quantity_start = ArrayHelper::toInteger($price_quantity_start);
		$price_quantity_end   = ArrayHelper::toInteger($price_quantity_end);
		$price_id             = ArrayHelper::toInteger($price_id);

		foreach ($prices as $i => $price)
		{
			$query->clear()
				->select('COUNT(*)')
				->from($db->qn('#__redshop_product_price'))
				->where($db->qn('product_id') . ' = ' . $product_id)
				->where($db->qn('price_id') . ' = ' . $price_id[$i])
				->where($db->qn('shopper_group_id') . ' = ' . $shopper_group_id[$i]);
			$count = (int) $db->setQuery($query)->loadResult();

			if ($count)
			{
				$query->clear()
					->select($db->qn('price_id'))
					->from($db->qn('#__redshop_product_price'))
					->where($db->qn('shopper_group_id') . ' = ' . $shopper_group_id[$i])
					->where($db->qn('product_id') . ' = ' . $product_id)
					->where($db->qn('price_quantity_end') . ' >= ' . $price_quantity_start[$i])
					->where($db->qn('price_quantity_start') . ' <= ' . $price_quantity_start[$i]);
				$xid = $db->setQuery($query)->loadResult();

				if ($xid && $xid != $price_id[$i])
				{
					echo $xid;

					$this->setError(JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS')));
				}

				if (!empty($price_id[$i]))
				{
					$query->clear()
						->update($db->qn('#__redshop_product_price'))
						->set($db->qn('product_price') . ' = ' . $db->quote($price))
						->set($db->qn('price_quantity_start') . ' = ' . $db->quote($price_quantity_start[$i]))
						->set($db->qn('price_quantity_end') . ' = ' . $db->quote($price_quantity_end[$i]))
						->where($db->qn('product_id') . ' = ' . $product_id)
						->where($db->qn('price_id') . ' = ' . $price_id[$i])
						->where($db->qn('shopper_group_id') . ' = ' . $shopper_group_id[$i]);
				}
				else
				{
					$query->clear()
						->delete($db->qn('#__redshop_product_price'))
						->where($db->qn('product_id') . ' = ' . $product_id)
						->where($db->qn('price_id') . ' = ' . $price_id[$i])
						->where($db->qn('shopper_group_id') . ' = ' . $shopper_group_id[$i]);
				}
			}
			elseif (!empty($price))
			{
				$query->clear()
					->insert($db->qn('#__redshop_product_price'))
					->columns(
						$db->qn(array('product_price', 'price_quantity_start', 'price_quantity_end', 'product_id', 'shopper_group_id'))
					)
					->values(
						$price . ',' . $price_quantity_start[$i] . ',' . $price_quantity_end[$i] . ',' . $product_id . ',' . $shopper_group_id[$i]
					);
			}

			$db->setQuery($query)->execute();
		}

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=product_price&pid=' . $product_id);
	}

	public function template()
	{
		$template_id = $this->input->get('template_id', '');
		$product_id  = $this->input->get('product_id', '');
		$section     = $this->input->get('section', '');
		$model       = $this->getModel('product');

		$data_product = $model->product_template($template_id, $product_id, $section);
		echo $data_product;
		exit;
	}
}
