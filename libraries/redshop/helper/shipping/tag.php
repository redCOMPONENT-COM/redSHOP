<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Shipping Tag
 *
 * @since  2.0.7
 */
class RedshopHelperShippingTag
{
	/**
	 * Replace shipping method
	 *
	 * @param   stdClass $shipping Shipping data
	 * @param   string   $content  Template content
	 *
	 * @return  string
	 *
	 * @deprecated __DEPLOY_VERSION__
	 */
	public static function replaceShippingMethod($shipping, $content = "")
	{
		return Redshop\Shipping\Tag::replaceShippingMethod($shipping, $content);
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param   string  $templateHtml    Template content
	 * @param   object  $shippingAddress Shipping address
	 * @param   boolean $sendMail        Is in send mail
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @deprecated __DEPLOY_VERSION__
	 */
	public static function replaceShippingAddress($templateHtml, $shippingAddress, $sendMail = false)
	{
		return Redshop\Shipping\Tag::replaceShippingAddress($templateHtml, $shippingAddress, $sendMail);
	}
}
