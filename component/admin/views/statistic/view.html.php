<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

class RedshopViewStatistic extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$layout = $app->input->getCmd('layout', '');

		$startdate = $app->input->getInt('startdate', 0);
		$enddate = $app->input->getInt('enddate', 0);

		$filteroption = $app->input->getInt('filteroption', 0);
		$typeoption = $app->input->getInt('typeoption', 2);

		$lists = array();
		$option = array();

		$option[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));
		$option[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_DAILY'));
		$option[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_WEEKLY'));
		$option[] = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_MONTHLY'));
		$option[] = JHTML::_('select.option', '4', JText::_('COM_REDSHOP_YEARLY'));

		$type[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_NUMBER_OF_TIMES_SOLD'));
		$type[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_NUMBER_OF_ITEMS_SOLD'));

		$lists['filteroption'] = JHTML::_('select.genericlist', $option, 'filteroption',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $filteroption
		);

		$lists['typeoption'] = JHTML::_('select.genericlist', $type, 'typeoption',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $typeoption
		);

		$redshopviewer = array();
		$pageviewer = array();
		$avgorderamount = array();
		$popularsell = array();
		$bestsell = array();
		$newprod = array();
		$neworder = array();
		$totalturnover = array();
		$amountorder = array();
		$amountprice = array();
		$amountspentintotal = array();

		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		if ($layout == 'turnover')
		{
			$this->setLayout('turnover');
			$title = JText::_('COM_REDSHOP_TOTAL_TURNOVER');
			$totalturnover = $this->get('TotalTurnover');
			$total = count($totalturnover);
		}
		elseif ($layout == 'pageview')
		{
			$this->setLayout('pageview');
			$title = JText::_('COM_REDSHOP_TOTAL_PAGEVIEWERS');
			$pageviewer = $this->get('PageViewer');
			$total = count($pageviewer);
		}
		elseif ($layout == 'amountorder')
		{
			$this->setLayout('amountorder');
			$title = JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER');
			$amountorder = $this->get('AmountOrder');
			$total = count($amountorder);
		}
		elseif ($layout == 'avrgorder')
		{
			$this->setLayout('avrgorder');
			$title = JText::_('COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER');
			$avgorderamount = $this->get('AvgOrderAmount');
			$total = count($avgorderamount);
		}
		elseif ($layout == 'amountprice')
		{
			$this->setLayout('amountprice');
			$title = JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER');
			$amountprice = $this->get('AmountPrice');
			$total = count($amountprice);
		}
		elseif ($layout == 'amountspent')
		{
			$this->setLayout('amountspent');
			$title = JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL');
			$amountspentintotal = $this->get('AmountSpentInTotal');
			$total = count($amountspentintotal);
		}
		elseif ($layout == 'bestsell')
		{
			$this->setLayout('bestsell');
			$title = JText::_('COM_REDSHOP_BEST_SELLERS');
			$bestsell = $this->get('BestSellers');
			$total = count($bestsell);
		}
		elseif ($layout == 'popularsell')
		{
			$this->setLayout('popularsell');
			$title = JText::_('COM_REDSHOP_MOST_VISITED_PRODUCTS');
			$popularsell = $this->get('MostPopular');
			$total = count($popularsell);
		}
		elseif ($layout == 'newprod')
		{
			$this->setLayout('newprod');
			$title = JText::_('COM_REDSHOP_NEWEST_PRODUCTS');
			$newprod = $this->get('NewProducts');
			$total = count($newprod);
		}
		elseif ($layout == 'neworder')
		{
			$this->setLayout('neworder');
			$title = JText::_('COM_REDSHOP_NEWEST_ORDERS');
			$neworder = $this->get('NewOrders');
			$total = count($neworder);
		}
		else
		{
			$this->setLayout('default');
			$title = JText::_('COM_REDSHOP_TOTAL_VISITORS');
			$redshopviewer = $this->get('RedshopViewer');
			$total = count($redshopviewer);
		}

		$document->setTitle(JText::_('COM_REDSHOP_STATISTIC'));

		$pagination       = new JPagination($total, $limitstart, $limit);
		$this->pagination = $pagination;

		$this->startdate = $startdate;
		$this->enddate   = $enddate;

		$this->popularsell        = $popularsell;
		$this->bestsell           = $bestsell;
		$this->avgorderamount     = $avgorderamount;
		$this->newprod            = $newprod;
		$this->neworder           = $neworder;
		$this->totalturnover      = $totalturnover;
		$this->amountorder        = $amountorder;
		$this->amountprice        = $amountprice;
		$this->amountspentintotal = $amountspentintotal;
		$this->redshopviewer      = $redshopviewer;
		$this->pageviewer         = $pageviewer;
		$this->lists              = $lists;
		$this->filteroption       = $filteroption;
		$this->typeoption         = $typeoption;
		$this->layout             = $layout;
		$this->request_url        = $uri->toString();
		$this->title              = $title;

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title($this->title, 'statistic redshop_statistic48');
	}
}
