<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Render class
 *
 * @since  2.1.0
 */
class Render
{
	/**
	 * Method for render cart, replace tag in template
	 *
	 * @param   integer $productId        Product Id
	 * @param   integer $categoryId       Category Id
	 * @param   integer $accessoryId      Accessory Id
	 * @param   integer $relatedProductId Related product Id
	 * @param   string  $content          Template content
	 * @param   boolean $isChild          Is child product?
	 * @param   array   $userFields       User fields
	 * @param   integer $totalAttr        Total attributes
	 * @param   integer $totalAccessory   Total accessories
	 * @param   integer $countNoUserField Total user fields
	 * @param   integer $moduleId         Module Id
	 * @param   integer $giftcardId       Giftcard Id
	 *
	 * @return  mixed|string
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replace($productId = 0, $categoryId = 0, $accessoryId = 0, $relatedProductId = 0, $content = "", $isChild = false, $userFields = array(), $totalAttr = 0, $totalAccessory = 0, $countNoUserField = 0, $moduleId = 0, $giftcardId = 0)
	{
		\JPluginHelper::importPlugin('redshop_product');

		$input           = \JFactory::getApplication()->input;
		$productQuantity = $input->get('product_quantity');
		$itemId          = $input->getInt('Itemid');
		$productPreOrder = '';
		$userId          = \JFactory::getUser()->id;
		$fieldSection    = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;

		if ($relatedProductId != 0)
		{
			$productId = $relatedProductId;
		}
		elseif ($giftcardId != 0)
		{
			$productId = $giftcardId;
		}

		if ($giftcardId != 0)
		{
			$product      = \RedshopEntityGiftcard::getInstance($giftcardId)->getItem();
			$fieldSection = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
		}
		else
		{
			$product = \RedshopHelperProduct::getProductById($productId);

			if (isset($product->preorder))
			{
				$productPreOrder = $product->preorder;
			}
		}

		$taxExemptAddToCart = \RedshopHelperCart::taxExemptAddToCart($userId, true);

		$cartTemplate = \Redshop\Template\Helper::getAddToCart($content);

		$cartForm = $cartTemplate->template_desc;

		$cartTemplateWapper = \RedshopTagsReplacer::_(
			'addtocart',
			$cartForm,
			array(
				'productId' => $productId,
				'product' => $product,
				'totalAttr' => $totalAttr,
				'accessoryId' => $accessoryId,
				'relatedProductId' => $relatedProductId,
				'productPreOrder' => $productPreOrder,
				'product' => $product,
				'userId' => $userId,
				'giftcardId' => $giftcardId,
				'totalAccessory' => $totalAccessory,
				'countNoUserField' => $countNoUserField,
				'cartTemplate'  => $cartTemplate,
				'categoryId' => $categoryId,
				'content'   => $content,
				'isChild' => $isChild,
				'taxExemptAddToCart' => $taxExemptAddToCart,
				'userFields' => $userFields,
				'fieldSection' => $fieldSection,
				'cartForm' => $cartForm
			)
		);

		$content = str_replace("{form_addtocart:$cartTemplate->name}", $cartTemplateWapper, $content);

		return $content;
	}
}
