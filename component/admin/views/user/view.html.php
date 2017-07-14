<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopViewUser extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_USER'));

		$userhelper = rsUserHelper::getInstance();

		$this->state = $this->get('State');
		$sync                      = JRequest::getVar('sync');
		$spgrp_filter              = $this->state->get('spgrp_filter');
		$tax_exempt_request_filter = $this->state->get('tax_exempt_request_filter');

		if ($sync)
		{
			$this->setLayout('user_sync');
			$sync_user = $userhelper->userSynchronization();
			$this->sync_user = $sync_user;
		}
		else
		{
			$this->setLayout('default');
			JToolBarHelper::title(JText::_('COM_REDSHOP_USER_MANAGEMENT'), 'users redshop_user48');
			JToolbarHelper::addNew();
			JToolbarHelper::EditList();
			JToolBarHelper::deleteList();
		}

		$lists ['order']     = $this->state->get('list.ordering', 'users_info_id');
		$lists ['order_Dir'] = $this->state->get('list.direction');

		$user                = $this->get('Data');
		$pagination          = $this->get('Pagination');

		$shopper_groups      = Redshop\Helper\ShopperGroup::generateList();

		$temps               = array();
		$temps[0]            = new stdClass;
		$temps[0]->value     = 0;
		$temps[0]->text      = JText::_('COM_REDSHOP_SELECT');
		$shopper_groups      = array_merge($temps, $shopper_groups);

		$lists['shopper_group'] = JHTML::_('select.genericlist', $shopper_groups, 'spgrp_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit()"', 'value', 'text', $spgrp_filter
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
