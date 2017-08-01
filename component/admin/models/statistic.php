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
 * @subpackage  Models.Attributes
 * @since       2.0.0.2
 */
class RedshopModelStatistic extends RedshopModelList
{
	public $_filteroption = null;

	public $_typeoption = null;

	/**
	 * Constructor
	 *
	 * @deprecated  2.0.0.3
	 */
	public function __construct()
	{
		parent::__construct();
		$input                 = JFactory::getApplication()->input;
		$this->_filteroption   = $input->getInt('filteroption', 0);
		$this->_typeoption     = $input->getInt('typeoption', 2);

		if (!$this->_filteroption && $input->getString('view', '') == "")
		{
			$this->_filteroption = 1;
		}
	}

	/**
	 * get most popular product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getMostPopular()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('pv.created_date'))
			->from($db->qn('#__redshop_pageviewer', 'pv'))
			->where($db->qn('pv.section') . ' = ' . $db->q('product'))
			->order($db->qn('pv.created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(' . $db->qn('pv.created_date') . ', "' . $formate . '") AS viewdate')
			->select($db->qn('p.product_id'))
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_price'))
			->select('COUNT(*) AS visited')
			->from($db->qn('#__redshop_pageviewer', 'pv'))
			->leftjoin(
				$db->qn('#__redshop_product', 'p') . ' ON '
				. $db->qn('p.product_id') . ' = '
				. $db->qn('pv.section_id')
			)
			->where($db->qn('pv.section') . ' = ' . $db->q('product'))
			->where($db->qn('pv.section_id') . ' != 0')
			->group($db->qn('pv.section_id'))
			->order($db->qn('visited') . ' DESC');

		$mostPopular = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('pv.created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('pv.created_date') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$mostPopular = $result;
			}
		}

		return $mostPopular;
	}

	/**
	 * get product best seller data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getBestSellers()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_order_item'))
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select('COUNT(oi.product_id) AS totalproduct');

		if ($this->_typeoption == 2)
		{
			$query = $db->getQuery(true)
				->select('SUM(oi.product_quantity) AS totalproduct');
		}

		$query->select('FROM_UNIXTIME(' . $db->qn('oi.cdate') . ', "' . $formate . '") AS viewdate')
			->select($db->qn('p.product_id'))
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_price'))
			->from($db->qn('#__redshop_order_item', 'oi'))
			->leftjoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('oi.product_id'))
			->group($db->qn('oi.product_id'))
			->order($db->qn('totalproduct') . ' DESC');

		$bestSallers = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('oi.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('oi.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$bestSallers = $result;
			}
		}

		return $bestSallers;
	}

	/**
	 * get new product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNewProducts()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('publish_date'))
			->from($db->qn('#__redshop_product'))
			->order($db->qn('publish_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select($db->qn('product_id'))
			->select($db->qn('product_name'))
			->select($db->qn('product_price'))
			->select('FROM_UNIXTIME(' . $db->qn('publish_date') . ', "' . $formate . '") AS viewdate')
			->from($db->qn('#__redshop_product'))
			->order($db->qn('publish_date') . ' DESC');

		$newProducts = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while (strtotime($minDate) < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('publish_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('publish_date') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$newProducts = $result;
			}
		}

		return $newProducts;
	}

	/**
	 * get new orders data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNewOrders()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_orders'))
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select($db->qn('o.order_id'))
			->select($db->qn('o.order_total'))
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ', "' . $formate . '") AS viewdate')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
			->order($db->qn('o.cdate') . ' DESC');

		$newOrders = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$newOrders = $result;
			}
		}

		return $newOrders;
	}

	/**
	 * Get total turnover for cpanel view
	 * This is an optimized version of original getTotalTurnover() function
	 *
	 * @return  array  Turn over of shop to show statistics chart.
	 */
	public function getTotalTurnOverCpanel()
	{
		$formate = $this->getDateFormate();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' = ' . $db->q('C'), 'OR')
			->where($db->qn('order_status') . ' = ' . $db->q('PR'), 'OR')
			->where($db->qn('order_status') . ' = ' . $db->q('S'), 'OR')
			->order($db->qn('cdate') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query, 0, 1);
		$minDate = $db->loadResult();

		if (!$minDate)
		{
			return array();
		}

		$query = $db->getQuery(true)
					->clear()
					->from($db->qn('#__redshop_orders', 'o'))
					->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
					->order($db->qn('o.cdate') . ' DESC')
					->group('viewdate');

