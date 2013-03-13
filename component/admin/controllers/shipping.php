<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');

class shippingcontroller extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	function cancel()
	{
		$this->setRedirect('index.php');
	}

	function display()
	{
		parent::display();
	}

	function importeconomic()
	{
		$db = JFactory::getDBO();
		#Add product to economic
		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic();

			$query = "SELECT s.*, r.* FROM #__redshop_shipping_rate r "
				. "LEFT JOIN #__extensions s ON r.shipping_class = s.element "
				. "WHERE s.enabled=1 and s.folder='redshop_shipping'"//					."AND r.apply_vat=1"
			;
			$db->setQuery($query);
			$shipping = $db->loadObjectList();

			for ($i = 0; $i < count($shipping); $i++)
			{
				$shipping_nshortname = (strlen($shipping[$i]->name) > 15) ? substr($shipping[$i]->name, 0, 15) : $shipping[$i]->name;
				$shipping_number = $shipping_nshortname . ' ' . $shipping[$i]->shipping_rate_id;
				$shipping_name = $shipping[$i]->shipping_rate_name;
				$shipping_rate = $shipping[$i]->shipping_rate_value;
				if ($shipping[$i]->economic_displayname)
					$shipping_number = $shipping[$i]->economic_displayname;
				$ecoShippingrateNumber = $economic->createShippingRateInEconomic($shipping_number, $shipping_name, $shipping_rate, $shipping[$i]->apply_vat);
			}
		}
		$msg = JText::_("COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC_SUCCESS");
		$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('shipping');
		$model->saveorder($cid);

		$msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
	}
}

?>
