<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Statistic Product
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Product extends RedshopModelList
{
	/**
	 * Constructor
	 *
	 * @deprecated  2.0.0.3
	 */
	public function __construct()
	{
		parent::__construct();
		$input                 = JFactory::getApplication()->input;
		$this->filterStartDate = $input->getString('filter_start_date', '');
		$this->filterEndDate   = $input->getString('filter_end_date', '');
		$this->filterDateLabel = $input->getString('filter_date_label', '');
	}

	/**
	 * get Product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getProducts()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDbo();
		$query = $db->getQuery(true)
			->select('DATE_FORMAT(p.publish_date,"' . $format . '") AS viewdate')
			->select('p.*')
			->select('COUNT(*) AS count')
			->select('m.manufacturer_name')
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
			->order($db->qn('p.publish_date') . ' DESC')
			->group($db->qn('product_id'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('p.publish_date') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('p.publish_date') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$products = $db->setQuery($query)->loadObjectList();

		$query = $db->getQuery(true)
			->select('SUM(product_final_price) AS total_sale')
			->select('COUNT(*) AS unit_sold')
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_status') . ' = ' . $db->q('S'))
			->group($db->qn('product_id'));

		$items = $db->setQuery($query)->loadObjectList();

		foreach ($products as $key => $product)
		{
			$products[$key]->unit_sold  = 0;
			$products[$key]->total_sale = 0;

			foreach ($items as $item)
			{
				if ($product->product_id == $item->product_id)
				{
					$products[$key]->unit_sold  = $item->unit_sold;
					$products[$key]->total_sale = $item->total_sale;
				}
			}
		}

		return $products;
	}

	/**
	 * get date Format for new statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormat()
	{
		$return = "";
		$startDate = strtotime($this->filterStartDate);
		$endDate = strtotime($this->filterEndDate);
		$interval = $endDate - $startDate;

		if ($interval == 0 && ($this->filterDateLabel == 'Today' || $this->filterDateLabel == 'Yesterday'))
		{
			$return = "%d %b %Y";
		}
		elseif ($interval <= 1209600)
		{
			$return = "%d %b. %Y";
		}
		elseif ($interval <= 7689600)
		{
			$return = "%b. %Y";
		}
		elseif ($interval <= 31536000)
		{
			$return = "%Y";
		}

		return $return;
	}
}
