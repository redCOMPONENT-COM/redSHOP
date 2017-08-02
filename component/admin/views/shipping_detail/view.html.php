<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * redSHOP Shipping Detail view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0
 */
class RedshopViewShipping_Detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the States view
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->setLayout('default');
		$lists  = array();
		$detail = $this->get('data');

		// Load language file of the shipping plugin
		JFactory::getLanguage()->load('plg_redshop_shipping_' . strtolower($detail->element), JPATH_ADMINISTRATOR);

		// Load language file from plugin folder.
		JFactory::getLanguage()->load(
			'plg_redshop_shipping_' . strtolower($detail->element),
			JPATH_ROOT . '/plugins/redshop_shipping/' . $detail->element
		);

		$isNew = ($detail->extension_id < 1);
		$text  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHIPPING') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_shipping48');

		$path               = JPATH_ROOT . '/plugins';
		$configPath         = $path . '/' . $detail->folder . '/' . $detail->element . '/' . $detail->element . '.xml';
		$shippingConfigPath = $path . '/' . $detail->folder . '/' . $detail->element . '/' . $detail->element . '.cfg.php';

		if (JFile::exists($shippingConfigPath))
		{
			include_once $shippingConfigPath;
		}

		$params      = new Registry($detail->params, $configPath);
		$hasRate     = $params->get('is_shipper');
		$hasLocation = $params->get('shipper_location');

		if ($hasRate)
		{
			JToolbarHelper::custom('shipping_rate', 'redshop_shipping_rates32',
				JText::_('COM_REDSHOP_SHIPPING_RATE_LBL'), JText::_('COM_REDSHOP_SHIPPING_RATE_LBL'), false, false
			);
		}
		elseif ($hasLocation)
		{
			JToolbarHelper::custom('shipping_rate', 'redshop_shipping_rates32',
				JText::_('COM_REDSHOP_SHIPPING_LOCATION'), JText::_('COM_REDSHOP_SHIPPING_LOCATION'), false, false
			);
		}

		JToolbarHelper::apply();
		JToolbarHelper::save();
		JToolbarHelper::cancel();

		$lists['published'] = JHtml::_('redshopselect.booleanlist', 'published', 'class="inputbox"', $detail->enabled);

		$this->lists       = $lists;
		$this->detail      = $detail;

		parent::display($tpl);
	}
}
