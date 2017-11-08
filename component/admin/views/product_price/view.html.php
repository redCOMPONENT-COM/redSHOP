<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct_price extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$product_id = JFactory::getApplication()->input->get('pid');

		$db       = JFactory::getDbo();
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_PRICE'));
		jimport('joomla.html.pagination');
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');

		$sql = "SELECT * FROM #__redshop_product WHERE product_id = '$product_id'";
		$db->setQuery($sql);
		$product = $db->loadObject();

		$sql = "SELECT g.*,p.product_price,p.price_id,p.price_quantity_end,p.price_quantity_start FROM #__redshop_shopper_group g LEFT JOIN #__redshop_product_price p ON g.shopper_group_id = p.shopper_group_id   AND product_id = '$product_id'";
		$db->setQuery($sql);
		$prices = $db->loadObjectList();

		$this->product = $product;

		$this->prices = $prices;

		$this->pid = $product_id;

		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
