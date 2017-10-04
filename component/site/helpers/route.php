<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_redshop
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Redshop Component Route Helper.
 *
 * @since  2.0.7
 */
abstract class RedshopHelperRoute
{
	/**
	 * Get the product route.
	 *
	 * @param   integer  $id    The route of the content item.
	 * @param   integer  $cid   The category ID.
	 *
	 * @return  string  The article route.
	 *
	 * @since   12.0.7
	 */
	public static function getProductRoute($id, $cid = 0)
	{
		// Create the link
		$link = 'index.php?option=com_redshop&view=product&pid=' . $id;

		if ((int) $cid > 1)
		{
			$link .= '&cid=' . $cid;
		}

		$producthelper   = productHelper::getInstance();

		$itemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $id);

		if (count($itemData) > 0)
		{
			$itemId = $itemData->id;
		}
		else
		{
			$itemId = RedshopHelperUtility::getItemId($id, $cid);
		}

		$link .= '&Itemid=' . $itemId;

		return $link;
	}
}
