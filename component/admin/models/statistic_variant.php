<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Statistic Product Variants
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Variant extends RedshopModelList
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
	 * get Product variants data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getProductVariants()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDbo();
		$query  = $db->getQuery(true)
			->select('FROM_UNIXTIME(oi.cdate,"' . $format . '") AS viewdate')
			->select('oai.*')
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_id'))
			->select($db->qn('pap.property_number'))
			->from($db->qn('#__redshop_order_attribute_item', 'oai'))
			->leftjoin($db->qn('#__redshop_order_item', 'oi') . ' ON ' . $db->qn('oai.order_item_id') . ' = ' . $db->qn('oi.order_item_id'))
			->leftjoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON ' . $db->qn('oai.section_id') . ' = ' . $db->qn('property_id'))
			->leftjoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('oai.section') . ' = ' . $db->q('property'))
			->order($db->qn('oi.order_item_id') . ' DESC,' . $db->qn('oai.parent_section_id') . ' ASC');

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('p.publish_date') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('p.publish_date') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$variants = $db->setQuery($query)->loadObjectList();
		$data     = array();
		$result   = array();

		foreach ($variants as $key => $variant)
		{
			$data[$variant->order_item_id]['attribute'][]     = $variant->section_name;
			$data[$variant->order_item_id]['attribute_sku'][] = $variant->property_number;
			$data[$variant->order_item_id]['viewdate']        = $variant->viewdate;
			$data[$variant->order_item_id]['product_name']    = $variant->product_name;
			$data[$variant->order_item_id]['product_id']      = $variant->product_id;
		}

		foreach ($data as $key => $value)
		{
			$result[$key]['product_attribute']     = implode(' - ', $value['attribute']);
			$result[$key]['product_attribute_sku'] = implode(' - ', $value['attribute_sku']);
			$result[$key]['viewdate']              = $value['viewdate'];
			$result[$key]['product_name']          = $value['product_name'];
			$result[$key]['product_id']            = $value['product_id'];
		}

		$query = $db->getQuery(true)
			->select('SUM(product_final_price) AS total_sale')
			->select('COUNT(*) AS unit_sold')
			->select($db->qn('order_item_id'))
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_status') . ' = ' . $db->q('S'))
			->group($db->qn('order_item_id'));

		$items = $db->setQuery($query)->loadObjectList();

		foreach ($result as $itemId => $value)
		{
			$result[$itemId]['unit_sold']  = 0;
			$result[$itemId]['total_sale'] = 0;

			foreach ($items as $item)
			{
				if ($itemId == $item->order_item_id)
				{
					$result[$itemId]['unit_sold']  = $item->unit_sold;
					$result[$itemId]['total_sale'] = $item->total_sale;
				}
			}
		}

		return $result;
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
