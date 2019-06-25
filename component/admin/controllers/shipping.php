<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

/**
 * Class RedshopControllerShipping
 *
 * @since  1.0.0
 */
class RedshopControllerShipping extends RedshopController
{
	public function importeconomic()
	{
		$db = JFactory::getDbo();

		// Add product to economic
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$query = "SELECT s.*, r.* FROM #__redshop_shipping_rate r "
				. "LEFT JOIN #__extensions s ON r.shipping_class = s.element "
				. "WHERE s.enabled=1 and s.folder='redshop_shipping'";

			$db->setQuery($query);
			$shipping = $db->loadObjectList();

			for ($i = 0, $in = count($shipping); $i < $in; $i++)
			{
				$shipping_nshortname = (strlen($shipping[$i]->name) > 15) ? substr($shipping[$i]->name, 0, 15) : $shipping[$i]->name;
				$shipping_number     = $shipping_nshortname . ' ' . $shipping[$i]->shipping_rate_id;
				$shipping_name       = $shipping[$i]->shipping_rate_name;
				$shipping_rate       = $shipping[$i]->shipping_rate_value;

				if ($shipping[$i]->economic_displayname)
				{
					$shipping_number = $shipping[$i]->economic_displayname;
				}

				Redshop\Economic\RedshopEconomic::createShippingRateInEconomic(
					$shipping_number, $shipping_name, $shipping_rate,
					$shipping[$i]->apply_vat
				);
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
	public function saveorder()
	{
		$cid   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		$cid   = ArrayHelper::toInteger($cid);
		$order = ArrayHelper::toInteger($order);

		/** @var RedshopModelShipping $model */
		$model = $this->getModel('shipping');

		$model->saveOrder($cid, $order);

		$msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		/** @var RedshopModelShipping_detail $model */
		$model = $this->getModel('shipping_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping', JText::_('COM_REDSHOP_SHIPPING_PUBLISHED_SUCCESSFULLY'));
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		/** @var RedshopModelShipping_detail $model */
		$model = $this->getModel('shipping_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping', JText::_('COM_REDSHOP_SHIPPING_UNPUBLISHED_SUCCESSFULLY'));
	}

	/**
	 * logic for orderup
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
		/** @var RedshopModelShipping_detail $model */
		$model = $this->getModel('shipping_detail');
		$model->move(-1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
	}

	/**
	 * logic for orderdown
	 *
	 * @access public
	 * @return void
	 */
	public function orderdown()
	{
		/** @var RedshopModelShipping_detail $model */
		$model = $this->getModel('shipping_detail');
		$model->move(1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
	}
}
