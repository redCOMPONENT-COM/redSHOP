<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * The Product Price controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.State
 * @since       2.0.6
 */
class RedshopControllerProduct_Price extends RedshopController
{
	/**
	 * Cancel process.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * Listing method.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
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
	 * @since   1.6
	 */
	public function saveprice()
	{
		$db                   = JFactory::getDbo();
		$query                = $db->getQuery(true);
		$productId            = $this->input->get('pid');
		$shopperGroupId       = $this->input->post->get('shopper_group_id', array(), 'array');
		$prices               = $this->input->post->get('price', array(), 'array');
		$priceQuantitiesStart = $this->input->post->get('price_quantity_start', array(), 'array');
		$priceQuantitiesEnd   = $this->input->post->get('price_quantity_end', array(), 'array');
		$priceId              = $this->input->post->get('price_id', array(), 'array');

		$shopperGroupId       = ArrayHelper::toInteger($shopperGroupId);
		$priceQuantitiesStart = ArrayHelper::toInteger($priceQuantitiesStart);
		$priceQuantitiesEnd   = ArrayHelper::toInteger($priceQuantitiesEnd);
		$priceId              = ArrayHelper::toInteger($priceId);

		foreach ($prices as $i => $price)
		{
			// Check quantity start and end.
			if ($priceQuantitiesStart[$i] > $priceQuantitiesEnd[$i])
			{
				continue;
			}

			$query->clear()
				->select('COUNT(*)')
				->from($db->qn('#__redshop_product_price'))
				->where($db->qn('product_id') . ' = ' . $productId)
				->where($db->qn('price_id') . ' = ' . $priceId[$i])
				->where($db->qn('shopper_group_id') . ' = ' . $shopperGroupId[$i]);
			$count = (int) $db->setQuery($query)->loadResult();

			if ($count)
			{
				$query->clear()
					->select($db->qn('price_id'))
					->from($db->qn('#__redshop_product_price'))
					->where($db->qn('shopper_group_id') . ' = ' . $shopperGroupId[$i])
					->where($db->qn('product_id') . ' = ' . $productId)
					->where($db->qn('price_quantity_end') . ' >= ' . $priceQuantitiesStart[$i])
					->where($db->qn('price_quantity_start') . ' <= ' . $priceQuantitiesStart[$i]);
				$xid = $db->setQuery($query)->loadResult();

				if ($xid && $xid != $priceId[$i])
				{
					echo $xid;

					$this->setError(JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS')));
				}

				if (!empty($priceId[$i]))
				{
					$query->clear()
						->update($db->qn('#__redshop_product_price'))
						->set($db->qn('product_price') . ' = ' . $db->quote($price))
						->set($db->qn('price_quantity_start') . ' = ' . $db->quote($priceQuantitiesStart[$i]))
						->set($db->qn('price_quantity_end') . ' = ' . $db->quote($priceQuantitiesEnd[$i]))
						->where($db->qn('product_id') . ' = ' . $productId)
						->where($db->qn('price_id') . ' = ' . $priceId[$i])
						->where($db->qn('shopper_group_id') . ' = ' . $shopperGroupId[$i]);
				}
				else
				{
					$query->clear()
						->delete($db->qn('#__redshop_product_price'))
						->where($db->qn('product_id') . ' = ' . $productId)
						->where($db->qn('price_id') . ' = ' . $priceId[$i])
						->where($db->qn('shopper_group_id') . ' = ' . $shopperGroupId[$i]);
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
						$price . ',' . $priceQuantitiesStart[$i] . ',' . $priceQuantitiesEnd[$i] . ',' . $productId . ',' . $shopperGroupId[$i]
					);
			}

			$db->setQuery($query)->execute();
		}

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=product_price&pid=' . $productId);
	}

	/**
	 * Method for get template content
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function template()
	{
		$templateId = $this->input->get('template_id', '');
		$productId  = $this->input->get('product_id', '');
		$section    = $this->input->get('section', '');
		$model      = $this->getModel('product');

		echo $model->product_template($templateId, $productId, $section);

		JFactory::getApplication()->close();
	}
}
