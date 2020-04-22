<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Template;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Template
 *
 * @since       2.1.0
 */
class Cart
{
	/**
	 * @var array
	 *
	 * @since   2.1.0
	 */
	protected static $templates = array();

	/**
	 * Method for get cart template
	 *
	 * @return  array
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function getCartTemplate()
	{
		if (!array_key_exists('cart', self::$templates))
		{
			if (\Redshop::getConfig()->get('USE_AS_CATALOG'))
			{
				self::$templates['cart'] = \RedshopHelperTemplate::getTemplate('cart');
			}
			else
			{
				self::$templates['cart'] = \RedshopHelperTemplate::getTemplate('catalogue_cart');
			}
		}

		return self::$templates['cart'];
	}
}
