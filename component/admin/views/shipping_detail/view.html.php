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

class shipping_detailViewshipping_detail extends JView
{
	public function display($tpl = null)
	{
		$uri    = JFactory::getURI();
		$this->setLayout('default');
		$lists  = array();
		$detail = $this->get('data');

		$isNew  = ($detail->extension_id < 1);
		$text   = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHIPPING') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_shipping48');

		$adminpath    = JPATH_ROOT . '/plugins';
		$shippingpath = $adminpath . DS . $detail->folder . DS . $detail->element . DS . $detail->element . '.xml';
		$shippingcfg  = $adminpath . DS . $detail->folder . DS . $detail->element . DS . $detail->element . '.cfg.php';

		if (file_exists($shippingcfg))
		{
			include_once $shippingcfg;
		}

		$myparams         = new JRegistry($detail->params, $shippingpath);
		$is_shipper       = $myparams->get('is_shipper');
		$shipper_location = $myparams->get('shipper_location');

		if ($is_shipper)
		{
			JToolBarHelper::custom('shipping_rate', 'redshop_shipping_rates32',
				JText::_('COM_REDSHOP_SHIPPING_RATE_LBL'), JText::_('COM_REDSHOP_SHIPPING_RATE_LBL'), false, false
			);
		}
		elseif ($shipper_location)
		{
			JToolBarHelper::custom('shipping_rate', 'redshop_shipping_rates32',
				JText::_('COM_REDSHOP_SHIPPING_LOCATION'), JText::_('COM_REDSHOP_SHIPPING_LOCATION'), false, false
			);
		}

		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->enabled);

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
