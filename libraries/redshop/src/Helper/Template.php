<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Template helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Template
{
	/**
	 * @var array
	 */
	protected static $templates = array();

	/**
	 * Method for get accessory template
	 *
	 * @param   string  $templateHtml  Template HTML
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAccessory($templateHtml = "")
	{
		if (empty($templateHtml))
		{
			return new \stdClass;
		}

		if (!array_key_exists('accessory_template', self::$templates))
		{
			self::$templates['accessory_template'] = \RedshopHelperTemplate::getTemplate('accessory_template');
		}

		foreach (self::$templates['accessory_template'] as $accessoryTemplate)
		{
			if (strpos($templateHtml, "{accessory_template:" . $accessoryTemplate->name . "}") !== false)
			{
				if (empty($accessoryTemplate->template_desc))
				{
					$accessoryTemplate->template_desc = '<div class="accessory"><div class="accessory_info">'
						. '<h2>Accessories</h2>Add accessories by clicking in the box.</div>{accessory_product_start}'
						. '<div class="accessory_box"><div class="accessory_left">'
						. '<div class="accessory_image">{accessory_image}</div></div><div class="accessory_right">'
						. '<div class="accessory_title"><h3>{accessory_title}</h3></div><div class="accessory_desc">'
						. '{accessory_short_desc}</div><div class="accessory_readmore">{accessory_readmore}</div>'
						. '<div class="accessory_add">{accessory_price} {accessory_add_chkbox}</div>'
						. '<div class="accessory_qua">{accessory_quantity_lbl} {accessory_quantity}</div></div>'
						. '<div style="clear: left">&nbsp;&nbsp;</div></div>{accessory_product_end}</div>'
						. '<div style="clear: left">&nbsp;&nbsp;</div>';
				}

				return $accessoryTemplate;
			}
		}

		return new \stdClass;
	}

	/**
	 * Method for get add-to-cart template
	 *
	 * @param   string  $templateHtml  Template HTML
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAddToCart($templateHtml = "")
	{
		if (empty($templateHtml))
		{
			return new \stdClass;
		}

		if (!array_key_exists('add_to_cart', self::$templates))
		{
			self::$templates['add_to_cart'] = \RedshopHelperTemplate::getTemplate('add_to_cart');
		}

		foreach (self::$templates['add_to_cart'] as $template)
		{
			if (strpos($templateHtml, "{form_addtocart:" . $template->name . "}") !== false)
			{
				if (empty($template->template_desc))
				{
					$template->template_desc = '<div style="clear: left;"></div><div class="cart-wrapper">'
						. '<div class="cart-quantity">{quantity_lbl}: {addtocart_quantity}</div>'
						. '<div class="cart-link">{addtocart_image_aslink}</div></div>';
				}

				return $template;
			}
		}

		return new \stdClass;
	}

	/**
	 * Method for get related-product template
	 *
	 * @param   string  $templateHtml  Template HTML
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getRelatedProduct($templateHtml = '')
	{
		if (empty($templateHtml))
		{
			return new \stdClass;
		}

		if (!array_key_exists('related_product', self::$templates))
		{
			self::$templates['related_product'] = \RedshopHelperTemplate::getTemplate('related_product');
		}

		foreach (self::$templates['related_product'] as $template)
		{
			if (strpos($templateHtml, "{related_product:" . $template->name . "}") !== false)
			{
				if (empty($template->template_desc))
				{
					$template->template_desc = '<div class="related_product_wrapper"><h2>Related Products</h2>'
						. '{related_product_start}<div class="related_product_inside">'
						. '<div class="related_product_left"><div class="related_product_image">'
						. '<div class="related_product_image_inside">{relproduct_image}</div></div></div>'
						. '<div class="related_product_right"><div class="related_product_name">{relproduct_name}</div>'
						. '<div class="related_product_price">{relproduct_price}</div>'
						. '<div class="related_product_desc">{relproduct_s_desc}</div>'
						. '<div class="related_product_readmore">{read_more}</div></div>'
						. '<div class="related_product_bottom">'
						. '<div class="related_product_attr">{attribute_template:attributes}</div>'
						. '<div class="related_product_addtocart">{form_addtocart:add_to_cart2}</div></div>'
						. '</div>{related_product_end}</div>';
				}

				return $template;
			}
		}

		return new \stdClass;
	}

	/**
	 * Method for get ajax detail box template
	 *
	 * @param   object  $product  Product data
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAjaxDetailBox($product)
	{
		if (!\Redshop::getConfig()->get('AJAX_CART_BOX'))
		{
			return new \stdClass;
		}

		$productTemplate = \RedshopHelperTemplate::getTemplate('product', $product->product_template);

		if (!array_key_exists('ajax_cart_detail_box', self::$templates))
		{
			$ajaxDetailData        = null;
			$defaultAjaxDetailData = null;

			$templates = \RedshopHelperTemplate::getTemplate('ajax_cart_detail_box');

			foreach ($templates as $template)
			{
				if (strpos($productTemplate[0]->template_desc, '{ajaxdetail_template:' . $template->name . '}') !== false)
				{
					$ajaxDetailData = $template;
					break;
				}

				if (\Redshop::getConfig()->get('DEFAULT_AJAX_DETAILBOX_TEMPLATE') == $template->id)
				{
					$defaultAjaxDetailData = $template;
				}
			}

			if (empty($ajaxDetailData) && !empty($defaultAjaxDetailData))
			{
				$ajaxDetailData = $defaultAjaxDetailData;
			}

			if (!empty($ajaxDetailData) && !empty($ajaxDetailData->template_desc))
			{
				$ajaxDetailData->template_desc = '<div id="ajax-cart"><div id="ajax-cart-attr">'
					. '{attribute_template:attributes}</div><div id="ajax-cart-access">{accessory_template:accessory}'
					. '</div>{if product_userfield}<div id="ajax-cart-user">{userfield-test}</div>'
					. '{product_userfield end if}<div id="ajax-cart-label">{form_addtocart:add_to_cart2}</div></div>';
			}

			self::$templates['ajax_cart_detail_box'] = $ajaxDetailData;
		}

		return self::$templates['ajax_cart_detail_box'];
	}

	/**
	 * Method for get attribute template
	 *
	 * @param   string   $templateHtml  Template html
	 * @param   boolean  $display       Is display?
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAttribute($templateHtml = "", $display = true)
	{
		if (empty($templateHtml))
		{
			return new \stdClass;
		}

		$displayName   = 'attribute_template';
		$noDisplayName = 'attributewithcart_template';

		if (\Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			$displayName   = 'attributewithcart_template';
			$noDisplayName = 'attribute_template';
		}

		if (!$display)
		{
			$displayName = $noDisplayName;
		}

		if ($displayName == 'attribute_template')
		{
			if (!array_key_exists('attribute_template', self::$templates))
			{
				self::$templates['attribute_template'] = \RedshopHelperTemplate::getTemplate($displayName);
			}

			$templates = self::$templates['attribute_template'];
		}
		else
		{
			if (!array_key_exists('attributewithcart_template', self::$templates))
			{
				self::$templates['attributewithcart_template'] = \RedshopHelperTemplate::getTemplate($displayName);
			}

			$templates = self::$templates['attributewithcart_template'];
		}

		foreach ($templates as $template)
		{
			if (strpos($templateHtml, "{" . $displayName . ":" . $template->name . "}") !== false)
			{
				return $template;
			}
		}

		return new \stdClass;
	}

	/**
	 * Method for get cart template
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCart()
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