		if (!empty($minDate))
		{
			$query->where($db->qn('cdate') . ' >= ' . $minDate);
		}

		if ($this->_filteroption == 2)
		{
			$query->select('CONCAT("' . JText::_('COM_REDSHOP_WEEKS') . ' - ", WEEKOFYEAR(FROM_UNIXTIME(o.cdate,"%Y-%m-%d"))) AS viewdate');

			$query->group('FROM_UNIXTIME(o.cdate,"%Y")');
		}
		elseif ($this->_filteroption == 4)
		{
			$query->select('CONCAT("' . JText::_('COM_REDSHOP_YEAR') . ' - ", FROM_UNIXTIME(o.cdate,"' . $formate . '")) AS viewdate');
		}
		else
		{
			$query->select('FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate');
		}

		$query->select('SUM(o.order_total) AS turnover');

		if ($this->_filteroption != 4)
		{
			$db->setQuery($query, 0, 10);
		}
		else
		{
			$db->setQuery($query);
		}

		return $db->loadRowList();
	}

	/**
	 * Get total sales for cpanel view
	 *
	 * @return  array  Sales of shop to show statistics chart.
	 */
	public function getTotalSalesCpanel()
	{
		$db    = JFactory::getDbo();
		$defaultQuery = $db->getQuery(true)
			->select('SUM(' . $db->qn('o.order_total') . ') AS total')
			->select('COUNT(' . $db->qn('o.order_total') . ') AS orders')
			->from($db->qn('#__redshop_orders', 'o'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')');

		// 30 days
		$query = clone $defaultQuery;
		$query->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '30')));
		$query->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 30 DAY AND NOW()');

		// Today
		$union = clone $defaultQuery;
		$union->select($db->q(JText::_('COM_REDSHOP_STATISTIC_TODAY')));
		$union->where('DATE(FROM_UNIXTIME(' . $db->qn('cdate') . ')) = CURDATE()');
		$query->union($union);

		// Yesterday
		$union = clone $defaultQuery;
		$union->select($db->q(JText::_('COM_REDSHOP_STATISTIC_YESTERDAY')));
		$union->where('DATE(FROM_UNIXTIME(' . $db->qn('cdate') . ')) = SUBDATE(CURDATE(),1)');
		$query->union($union);

		// 7 days
		$union = clone $defaultQuery;
		$union->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '7')));
		$union->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 7 DAY AND NOW()');
		$query->union($union);

		// 90 days
		$union = clone $defaultQuery;
		$union->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '90')));
		$union->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 90 DAY AND NOW()');
		$query->union($union);

		$db->setQuery($query);

		return $db->loadRowList();
	}

	/**
	 * get total turnover
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getTotalTurnover()
	{
		$turnOver = array();
		$today    = $this->getStartDate();
		$formate  = $this->getDateFormate();
		$result   = array();
		$db       = $this->getDbo();
		$query    = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate')
			->select('SUM(o.order_total) AS turnover')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('o.cdate'));

		$turnOver = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);

				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					  ->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query2)->loadObjectList();

				if (count($rs) > 0 && $rs[0]->turnover > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$turnOver = $result;
			}
		}

		return $turnOver;
	}

	/**
	 * get avarage order amount
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAvgOrderAmount()
	{
		$amountPrice = "";
		$today       = $this->getStartDate();
		$formate     = $this->getDateFormate();
		$result      = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select('(SUM(o.order_total)/COUNT(DISTINCT o.user_id)) AS avg_order')
			->from($db->qn('#__redshop_orders', 'o'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('viewdate') . ' DESC');

		if ($this->_filteroption && $minDate != '' && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				if (count($rs) > 0 && $rs[0]->avg_order > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		if (empty($result))
		{
			$amountPrice = $db->setQuery($query)->loadObjectList();
		}

		return $amountPrice;
	}

	/**
	 * get amount price
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountPrice()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('MAX(o.order_total) AS order_total')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('order_total') . 'DESC');
		$amountPrice = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		return $amountPrice;
	}

	/**
	 * get amount spent in total
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountSpentInTotal()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('SUM(o.order_total) AS order_total')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('order_total') . 'DESC');
		$amountPrice = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		return $amountPrice;
	}

	/**
	 * get amount order
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountOrder()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('COUNT(o.user_id) AS totalorder')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('totalorder') . 'DESC');
		$amountPrice = $this->_getList($query);

		$amountOrder = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountOrder = $result;
			}
		}

		return $amountOrder;
	}

	/**
	 * get page viewer
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getPageViewer()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('created_date')
			->from($db->qn('#__redshop_pageviewer'))
			->order($db->qn('created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('created_date') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('section'))
			->select($db->qn('section_id'))
			->select('COUNT(*) AS totalpage')
			->from($db->qn('#__redshop_pageviewer'))
			->where($db->qn('section_id') . ' != 0')
			->group($db->qn('section'))
			->group($db->qn('section_id'))
			->order($db->qn('totalpage') . ' DESC');

		$pageViewer = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('created_date') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$pageViewer = $result;
			}
		}

		return $pageViewer;
	}

	/**
	 * get redSHOP viewer
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getRedshopViewer()
	{
		$siteViewer = array();
		$today      = $this->getStartDate();
		$formate    = $this->getDateFormate();
		$result     = array();
		$db         = JFactory::getDbo();
		$query      = $db->getQuery(true)
			->select('created_date')
			->from($db->qn('#__redshop_siteviewer'))
			->order($db->qn('created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('COUNT(*) AS viewer')
			->from($db->qn('#__redshop_siteviewer'));

		$siteViewer = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			$query = $db->getQuery(true)
				->clear()
				->select('FROM_UNIXTIME(' . $db->qn('created_date') . ',' . $db->q($formate) . ') AS viewdate')
				->from($db->qn('#__redshop_siteviewer'));

			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query2 = clone($query);
				$query2->where($db->qn('created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('created_date') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query2)->loadObjectList();

				$rs[0]->viewer = count($rs);

				if ($rs[0]->viewer > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = JText::_('COM_REDSHOP_WEEK') . " " . date("W - Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$siteViewer = $result;
			}
		}

		return $siteViewer;
	}

	/**
	 * get next interval
	 *
	 * @param   string  $today  today text
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNextInterval($today)
	{
		$list = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($this->_filteroption)
		{
			case 1:
				$query->select('SUBDATE(' . $db->q($today) . ', INTERVAL 1 DAY) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
			case 2:
				$query->select('SUBDATE(' . $db->q($today) . ', INTERVAL 1 WEEK) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
			case 3:
				$query->select('LAST_DAY(SUBDATE(' . $db->q($today) . ', INTERVAL 1 MONTH)) AS preday');
				$list = $db->setQuery($query)->loadObject();
				$list->preday = $list->preday . " 23:59:59";
				break;
			case 4:
				$query->select('SUBDATE(' . $db->q($today) . ', INTERVAL 1 YEAR) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
		}

		return $list;
	}

	/**
	 * get start date
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getStartDate()
	{
		$return = "";
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($this->_filteroption)
		{
			case 1:
				$query->select('CURDATE() AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 2:
				$query->select('ADDDATE(CURDATE(), INTERVAL 6-weekday(CURDATE()) DAY) AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 3:
				$query->select('LAST_DAY(CURDATE()) AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 4:
				$query->select('LAST_DAY(' . $db->q(date("Y-12-d")) . ') AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
		}

		return $return;
	}

	/**
	 * get date Format
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormate()
	{
		$return = "";

		switch ($this->_filteroption)
		{
			case 1:
				$return = "%d %b %Y";
				break;
			case 2:
				$return = "%d %b, %Y";
				break;
			case 3:
				$return = "%b, %Y";
				break;
			case 4:
				$return = "%Y";
				break;
			default:
				$return = "%Y";
				break;
		}

		return $return;
	}

	/**
	 * get section detail
	 *
	 * @param   string  $section    section
	 * @param   int     $sectionId  section id
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getSectionDetail($section, $sectionId)
	{
		$return = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($section)
		{
			case "product":
				$query
					->select($db->qn('product_name', 'sname'))
					->select($db->qn('product_id', 'id'))
					->from($db->qn('#__redshop_product'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
			case "category":
				$query
					->select($db->qn('name', 'sname'))
					->select($db->qn('id'))
					->from($db->qn('#__redshop_category'))
					->where($db->qn('id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
			case "manufacturers":
				$query
					->select($db->qn('manufacturer_name', 'sname'))
					->select($db->qn('manufacturer_id', 'id'))
					->from($db->qn('#__redshop_manufacturer'))
					->where($db->qn('manufacturer_id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
		}

		return $return;
	}
}
