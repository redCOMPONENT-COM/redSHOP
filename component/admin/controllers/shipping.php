<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopControllerShipping
 *
 * @since  1.0.0
 */
class RedshopControllerShipping extends RedshopController
{
	/**
	 * Method for import economic
	 *
	 * @return  void
	 */
	public function importeconomic()
	{
		// Add product to economic
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('s.*')
				->select('r.*')
				->from($db->qn('#__redshop_shipping_rate', 'r'))
				->leftJoin($db->qn('#__extensions', 's') . ' ON ' . $db->qn('r.shipping_class') . ' = ' . $db->qn('s.element'))
				->where($db->qn('s.enabled') . ' = 1')
				->where($db->qn('s.folder') . ' = ' . $db->quote('redshop_shipping'));

			$shippingList = $db->setQuery($query)->loadObjectList();

			if (!empty($shippingList))
			{
				foreach ($shippingList as $shipping)
				{
					$shortName = (strlen($shipping->name) > 15) ? substr($shipping->name, 0, 15) : $shipping->name;
					$number    = $shortName . ' ' . $shipping->shipping_rate_id;
					$name      = $shipping->shipping_rate_name;
					$rate      = $shipping->shipping_rate_value;

					if ($shipping->economic_displayname)
					{
						$number = $shipping->economic_displayname;
					}

					Redshop\Economic\Helper::createShippingRateInEconomic($number, $name, $rate, $shipping->apply_vat);
				}
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping', JText::_("COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC_SUCCESS"));
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

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('shipping');
		$model->saveorder($cid, $order);

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

		$model = $this->getModel('shipping_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping');
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('shipping_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_redshop&view=shipping');
	}

	/**
	 * logic for orderup
	 *
	 * @access public
	 * @return void
	 */
	public function orderup()
	{
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
		$model = $this->getModel('shipping_detail');
		$model->move(1);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=shipping', $msg);
	}
}
