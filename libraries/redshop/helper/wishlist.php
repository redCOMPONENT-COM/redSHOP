<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wishlist functions for redSHOP
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperWishlist
{
	/**
	 * Method for replace wishlist tag in template.
	 *
	 * @param   int     $productId        Product ID
	 * @param   string  $templateContent  HTML data of template content
	 *
	 * @return  string                    HTML data of replaced content.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceWishlistTag($productId = 0, $templateContent = '')
	{
		if (Redshop::getConfig()->get('MY_WISHLIST') == 0)
		{
			$templateContent = str_replace('{wishlist_button}', '', $templateContent);
			$templateContent = str_replace('{wishlist_link}', '', $templateContent);

			return $templateContent;
		}

		return RedshopTagsReplacer::_('wishlist', $templateContent, array('productId' => $productId));
	}
}
