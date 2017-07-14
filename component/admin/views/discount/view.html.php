<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewDiscount extends RedshopViewAdmin
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_DISCOUNT'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT'), 'redshop_discountmanagmenet48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();
		$this->state = $this->get('State');

		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PERCENTAGE'));
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_TOTAL'));

		$lists['discount_type'] = JHTML::_('select.genericlist', $optiontype, 'discount_type',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $this->state->get('discount_type')
		);

		$lists['order'] = $this->state->get('list.ordering');
		$lists['order_Dir'] = $this->state->get('list.direction');
		$discounts = $this->get('Data');
		$pagination = $this->get('Pagination');

		$spgrpdis_filter = $this->state->get('spgrpdis_filter');
		$userhelper = rsUserHelper::getInstance();
		$shopper_groups = Redshop\Helper\ShopperGroup::generateList();

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = 0;
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
		$shopper_groups = array_merge($temps, $shopper_groups);
		$lists['shopper_group'] = JHTML::_('select.genericlist', $shopper_groups, 'spgrpdis_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit()"', 'value', 'text', $spgrpdis_filter
		);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->discounts = $discounts;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
