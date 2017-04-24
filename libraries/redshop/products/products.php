<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.log.log');

/**
 * Products library
 *
 * @package     Redshop.Library
 * @subpackage  Product
 * @since       2.0.5
 */
class RedshopProducts
{
	protected static $instance = null;

	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * @param $offset
	 * @param $count
	 *
	 * @return mixed
	 */
	public function getProducts ($offset, $count = 10)
	{
		// @TODO Work with cache
		$db       = JFactory::getDbo();
		$query      = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product'));
		$db->setQuery($query, $offset, $count);

		// @TODO Return array of Entity object
		return $db->loadObjectList();
	}

	public function importEconomic($offset)
	{
		// Add product to economic
		$totalProducts = 0;
		$message      = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$economic = economic::getInstance();
			$products         = $this->getProducts($offset);
			$totalProducts    = count($products);
			$responceMsg = '';

			if ($products)
			{
				foreach ($products as $index => $product)
				{

					$ecoProductNumber = $economic->createProductInEconomic($product);
					$responceMsg      .= "<div>" . $index . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $product->product_number . " -> ";

					if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
					{
						$responceMsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC_SUCCESS') . "</span>";
					}
					else
					{
						$errorMessage = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_PRODUCT_TO_ECONOMIC');

						if (JError::isError(JError::getError()))
						{
							$errorMessage = JError::getError()->getMessage();
						}

						$responceMsg .= "<span style='color: #ff0000'>" . $errorMessage . "</span>";
					}

					$responceMsg .= "</div>";
				}
			}

			if ($totalProducts > 0)
			{
				$message = $responceMsg;
			}
			else
			{
				$message = JText::_("COM_REDSHOP_IMPORT_PRODUCT_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalProducts . "`_`" . $message . "</div>";
		die();

	}

	public function importAtteco($offset)
	{
		// Add product attribute to economic
		$cnt      = $offset;
		$totalprd = 0;
		$msg      = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 1)
		{
			$economic = economic::getInstance();

			$db    = JFactory::getDbo();
			$incNo = $cnt;
			$query = "SELECT ap.*, a.attribute_name, p.product_id, p.accountgroup_id "
				. "FROM #__redshop_product_attribute_property AS ap "
				. "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id=a.product_id "
				. "WHERE p.published=1 "
				. "AND p.product_id!='' "
				. "AND ap.property_number!='' "
				. "LIMIT " . $cnt . ", 10 ";
			$db->setQuery($query);
			$list        = $db->loadObjectlist();
			$totalprd    = count($list);
			$responcemsg = '';

			for ($i = 0, $in = count($list); $i < $in; $i++)
			{
				$incNo++;
				$prdrow                  = new stdClass;
				$prdrow->product_id      = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber        = $economic->createPropertyInEconomic($prdrow, $list[$i]);
				$responcemsg             .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PROPERTY_NUMBER') . " " . $list[$i]->property_number . " -> ";

				if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
				{
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_ATTRIBUTES_TO_ECONOMIC');

					if (JError::isError(JError::getError()))
					{
						$error  = JError::getError();
						$errmsg = $error->getMessage();
					}

					$responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
				}

				$responcemsg .= "</div>";
			}

			$query = "SELECT sp.*, ap.property_id, ap.property_name, p.product_id, p.accountgroup_id  FROM #__redshop_product_subattribute_color AS sp "
				. "LEFT JOIN #__redshop_product_attribute_property AS ap ON ap.property_id=sp.subattribute_id "
				. "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id=a.product_id "
				. "WHERE p.published=1 "
				. "AND p.product_id!='' "
				. "AND sp.subattribute_color_number!='' "
				. "LIMIT " . $cnt . ", 10 ";
			$db->setQuery($query);
			$list     = $db->loadObjectlist();
			$totalprd = $totalprd + count($list);

			for ($i = 0, $in = count($list); $i < $in; $i++)
			{
				$incNo++;
				$prdrow                  = new stdClass;
				$prdrow->product_id      = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber        = $economic->createSubpropertyInEconomic($prdrow, $list[$i]);
				$responcemsg             .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER') . " "
					. $list[$i]->subattribute_color_number . " -> ";

				if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
				{
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_ATTRIBUTES_TO_ECONOMIC');

					if (JError::isError(JError::getError()))
					{
						$error  = JError::getError();
						$errmsg = $error->getMessage();
					}

					$responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
				}

				$responcemsg .= "</div>";
			}

			if ($totalprd > 0)
			{
				$msg = $responcemsg;
			}
			else
			{
				$msg = JText::_("COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}
}