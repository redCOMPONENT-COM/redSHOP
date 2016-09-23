<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStatistic extends RedshopModel
{
	public $_table_prefix = null;

	public $_startdate = null;

	public $_enddate = null;

	public $_filteroption = null;

	public $_typeoption = null;

	public $_mostpopular = null;

	public $_bestsallers = null;

	public $_newproducts = null;

	public $_neworders = null;

	public $_amountprice = null;

	public $_amountorder = null;

	public $_turnover = 0;

	public $_siteviewer = 0;

	public $_pageviewer = 0;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$jinput = JFactory::getApplication()->input;

		$this->_startdate = strtotime($jinput->getInt('startdate', 0));
		$this->_enddate = strtotime($jinput->getInt('enddate', 0));
		$this->_filteroption = $jinput->getInt('filteroption', 0);
		$this->_typeoption = $jinput->getInt('typeoption', 2);

		if (!$this->_filteroption && $jinput->getString('view', '') == "")
		{
			$this->_filteroption = 1;
		}
	}

	public function getMostPopular()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT pv.created_date '
			. 'FROM ' . $this->_table_prefix . 'pageviewer AS pv '
			. 'WHERE pv.section="product" '
			. 'ORDER BY pv.created_date ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT FROM_UNIXTIME(pv.created_date,"' . $formate . '") AS viewdate '
			. ', p.product_id, p.product_name, p.product_price, count(*) AS visited '
			. 'FROM ' . $this->_table_prefix . 'pageviewer AS pv '
			. 'LEFT JOIN ' . $this->_table_prefix . 'product p ON p.product_id=pv.section_id '
			. 'WHERE pv.section="product" AND pv.section_id!=0 ';
		$query1 = ' GROUP BY pv.section_id '
			. 'ORDER BY visited desc ';
		$this->_mostpopular = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND created_date > " . strtotime($list->preday)
					. " AND created_date <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_mostpopular = $result;
			}
		}

		return $this->_mostpopular;
	}

	public function getBestSellers()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'order_item '

			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$type = 'count(oi.product_id)';

		if ($this->_typeoption == 2)
		{
			$type = 'sum(oi.product_quantity)';
		}

		$query = 'SELECT '. $type .' AS totalproduct, FROM_UNIXTIME(oi.cdate,"' . $formate . '") AS viewdate '
			. ', p.product_id, p.product_name, p.product_price '
			. 'FROM ' . $this->_table_prefix . 'order_item as oi '
			. 'LEFT JOIN ' . $this->_table_prefix . 'product p ON p.product_id=oi.product_id ';
		$query1 = ' GROUP BY oi.product_id '
			. 'ORDER BY totalproduct desc ';
		$this->_bestsallers = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " WHERE oi.cdate > " . strtotime($list->preday)
					. " AND oi.cdate <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_bestsallers = $result;
			}
		}

		return $this->_bestsallers;
	}

	public function getNewProducts()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT publish_date '
			. 'FROM ' . $this->_table_prefix . 'product '
			. 'ORDER BY publish_date ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT product_id,product_name,product_price '
			. ', DATE_FORMAT(publish_date,"' . $formate . '") AS viewdate '
			. 'FROM ' . $this->_table_prefix . 'product ';
		$query1 = ' ORDER BY publish_date desc ';
		$this->_newproducts = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while (strtotime($mindate) < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " WHERE publish_date > '" . $list->preday . "' "
					. " AND publish_date <= '" . $today . "' "
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_newproducts = $result;
			}
		}

		return $this->_newproducts;
	}

	public function getNewOrders()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'orders '
			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT uf.firstname, uf.lastname,o.order_id, o.order_total '
			. ', FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id=uf.user_id '
			. 'AND address_type LIKE "BT" ';
		$query1 = ' ORDER BY cdate desc ';
		$this->_neworders = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " WHERE o.cdate > " . strtotime($list->preday)
					. " AND o.cdate <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_neworders = $result;
			}
		}

		return $this->_neworders;
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
					->from($db->qn('#__redshop_orders', 'o'))
					->where(
						$db->qn('o.order_status') . ' = ' . $db->q('C')
						. ' OR '
						. $db->qn('o.order_status') . ' = ' . $db->q('PR')
						. ' OR '
						. $db->qn('o.order_status') . ' = ' . $db->q('S')
					)
					->order($db->qn('o.cdate') . ' DESC')
					->group('viewdate');

		if (!empty($mindate))
		{
			$query->where($db->qn('cdate') . ' >= ' . $minDate);
		}

		$query->leftjoin(
					$db->qn('#__redshop_users_info', 'uf')
					. ' ON '
					. $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id')
					. ' AND ' . $db->qn('uf.address_type') . ' = ' . $db->q('BT')
				);

		if ($this->_filteroption == 2)
		{
			$query->select('CONCAT("' . JText::_('COM_REDSHOP_WEEKS') . ' - ", WEEKOFYEAR(FROM_UNIXTIME(o.cdate,"%Y-%m-%d"))) AS viewdate');

			$query->group('FROM_UNIXTIME(o.cdate,"%Y")');
		}
		else if ($this->_filteroption == 4)
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
			->where(
				'(' . $db->qn('o.order_status') . ' = ' . $db->q('C')
				. ' OR '
				. $db->qn('o.order_status') . ' = ' . $db->q('PR')
				. ' OR '
				. $db->qn('o.order_status') . ' = ' . $db->q('S') . ')'
			)->leftjoin(
				$db->qn('#__redshop_users_info', 'uf')
				. ' ON '
				. $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id')
				. ' AND ' . $db->qn('uf.address_type') . ' = ' . $db->q('BT')
			);

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

	public function getTotalTurnover()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();

		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'orders '
			. 'WHERE order_status = "C" OR order_status = "PR" OR order_status = "S" '
			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate, SUM(o.order_total) AS turnover '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id=uf.user_id '
			. 'WHERE uf.address_type="BT" and (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$quesry1 = ' GROUP BY 1  ORDER BY o.cdate ';
		$this->_turnover = $this->_getList($query . $quesry1);

		if ($this->_filteroption == 3)
		{
			return $this->_turnover;
		}

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND cdate > " . strtotime($list->preday)
					. " AND cdate <= " . strtotime($today)
					. $quesry1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				if (count($rs) > 0 && $rs[0]->turnover > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_turnover = array_reverse($result);
			}
		}

		return $this->_turnover;
	}

	public function getAvgOrderAmount()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where('order_status IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order('cdate ASC');
		$mindate = $db->setQuery($query)->loadResult();

		$query->clear()
			->select('FROM_UNIXTIME(o.cdate,' . $db->q($formate) . ') AS viewdate')
			->select('(SUM(o.order_total)/COUNT(DISTINCT o.user_id)) AS avg_order')
			->from($db->qn('#__redshop_orders', 'o'))
			->where('o.order_status IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order('viewdate DESC')
			->group('1');

		if ($this->_filteroption && $mindate != '' && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$newQuery = clone $query;
				$newQuery->where('o.cdate > ' . strtotime($list->preday))
					->where('o.cdate <= ' . strtotime($today));
				$rs = $db->setQuery($newQuery)->loadObjectList();

				if (count($rs) > 0 && $rs[0]->avg_order > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_amountprice = $result;
			}
		}

		if (empty($result))
		{
			$this->_amountprice = $db->setQuery($query)->loadObjectList();
		}

		return $this->_amountprice;
	}

	public function getAmountPrice()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'orders '
			. 'WHERE order_status = "C" OR order_status = "PR" OR order_status = "S" '
			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT firstname,lastname, FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate, MAX(o.order_total) AS order_total '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id=uf.user_id '
			. 'AND address_type LIKE "BT" '
			. 'WHERE (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$query1 = ' GROUP by o.user_id '
			. 'ORDER BY order_total desc ';
		$this->_amountprice = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND cdate > " . strtotime($list->preday)
					. " AND cdate <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_amountprice = $result;
			}
		}

		return $this->_amountprice;
	}

	public function getAmountSpentInTotal()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'orders '
			. 'WHERE order_status = "C" OR order_status = "PR" OR order_status = "S" '
			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT firstname,lastname, FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate, SUM(o.order_total) AS order_total '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id=uf.user_id '
			. 'AND address_type LIKE "BT" '
			. 'WHERE (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$query1 = ' GROUP by o.user_id '
			. 'ORDER BY order_total desc ';
		$this->_amountprice = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND cdate > " . strtotime($list->preday)
					. " AND cdate <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_amountprice = $result;
			}
		}

		return $this->_amountprice;
	}

	public function getAmountOrder()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT cdate '
			. 'FROM ' . $this->_table_prefix . 'orders '
			. 'WHERE order_status = "C" OR order_status = "PR" OR order_status = "S" '
			. 'ORDER BY cdate ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT firstname,lastname, FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate '
			. ', COUNT(o.user_id) AS totalorder '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id=uf.user_id '
			. 'AND address_type LIKE "BT" '
			. 'WHERE (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$query1 = ' GROUP BY o.user_id '
			. 'ORDER BY totalorder desc ';
		$this->_amountorder = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND cdate > " . strtotime($list->preday)
					. " AND cdate <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_amountorder = $result;
			}
		}

		return $this->_amountorder;
	}

	public function getPageViewer()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT created_date '
			. 'FROM ' . $this->_table_prefix . 'pageviewer '
			. 'ORDER BY created_date ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT section, section_id, count(*) as totalpage '
			. ', FROM_UNIXTIME(created_date,"' . $formate . '") AS viewdate '
			. 'FROM ' . $this->_table_prefix . 'pageviewer '
			. 'WHERE section_id != 0 ';
		$query1 = ' GROUP BY `section`,`section_id` '
			. 'ORDER BY totalpage DESC ';
		$this->_pageviewer = $this->_getList($query . $query1);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " AND created_date > " . strtotime($list->preday)
					. " AND created_date <= " . strtotime($today)
					. $query1;
				$this->_db->setQuery($q);
				$rs = $this->_db->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->_pageviewer = $result;
			}
		}

		return $this->_pageviewer;
	}

	public function getRedshopViewer()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$query = 'SELECT created_date '
			. 'FROM ' . $this->_table_prefix . 'siteviewer '
			. 'ORDER BY created_date ASC ';
		$this->_db->setQuery($query);
		$mindate = $this->_db->loadResult();

		$query = 'SELECT COUNT(*) AS viewer '
			. 'FROM ' . $this->_table_prefix . 'siteviewer ';
		$this->siteviewer = $this->_getList($query);

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			$query = 'SELECT FROM_UNIXTIME(created_date,"' . $formate . '") AS viewdate '
				. 'FROM ' . $this->_table_prefix . 'siteviewer ';

			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$q = $query . " WHERE created_date > " . strtotime($list->preday)
					. " AND created_date <= " . strtotime($today);
				$this->_db->setQuery($q);

				$rs = $this->_db->loadObjectList();
				$rs[0] = new stdClass;
				$rs[0]->viewer = count($rs);

				if ($rs[0]->viewer > 0)
				{
					if ($this->_filteroption == 1)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 3)
					{
						$rs[0]->viewdate = date("F, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 4)
					{
						$rs[0]->viewdate = date("Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$this->siteviewer = $result;
			}
		}

		return $this->siteviewer;
	}

	public function getNextInterval($today)
	{
		$list = array();

		switch ($this->_filteroption)
		{
			case 1:
				$query = 'SELECT SUBDATE("' . $today . '", INTERVAL 1 DAY) AS preday';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				break;
			case 2:
				$query = 'SELECT SUBDATE("' . $today . '", INTERVAL 1 WEEK) AS preday';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				break;
			case 3:
				$query = 'SELECT LAST_DAY(SUBDATE("' . $today . '", INTERVAL 1 MONTH)) AS preday';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				$list->preday = $list->preday . " 23:59:59";
				break;
			case 4:
				$query = 'SELECT SUBDATE("' . $today . '", INTERVAL 1 YEAR) AS preday';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				break;
		}

		return $list;
	}

	public function getStartDate()
	{
		$return = "";

		switch ($this->_filteroption)
		{
			case 1:
				$query = 'SELECT CURDATE() AS date';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 2:
				$query = 'SELECT ADDDATE(CURDATE(), INTERVAL 6-weekday(CURDATE()) DAY) AS date';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 3:
				$query = 'SELECT LAST_DAY(CURDATE()) as date';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 4:
				$query = 'SELECT LAST_DAY("' . date("Y-12-d") . '") as date';
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
				$return = $list->date . " 23:59:59";
				break;
		}

		return $return;
	}

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

	public function getSectionDetail($section, $sectionid)
	{
		$return = array();

		switch ($section)
		{
			case "product":
				$query = 'SELECT product_name AS sname, product_id AS id FROM ' . $this->_table_prefix . 'product '
					. 'WHERE product_id = ' . $sectionid;
				$this->_db->setQuery($query);
				$return = $this->_db->loadObject();
				break;
			case "category":
				$query = 'SELECT category_name AS sname, category_id AS id FROM ' . $this->_table_prefix . 'category '
					. 'WHERE category_id = ' . $sectionid;
				$this->_db->setQuery($query);
				$return = $this->_db->loadObject();
				break;
			case "manufacturers":
				$query = 'SELECT manufacturer_name AS sname, manufacturer_id AS id FROM ' . $this->_table_prefix . 'manufacturer '
					. 'WHERE manufacturer_id = ' . $sectionid;
				$this->_db->setQuery($query);
				$return = $this->_db->loadObject();
				break;
		}

		return $return;
	}
}
