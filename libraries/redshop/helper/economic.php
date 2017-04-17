<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Economic
 *
 * @since  2.0.5
 */
class RedshopHelperEconomic
{
	public static function importRedshopProductToEconomic($offset, $limit = 10)
	{
		$economic = economic::getInstance();
		$db       = JFactory::getDbo();
		$incNo    = $offset;
		$query    = 'SELECT p.* FROM #__redshop_product AS p LIMIT ' . (int) $offset . ', ' . (int) $limit;
		$db->setQuery($query);
		$prd         = $db->loadObjectlist();
		$totalprd    = count($prd);
		$responcemsg = '';

		for ($i = 0, $in = count($prd); $i < $in; $i++)
		{
			$incNo++;
			$ecoProductNumber = $economic->createProductInEconomic($prd[$i]);
			$responcemsg      .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $prd[$i]->product_number . " -> ";

			// @TODO Use JLayout for HTML render instead hard code here
			if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
			{
				$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC_SUCCESS') . "</span>";
			}
			else
			{
				$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_PRODUCT_TO_ECONOMIC');

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
			$msg = JText::_("COM_REDSHOP_IMPORT_PRODUCT_TO_ECONOMIC_IS_COMPLETED");
		}

		return $msg;
	}
}