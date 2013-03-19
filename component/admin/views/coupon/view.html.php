<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class couponViewcoupon extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$context = 'coupon_id';
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_COUPON'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUPON_MANAGEMENT'), 'redshop_coupon48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri =& JFactory::getURI();
		$context = "rating";
		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'coupon_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$coupons = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');


		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('coupons', $coupons);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}

?>
