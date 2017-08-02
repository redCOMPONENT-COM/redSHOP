<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewPrices_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRICE_MANAGEMENT_DETAIL'), 'redshop_vatrates48');

		$this->setLayout('default');

		$this->lists  = array();
		$this->detail = $this->get('data');
		$isNew        = ($this->detail->price_id < 1);
		$text         = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRICE') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_vatrates48');
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$this->lists['product_id']   = $this->detail->product_id;
		$this->lists['product_name'] = $this->detail->product_name;

				$shoppergroup = new shoppergroup;
		$this->lists['shopper_group_name'] = $shoppergroup->list_all("shopper_group_id", 0, array((int) $this->detail->shopper_group_id));

		$this->request_url = JFactory::getURI()->toString();

		parent::display($tpl);
	}
}
