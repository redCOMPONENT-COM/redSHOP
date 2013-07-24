<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class userViewuser extends JView
{
	public function display($tpl = null)
	{
		$context = 'user_info_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_USER'));

		$userhelper = new rsUserhelper;

		$sync                      = JRequest::getVar('sync');
		$filter_by                 = JRequest::getVar('filter_by', '', 'request', 'string');
		$spgrp_filter              = JRequest::getVar('spgrp_filter', '', 'request', 'string');
		$approved_filter           = JRequest::getVar('approved_filter', '', 'request', 'string');
		$tax_exempt_request_filter = JRequest::getVar('tax_exempt_request_filter', '', 'request', 'string');

		$model = $this->getModel('user');

		if ($sync)
		{
			$this->setLayout('user_sync');
			$sync_user = $userhelper->userSynchronization();
			$this->sync_user = $sync_user;
		}
		else
		{
			$this->setLayout('default');
			JToolBarHelper::addNewX();
			JToolBarHelper::editListX();
			JToolBarHelper::deleteList();
		}

		$filter_order        = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'users_info_id');
		$filter_order_Dir    = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists ['order']     = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;

		$user                = $this->get('Data');
		$total               = $this->get('Total');
		$pagination          = $this->get('Pagination');

		$shopper_groups      = $userhelper->getShopperGroupList();

		$temps               = array();
		$temps[0]            = new stdClass;
		$temps[0]->value     = 0;
		$temps[0]->text      = JText::_('COM_REDSHOP_SELECT');
		$shopper_groups      = array_merge($temps, $shopper_groups);

		$lists['shopper_group'] = JHTML::_('select.genericlist', $shopper_groups, 'spgrp_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit()"', 'value', 'text', $spgrp_filter
		);

		$arr_filter_by      = array();
		$arr_filter_by[]    = JHTML::_('select.option', '', 'All');
		$arr_filter_by[]    = JHTML::_('select.option', 'fullname', JText::_('COM_REDSHOP_FULLNAME'));
		$arr_filter_by[]    = JHTML::_('select.option', 'username', JText::_('COM_REDSHOP_USERNAME'));
		$lists['filter_by'] = JHTML::_('select.genericlist', $arr_filter_by, 'filter_by',
			'class="inputbox" size="1"', 'value', 'text', $filter_by
		);

		$optiontax_req               = array();
		$optiontax_req[]             = JHTML::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$optiontax_req[]             = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_yes'));
		$optiontax_req[]             = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_no'));
		$lists['tax_exempt_request'] = JHTML::_('select.genericlist', $optiontax_req, 'tax_exempt_request_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit()"', 'value', 'text', $tax_exempt_request_filter
		);

		$this->lists       = $lists;
		$this->user        = $user;
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
