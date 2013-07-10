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
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

class product_priceViewproduct_price extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$product_id = JRequest::getVar('pid');

		$db       = JFactory::getDBO();
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
