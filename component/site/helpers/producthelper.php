<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

jimport('joomla.filesystem.file');

class productHelper
{
	public $_db = null;

	public $_userdata = null;

	public $_table_prefix = null;

	public $_product_level = 0;

	public $_userhelper = null;

	public $_session = null;

	public $_cartTemplateData = null;

	public $_ajaxdetail_templatedata = null;

	public $_vatCountry = null;

	public $_vatState = null;

	public $_vatGroup = null;

	public $_taxData = array();

	public $_cart_template = null;

	public $_acc_template = null;

	public $_attribute_template = null;

	public $_attributewithcart_template = null;

	protected static $productSpecialIds = array();

	protected static $productDateRange = array();

	protected static $instance = null;

	/**
	 * Returns the productHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  productHelper  The productHelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->_db           = JFactory::getDbo();
		$this->_table_prefix = '#__redshop_';
		$this->_userhelper   = rsUserHelper::getInstance();
		$this->_session      = JFactory::getSession();
	}

	/**
	 * Method for get Wishlist module base in element name
	 *
	 * @param   string  $elementName  Element name
	 *
	 * @return  object|null
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6  Use RedshopHelperWishlist::getWishlistModule instead
	 */
	public function getWishlistModule($elementName)
	{
		return RedshopHelperWishlist::getWishlistModule($elementName);
	}

	/**
	 * Method for get Wishlist user field data
	 *
	 * @param   integer  $wishlistId  Wish list id
	 * @param   integer  $productId   Product Id
	 *
	 * @return  array
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6
	 */
	public function getwishlistuserfieldata($wishlistId, $productId)
	{
		return RedshopHelperWishlist::getUserFieldData($wishlistId, $productId);
	}

	/**
	 * Get Main Product Query
	 *
	 * @param   bool|JDatabaseQuery  $query   Get query or false
	 * @param   int                  $userId  User id
	 *
	 * @deprecated  1.5 Use RedshopHelperProduct::getMainProductQuery instead
	 *
	 * @return JDatabaseQuery
	 */
	public function getMainProductQuery($query = false, $userId = 0)
	{
		return RedshopHelperProduct::getMainProductQuery($query, $userId);
	}

	/**
	 * Get product information
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $userId     User id
	 *
	 * @deprecated  1.5 Use RedshopHelperProduct::getProductById instead
	 *
	 * @return mixed
	 */
	public function getProductById($productId, $userId = 0)
	{
		return RedshopHelperProduct::getProductById($productId, $userId);
	}

	/**
	 * Set product array
	 *
	 * @param   array  $products  Array product/s values
	 *
	 * @return void
	 *
	 * @deprecated  1.5 Use RedshopHelperProduct::setProduct instead
	 */
	public function setProduct($products)
	{
		RedshopHelperProduct::setProduct($products);
	}

	/**
	 * Method for check country in EU area or not
	 *
	 * @param   string  $country  Country code
	 *
	 * @return  boolean
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6
	 */
	public function country_in_eu_common_vat_zone($country)
	{
		return RedshopHelperUtility::isCountryInEurope($country);
	}

	/**
	 * Get Product Prices
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $userId     User id
	 * @param   int  $quantity   Quantity
	 *
	 * @return mixed
	 */
	public function getProductPrices($productId, $userId, $quantity = 1)
	{
		return RedshopHelperProduct::getProductPrices($productId, $userId, $quantity);
	}

	/**
	 * Get Product Special Price
	 *
	 * @param   float   $productPrice       Product price
	 * @param   string  $discountStringIds  Discount ids
	 * @param   int     $productId          Product id
	 *
	 * @return  null|object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProductPrice::getProductSpecialPrice
	 */
	public function getProductSpecialPrice($productPrice, $discountStringIds, $productId = 0)
	{
		return RedshopHelperProductPrice::getProductSpecialPrice($productPrice, $discountStringIds, $productId);
	}

	/**
	 * Get Product Special Id
	 *
	 * @param   int  $userId  User Id
	 *
	 * @return  string
	 */
	public function getProductSpecialId($userId)
	{
		if (array_key_exists($userId, self::$productSpecialIds))
		{
			return self::$productSpecialIds[$userId];
		}

		$db = JFactory::getDbo();

		if ($userId)
		{
			$query = $db->getQuery(true)
				->select('ps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'ps'))
				->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ui.shopper_group_id = ps.shopper_group_id')
				->where('ui.user_id = ' . (int) $userId)
				->where('ui.address_type = ' . $db->q('BT'));
		}
		else
		{
			$userArr = $this->_session->get('rs_user');

			if (empty($userArr))
			{
				$userArr = $this->_userhelper->createUserSession($userId);
			}

			$shopperGroupId = isset($userArr['rs_user_shopperGroup']) ? $userArr['rs_user_shopperGroup'] : $this->_userhelper->getShopperGroup($userId);
			$query = $db->getQuery(true)
				->select('dps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'dps'))
				->where('dps.shopper_group_id =' . (int) $shopperGroupId);
		}

		$db->setQuery($query);
		$result = $db->loadColumn();
		self::$productSpecialIds[$userId] = '0';

		if (count($result) > 0)
		{
			self::$productSpecialIds[$userId] .= ',' . implode(',', $result);
		}

		return self::$productSpecialIds[$userId];
	}

	/**
	 * Method for get product tax
	 *
	 * @param   integer  $product_id     Product Id
	 * @param   integer  $product_price  Product price
	 * @param   integer  $user_id        User ID
	 * @param   integer  $tax_exempt     Tax exempt
	 *
	 * @return  integer
	 *
	 * @deprecated   2.0.6
	 */
	public function getProductTax($product_id = 0, $product_price = 0, $user_id = 0, $tax_exempt = 0)
	{
		return RedshopHelperProduct::getProductTax($product_id, $product_price, $user_id, $tax_exempt);
	}

	/**
	 * Method for replace tags about VAT information
	 *
	 * @param   string  $data_add  Template data.
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.6
	 */
	public function replaceVatinfo($data_add)
	{
		return RedshopHelperTax::replaceVatInformation($data_add);
	}

	/**
	 * Check user for Tax Exemption approved
	 *
	 * @param   integer  $user_id              User Information Id - Login user id
	 * @param   integer  $btn_show_addto_cart  Display Add to cart button for tax exemption user
	 *
	 * @return  boolean  true if VAT applied else false
	 */
	public function taxexempt_addtocart($user_id = 0, $btn_show_addto_cart = 0)
	{
		return RedshopHelperCart::taxExemptAddToCart($user_id, (boolean) $btn_show_addto_cart);
	}

	/**
	 * Get VAT User information
	 *
	 * @param   integer  $user_id  User ID
	 *
	 * @return  object
	 *
	 * @deprecated   2.0.6
	 */
	public function getVatUserinfo($user_id = 0)
	{
		return RedshopHelperUser::getVatUserInformation($user_id);
	}

	/**
	 * get VAT rates from product or global
	 *
	 * @param   int  $productId  Id current product
	 * @param   int  $userId     Id current user
	 *
	 * @return  object|null  VAT rates information
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 *
	 * @see  RedshopHelperTax::getVatRates
	 */
	public function getVatRates($productId = 0, $userId = 0)
	{
		return RedshopHelperTax::getVatRates($productId, $userId);
	}

	/**
	 * Get ExtraFields For Current Template
	 *
	 * @param   array   $filedNames      Field name list
	 * @param   string  $templateData    Template data
	 * @param   int     $isCategoryPage  Flag change extra fields in category page
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.6
	 */
	public function getExtraFieldsForCurrentTemplate($filedNames = array(), $templateData = '', $isCategoryPage = 0)
	{
		return RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($filedNames, $templateData, $isCategoryPage);
	}

	/**
	 * Parse extra fields for template for according to section.
	 *
	 * @param   array    $fieldNames       List of field names
	 * @param   integer  $productId        ID of product
	 * @param   integer  $section          Section
	 * @param   string   $templateContent  Template content
	 * @param   integer  $categoryPage     Argument for product section extra field for category page
	 *
	 * @return  string
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function getExtraSectionTag($fieldNames = array(), $productId = 0, $section = 0, $templateContent = '', $categoryPage = 0)
	{
		return RedshopHelperProductTag::getExtraSectionTag($fieldNames, $productId, $section, $templateContent, $categoryPage);
	}

	/**
	 * Method for replace price.
	 *
	 * @param   float  $productPrice  Product price
	 *
	 * @return  string
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function getPriceReplacement($productPrice)
	{
		return RedshopHelperProductPrice::priceReplacement($productPrice);
	}

	/**
	 * Format Product Price
	 *
	 * @param   float    $productPrice    Product price
	 * @param   boolean  $convert         Decide to convert price in Multi Currency
	 * @param   string   $currencySymbol  Product Formatted Price
	 *
	 * @return  string                    Formatted Product Price
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function getProductFormattedPrice($productPrice, $convert = true, $currencySymbol = '_NON_')
	{
		return RedshopHelperProductPrice::formattedPrice($productPrice, $convert, $currencySymbol);
	}

	/**
	 * Method for round product price
	 *
	 * @param   float  $productPrice  Product price
	 *
	 * @return  float
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function productPriceRound($productPrice)
	{
		return RedshopHelperProductPrice::priceRound($productPrice);
	}

	public function getProductparentImage($product_parent_id)
	{
		$result = $this->getProductById($product_parent_id);

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = $this->getProductparentImage($result->product_parent_id);
		}

		return $result;
	}

	/**
	 * Get Product image
	 *
	 * @param   integer  $product_id               Product Id
	 * @param   string   $link                     Product link
	 * @param   integer  $width                    Product image width
	 * @param   integer  $height                   Product image height
	 * @param   integer  $Product_detail_is_light  Product detail is light
	 * @param   integer  $enableHover              Enable hover
	 * @param   integer  $suffixid                 Suffix id
	 * @param   array    $preselectedresult        Preselected result
	 *
	 * @return  string   Product Image
	 */
	public function getProductImage($product_id = 0, $link = '', $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $suffixid = 0, $preselectedresult = array())
	{
		$thum_image      = '';
		$stockroomhelper = rsstockroomhelper::getInstance();
		$result          = $this->getProductById($product_id);

		$isStockExists = $stockroomhelper->isStockExists($product_id);

		$middlepath = REDSHOP_FRONT_IMAGES_RELPATH . "product/";

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = $this->getProductparentImage($result->product_parent_id);
		}

		$cat_product_hover = false;

		if ($enableHover && Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_ENABLE'))
		{
			$cat_product_hover = true;
		}

		$product_image = $result->product_full_image;

		if ($Product_detail_is_light != 2)
		{
			if ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
			{
				$product_image = $result->product_thumb_image;
			}
		}

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		// Trigger to change product image.
		$dispatcher->trigger('changeProductImage', array(&$thum_image, $result, $link, $width, $height, $Product_detail_is_light, $enableHover, $suffixid));

		if (!empty($thum_image))
		{
			return $thum_image;
		}

		if (!$isStockExists && Redshop::getConfig()->get('USE_PRODUCT_OUTOFSTOCK_IMAGE') == 1)
		{
			if (Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE') && file_exists($middlepath . Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE')))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE'),
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
			elseif ($product_image && file_exists($middlepath . $product_image))
			{
				if ($result->product_full_image && file_exists($middlepath . $result->product_full_image)
					&& ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image)))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						$result->product_thumb_image,
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						$preselectedresult,
						$suffixid
					);
				}
				elseif ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						"",
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						$preselectedresult,
						$suffixid
					);
				}
				elseif ($result->product_full_image && file_exists($middlepath . $result->product_full_image))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						"",
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						$preselectedresult,
						$suffixid
					);
				}
			}
			else
			{
				$thum_image = $this->replaceProductImage(
					$result,
					"",
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
		}
		elseif ($product_image && file_exists($middlepath . $product_image))
		{
			if ($result->product_full_image && file_exists($middlepath . $result->product_full_image)
				&& ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image)))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					$result->product_thumb_image,
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
			elseif ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
			elseif ($result->product_full_image && file_exists($middlepath . $result->product_full_image))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
		}
		else
		{
			if (Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE') && file_exists($middlepath . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					$preselectedresult,
					$suffixid
				);
			}
		}

		return $thum_image;
	}

	public function replaceProductImage($product, $imagename = "", $linkimagename = "", $link = "", $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $preselectedResult = array(), $suffixid = 0)
	{
		$url           = JURI::root();
		$imagename     = trim($imagename);
		$linkimagename = trim($linkimagename);
		$product_id    = $product->product_id;
		$redhelper     = redhelper::getInstance();
		$dispatcher    = RedshopHelperUtility::getDispatcher();

		$middlepath    = REDSHOP_FRONT_IMAGES_RELPATH . "product/";
		$product_image = $product->product_full_image;

		if ($Product_detail_is_light != 2)
		{
			if ($product->product_thumb_image && file_exists($middlepath . $product->product_thumb_image))
			{
				$product_image = $product->product_thumb_image;
			}
		}

		$altText = $this->getAltText('product', $product_id, $product_image);
		$altText = empty($altText) ? $product->product_name : $altText;

		$dispatcher    = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

		$title = " title='" . $altText . "' ";
		$alt   = " alt='" . $altText . "' ";

		$cat_product_hover = false;

		if ($enableHover && Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_ENABLE'))
		{
			$cat_product_hover = true;
		}

		$noimage = "noimage.jpg";

		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;

		$product_hover_img = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;
		$linkimage         = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;

		if ($imagename != "")
		{
			$product_img = RedshopHelperMedia::watermark('product', $imagename, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0');

			if ($cat_product_hover)
				$product_hover_img = RedshopHelperMedia::watermark('product',
					$imagename,
					Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'),
					Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'),
					Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'),
					'2');

			if ($linkimagename != "")
			{
				$linkimage = RedshopHelperMedia::watermark('product', $linkimagename, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
			}
			else
			{
				$linkimage = RedshopHelperMedia::watermark('product', $imagename, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
			}
		}

		if (count($preselectedResult) > 0)
		{
			$product_img = $preselectedResult['product_mainimg'];
			$title       = " title='" . $preselectedResult['aTitleImageResponse'] . "' ";
			$linkimage   = $preselectedResult['aHrefImageResponse'];
		}

		$commonid = ($suffixid) ? $product_id . '_' . $suffixid : $product_id;

		if ($Product_detail_is_light != 2 && $Product_detail_is_light != 1)
		{
			$thum_image = "<img id='main_image" . $commonid . "' src='" . $product_img . "' " . $title . $alt . " />";
		}
		else
		{
			if ($Product_detail_is_light == 1)
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $linkimage . "' rel=\"myallimg\">";
			}
			elseif (Redshop::getConfig()->get('PRODUCT_IS_LIGHTBOX') == 1)
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $linkimage
					. "' class=\"modal\" rel=\"{handler: 'image', size: {}}\">";
			}
			else
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $link . "'>";
			}

			$thum_image .= "<img id='main_image" . $commonid . "' src='" . $product_img . "' " . $title . $alt . " />";

			if ($cat_product_hover)
			{
				$thum_image .= "<img id='main_image" . $commonid . "' src='" . $product_hover_img . "' "
					. $title . $alt . " class='redImagepreview' />";
			}

			$thum_image .= "</a>";
		}

		if ($cat_product_hover)
		{
			$thum_image = "<div class='redhoverImagebox'>" . $thum_image . "</div>";
		}
		else
		{
			$thum_image = "<div>" . $thum_image . "</div>";
		}

		$dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

		return $thum_image;
	}

	public function getProductCategoryImage($product_id = 0, $category_img = '', $link = '', $width, $height)
	{
		$redhelper  = redhelper::getInstance();
		$result     = $this->getProductById($product_id);
		$thum_image = "";
		$title      = " title='" . $result->product_name . "' ";
		$alt        = " alt='" . $result->product_name . "' ";

		if ($category_img && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $category_img))
		{
			if (Redshop::getConfig()->get('PRODUCT_IS_LIGHTBOX') == 1)
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$product_hover_img = RedshopHelperMedia::watermark('product', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$linkimage         = RedshopHelperMedia::watermark('category', $category_img, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $linkimage . "' " . $title . "  rel=\"myallimg\">";
				$thum_image .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";

				$thum_image .= "</a>";
			}
			else
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$product_hover_img = RedshopHelperMedia::watermark('category', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $link . "' " . $title . ">";
				$thum_image .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";
				$thum_image .= "</a>";
			}
		}

		return $thum_image;
	}

	public function getProductMinDeliveryTime($product_id = 0, $section_id = 0, $section = '', $loadDiv = 1)
	{
		$helper = redhelper::getInstance();

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (!$section_id && !$section)
		{
			$query
				->from($db->qn('#__redshop_product_stockroom_xref') . ' AS ps')
				->where($db->qn('ps.product_id') . ' = ' . (int) $product_id);
		}
		else
		{
			$query
				->from($db->qn('#__redshop_product_attribute_stockroom_xref') . ' AS ps')
				->where($db->qn('ps.section_id') . ' = ' . (int) $section_id)
				->where($db->qn('ps.section') . ' = ' . $db->q($section));
		}

		// Create the base select statement.
		$query->select(
			array(
				'min_del_time as deltime',
				's.max_del_time',
				's.delivery_time'
			)
		)
			->join('', $db->qn('#__redshop_stockroom') . ' AS s')
			->where($db->qn('ps.stockroom_id') . ' = ' . $db->qn('s.stockroom_id'))
			->where($db->qn('ps.quantity') . ' > 0 ')
			->order($db->qn('min_del_time') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query, 0, 1);

		try
		{
			$result = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$product_delivery_time = '';

		if ($result)
		{
			// Append where clause to get Maximum Delivery time of Minimum Delivery stockroom
			$query->where($db->qn('s.min_del_time') . ' = ' . (int) $result->deltime);

			// Set the query and load the row.
			$db->setQuery($query, 0, 1);

			try
			{
				$row = $db->loadObject();
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage(), $e->getCode());
			}

			if ($row->deltime == 0 || $row->deltime == ' ')
			{
				$product_delivery_time = '';
			}
			else
			{
				if ($row->delivery_time == "Days")
				{
					$duration = JText::_('COM_REDSHOP_DAYS');
				}
				else
				{
					$row->deltime      = $row->deltime / 7;
					$row->max_del_time = $row->max_del_time / 7;
					$duration          = JText::_('COM_REDSHOP_WEEKS');
				}

				$product_delivery_time = (int) $row->deltime . "-" . (int) $row->max_del_time . " " . $duration;
			}
		}

		if ($product_delivery_time && $loadDiv)
		{
			$product_delivery_time = '<div id="ProductAttributeMinDelivery' . $product_id . '">' . $product_delivery_time . '</div>';
		}

		return $product_delivery_time;
	}

	public function GetDefaultQuantity($product_id = 0, $data_add = "")
	{
		$cart_template = $this->getAddtoCartTemplate($data_add);
		$cartform      = (count($cart_template) > 0) ? $cart_template->template_desc : "";
		$qunselect     = 1;

		if (strstr($cartform, "{addtocart_quantity_selectbox}"))
		{
			$product = $this->getProductById($product_id);

			if ((Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') != "" && $product->quantity_selectbox_value == '')
				|| $product->quantity_selectbox_value != '')
			{
				$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
				$quaboxarr       = explode(",", $selectbox_value);
				$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
				sort($quaboxarr);

				for ($q = 0, $qn = count($quaboxarr); $q < $qn; $q++)
				{
					if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
					{
						$qunselect = intVal($quaboxarr[$q]);
						break;
					}
				}
			}
		}

		return $qunselect;
	}

	/**
	 * Method for get product show price
	 *
	 * @param   integer  $productId     Product ID
	 * @param   string   $templateHtml  Template content
	 * @param   string   $seoTemplate   SEO template
	 * @param   int      $userId        User ID
	 * @param   int      $isRel         Is Rel
	 * @param   array    $attributes    Attributes
	 *
	 * @return  mixed|string
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 *
	 * @see  RedshopHelperProductPrice::getShowPrice()
	 */
	public function getProductShowPrice($productId, $templateHtml, $seoTemplate = "", $userId = 0, $isRel = 0, $attributes = array())
	{
		return RedshopHelperProductPrice::getShowPrice($productId, $templateHtml, $seoTemplate, $userId, (boolean) $isRel, $attributes);
	}

	/**
	 * Method for get product net price
	 *
	 * @param   integer  $productId   ID of product
	 * @param   integer  $userId      ID of user
	 * @param   integer  $quantity    Quantity for get
	 * @param   string   $dataAdd     Template data
	 * @param   array    $attributes  Attributes list.
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function getProductNetPrice($productId, $userId = 0, $quantity = 1, $dataAdd = '', $attributes = array())
	{
		return RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity, $dataAdd, $attributes);
	}

	/**
	 * Get Layout product quantity price
	 *
	 * @param   int  $productId  Product Id
	 * @param   int  $userId     User Id
	 *
	 * @deprecated  1.5  Use RedshopHelperProduct::getProductQuantityPrice instead
	 *
	 * @return  mixed  Redshop Layout
	 */
	public function getProductQuantityPrice($productId, $userId)
	{
		return RedshopHelperProduct::getProductQuantityPrice($productId, $userId);
	}

	public function getDiscountId($subtotal = 0, $user_id = 0)
	{
		return RedshopHelperDiscount::getDiscount($subtotal, $user_id);
	}

	public function getDiscountAmount($cart = array(), $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if (count($cart) <= 0)
		{
			$cart = $this->_session->get('cart');
		}

		$discount = $this->getDiscountId($cart['product_subtotal'], $user_id);

		$discount_amount_final = 0;
		$discountVAT     = 0;

		if (!empty($discount))
		{
			$product_subtotal = $cart['product_subtotal'] + $cart['shipping'];

			// Discount total type
			if (isset($discount->discount_type) && $discount->discount_type == 0)
			{
				// 100% discount
				if ($discount->discount_amount > $product_subtotal)
				{
					$discount_amount = $product_subtotal;
				}
				else
				{
					$discount_amount = $discount->discount_amount;
				}

				$discount_percent = ($discount_amount * 100) / $product_subtotal;
			}
			// Disocunt percentage price
			else
			{
				$discount_percent = isset($discount->discount_amount)? $discount->discount_amount: 0;
			}

			// Apply even products already on discount
			if (Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'))
			{
				$discount_amount_final = $discount_percent * $product_subtotal / 100;
			}
			else
			{
				/*
					Checking which discount is the best
					Example 2 products in cart, 1 product 0% - 1 product 15%
					Cart total order discount of 10% for value over 1000, now that discount will be added to both products, so the product with 15% will now have 25% and the product with 0% will have 10%.
					The product with 25% should only have 15% discount as it's best practice and most logical setup
				*/

				$idx = 0;

				if (isset($cart['idx']))
				{
					$idx = $cart['idx'];
				}

				for ($i = 0; $i < $idx; $i++)
				{
					$product_price_array = $this->getProductNetPrice($cart[$i]['product_id']);

					// Product already discount
					if ($product_price_array['product_discount_price'] > 0)
					{
						// Restore to the origigal price
						$cart[$i]['product_price'] = $product_price_array['product_old_price'];
						$cart[$i]['product_price_excl_vat'] = $product_price_array['product_old_price_excl_vat'];
						$cart[$i]['product_vat']= $product_price_array['product_old_price'] - $product_price_array['product_old_price_excl_vat'];
					}

					// Checking the product discount < total discount => get total discount
					if ($product_price_array['product_price_saving_percentage'] <= $discount_percent)
					{
						$discount_amount = $discount_percent * $product_price_array['product_price'] / 100;
					}
					// Keep product discount
					else
					{
						$discount_amount = $product_price_array['product_price_saving'];
					}

					// With quantity
					$discount_amount_final += $discount_amount * $cart[$i]['quantity'];
				}
			}

			if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
			{
				$discountVAT = $discount_amount_final * (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
			}

			$cart['discount_tax'] = $discountVAT;

			$this->_session->set('cart', $cart);
		}

		return $discount_amount_final;
	}

	public function getProductPrice($product_id, $show_price_with_vat = 1, $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$row    = $this->getProductById($product_id);
		$result = $this->getProductPrices($product_id, $user_id);

		if (!empty($result))
		{
			$temp_Product_price = $result->product_price;
			$row->product_price = $temp_Product_price;
		}

		$discount_product_id = $this->getProductSpecialId($user_id);
		$res                 = $this->getProductSpecialPrice($row->product_price, $discount_product_id);

		if (!empty($res))
		{
			$discount_amount = 0;

			if (count($res) > 0)
			{
				if ($res->discount_type == 0)
				{
					$discount_amount = $res->discount_amount;
				}
				else
				{
					$discount_amount = ($row->product_price * $res->discount_amount) / (100);
				}
			}

			$row->product_price = $row->product_price - $discount_amount;

			if ($row->product_price < 0)
			{
				$row->product_price = 0;
			}
		}

		$tax_amount = 0;

		if ($show_price_with_vat && $row->product_price != 0)
		{
			$tax_amount = $this->getProductTax($row->product_id, $row->product_price, $user_id);
		}

		$product_price = $tax_amount + $row->product_price;

		return $product_price;
	}

	/**
	 * Method for get additional media images
	 *
	 * @param   int     $section_id  Section Id
	 * @param   string  $section     Section name
	 * @param   string  $mediaType   Media type
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function getAdditionMediaImage($section_id = 0, $section = "", $mediaType = "images")
	{
		return RedshopHelperMedia::getAdditionMediaImage($section_id, $section, $mediaType);
	}

	/**
	 * Get alternative text for media
	 *
	 * @param   string  $mediaSection  Media section
	 * @param   int     $sectionId     Section i
	 * @param   string  $mediaName     Media name
	 * @param   int     $mediaId       Media id
	 * @param   string  $mediaType     Media type
	 *
	 * @return  string  Alternative text from media
	 */
	public function getAltText($mediaSection, $sectionId, $mediaName = '', $mediaId = 0, $mediaType = 'images')
	{
		if ($mediaSection == 'product' && $mediaType = 'images')
		{
			if ($productData = $this->getProductById($sectionId))
			{
				if ($mediaName == $productData->product_full_image || $mediaId == $productData->media_id)
				{
					return $productData->media_alternate_text;
				}
			}
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('media_alternate_text')
			->from($db->qn('#__redshop_media'))
			->where('media_section = ' . $db->q($mediaSection))
			->where('section_id = ' . (int) $sectionId)
			->where('media_type = ' . $db->q($mediaType));

		if ($mediaName)
		{
			$query->where('media_name = ' . $db->q($mediaName));
		}

		if ($mediaId)
		{
			$query->where('media_id = ' . (int) $mediaId);
		}

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Get redshop user information
	 *
	 * @param   int     $userId       Id joomla user
	 * @param   string  $addressType  Type user address BT (Billing Type) or ST (Shipping Type)
	 * @param   int     $userInfoId   Id redshop user
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::getUserInformation instead
	 *
	 * @return  object  Redshop user information
	 */
	public function getUserInformation($userId = 0, $addressType = 'BT', $userInfoId = 0)
	{
		return RedshopHelperUser::getUserInformation($userId, $addressType, $userInfoId);
	}

	public function getApplyVatOrNot($data_add = "", $user_id = 0)
	{
		$user            = JFactory::getUser();
		$userInformation = array();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id != 0)
		{
			$userInformation = $this->getUserInformation($user_id);
		}

		if (count($userInformation) <= 0)
		{
			$userInformation = $this->GetdefaultshopperGroupData();
		}

		if (!empty($userInformation)
			&& isset($userInformation->show_price_without_vat)
			&& $userInformation->show_price_without_vat)
		{
			return false;
		}

		if (strpos($data_add, "{without_vat}") !== false)
		{
			return false;
		}
		else
		{
			return $this->taxexempt_addtocart($user_id);
		}

		return true;
	}

	public function getApplyattributeVatOrNot($template = "", $userId = 0)
	{
		$userInformation = new stdClass;

		if ($userId == 0)
		{
			$userId = JFactory::getUser()->id;
		}

		if ($userId != 0)
		{
			$userInformation = $this->getUserInformation($userId);
		}

		if (count((array) $userInformation) == 0)
		{
			$userInformation = $this->GetdefaultshopperGroupData();
		}

		if (!empty($userInformation)
			&& isset($userInformation->show_price_without_vat)
			&& $userInformation->show_price_without_vat)
		{
			return false;
		}

		if (strpos($template, "{attribute_price_without_vat}") !== false)
		{
			return false;
		}
		elseif (strpos($template, "{attribute_price_with_vat}") !== false)
		{
			return true;
		}
		else
		{
			return $this->taxexempt_addtocart($userId);
		}

		return true;
	}

	public function GetdefaultshopperGroupData()
	{
		$list           = array();
		$shopperGroupId = RedshopHelperUser::getShopperGroup();
		$result         = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);

		if (count($result) > 0)
		{
			$list = $result[0];
		}

		return $list;
	}

	/**
	 * Get discount price from product with check discount date.
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return  float
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 *
	 * @see  RedshopHelperDiscount::getDiscountPriceBaseDiscountDate()
	 */
	public function checkDiscountDate($productId)
	{
		return RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($productId);
	}

	/**
	 * Method for get property price with discount
	 *
	 * @param   string   $sectionId  Section ID
	 * @param   string   $quantity   Quantity
	 * @param   string   $section    Section
	 * @param   integer  $userId     User ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperProduct_Attribute::getPropertyPrice() instead.
	 */
	public function getPropertyPrice($sectionId = '', $quantity = '', $section = '', $userId = 0)
	{
		return RedshopHelperProduct_Attribute::getPropertyPrice($sectionId, $quantity, $section, $userId);
	}

	/**
	 * Method for get property or sub object
	 *
	 * @param   string  $sectionId  Section ID
	 * @param   string  $section    Section
	 *
	 * @return  object
	 *
	 */
	public function getProperty($sectionId, $section)
	{
		if ($section == 'property')
		{
			$properties = RedshopHelperProduct_Attribute::getAttributeProperties($sectionId);

			if (!empty($properties))
			{
				$properties[0]->product_price = $properties[0]->property_price;

				return $properties[0];
			}
		}
		elseif ($section == 'subproperty')
		{
			$properties = RedshopHelperProduct_Attribute::getAttributeSubProperties($sectionId);

			if (!empty($properties))
			{
				$properties[0]->product_price = $properties[0]->subattribute_color_price;

				return $properties[0];
			}
		}

		return null;
	}

	public function getWrapper($product_id, $wrapper_id = 0, $default = 1)
	{
		$usetoall = "";
		$and      = "";

		if ($wrapper_id != 0)
		{
			$and .= " AND wrapper_id='" . $wrapper_id . "' ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref "
			. "WHERE product_id = '" . (int) $product_id . "' ";
		$this->_db->setQuery($query);
		$cat = $this->_db->loadObjectList();

		for ($i = 0, $in = count($cat); $i < $in; $i++)
		{
			$usetoall .= " OR FIND_IN_SET(" . (int) $cat[$i]->category_id . ",category_id) ";
		}

		if ($default != 0)
		{
			$usetoall .= " OR wrapper_use_to_all = 1 ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "wrapper "
			. "WHERE published = 1 "
			. "AND (FIND_IN_SET(" . (int) $product_id . ",product_id) "
			. $usetoall . " )"
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getBreadcrumbPathway($category = array())
	{
		$pathway_items = array();

		for ($i = 0, $in = count($category); $i < $in; $i++)
		{
			$item            = new stdClass;
			$item->name      = $category[$i]['category_name'];
			$item->link      = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $category[$i]['category_id'] . '&Itemid=' . $category[$i]['catItemid']);
			$pathway_items[] = $item;
		}

		return $pathway_items;
	}

	public function getCategoryNavigationlist($category_id)
	{
		static $i = 0;
		static $category_list = array();

		$categorylist       = $this->getSection("category", $category_id);
		$category_parent_id = $this->getParentCategory($category_id);

		if (count($categorylist) > 0 && $categorylist->parent_id > 0)
		{
			$cItemid = RedshopHelperUtility::getCategoryItemid($categorylist->id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = $input  = JFactory::getApplication()->input->get('Itemid');
			}

			$category_list[$i]['category_id']   = $categorylist->id;
			$category_list[$i]['category_name'] = $categorylist->name;
			$category_list[$i]['catItemid']     = $tmpItemid;
		}

		if ($category_parent_id)
		{
			$i++;
			array_merge($category_list, $this->getCategoryNavigationlist($category_parent_id));
		}

		return $category_list;
	}

	/**
	 * Method for generate breadcrumb base on specific section
	 *
	 * @param   integer  $sectionId  Section ID
	 *
	 * @return  void
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 *
	 * @see RedshopHelperBreadcrumb::generate()
	 */
	public function generateBreadcrumb($sectionId = 0)
	{
		RedshopHelperBreadcrumb::generate($sectionId);
	}

	/**
	 * Get section
	 *
	 * @param   string  $section  Section name
	 * @param   int     $id       Section id
	 *
	 * @return mixed|null
	 */
	public function getSection($section = '', $id = 0)
	{
		// To avoid killing queries do not allow queries that get all the items
		if ($id != 0 && $section != '')
		{
			switch ($section)
			{
				case 'product':
					return $this->getProductById($id);
					break;
				case 'category':
					return RedshopHelperCategory::getCategoryById($id);
					break;
				default:
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_' . $section))
						->where($db->qn($section . '_id') . ' = ' . (int) $id);

					return  $db->setQuery($query)->loadObject();
			}
		}

		return null;
	}

	/**
	 * Get menu detail
	 *
	 * @param   string  $link  Link
	 *
	 * @return mixed|null
	 */
	public function getMenuDetail($link = '')
	{
		// Do not allow queries that load all the items
		if ($link != '')
		{
			return JFactory::getApplication()->getMenu()->getItems('link', $link, true);
		}

		return null;
	}

	/**
	 * Get Menu Information
	 *
	 * @param   int     $Itemid       Item id
	 * @param   int     $sectionId    Section id
	 * @param   string  $sectionName  Section name
	 * @param   string  $menuView     Menu view
	 * @param   bool    $isRedshop    Is redshop
	 *
	 * @return mixed|null
	 */
	public function getMenuInformation($Itemid = 0, $sectionId = 0, $sectionName = '', $menuView = '', $isRedshop = true)
	{
		$menu = JFactory::getApplication()->getMenu();
		$values = array();

		if ($menuView != "")
		{
			if ($items = explode('&', $menuView))
			{
				$values['view'] = $items[0];
				unset($items[0]);

				if (count($items) > 0)
				{
					foreach ($items as $item)
					{
						$value = explode('=', $item);
						$values[$value[0]] = $value[1];
					}
				}
			}
		}

		if ($Itemid != 0)
		{
			return $menu->getItem($Itemid);
		}

		if ($isRedshop)
		{
			$menuItems = RedshopHelperUtility::getRedshopMenuItems();
		}
		else
		{
			$menuItems = $menu->getMenu();
		}

		foreach ($menuItems as $oneMenuItem)
		{
			if (!RedshopHelperUtility::checkMenuQuery($oneMenuItem, $values))
			{
				break;
			}

			if ($sectionName != '')
			{
				if ($sectionId != 0)
				{
					if ($oneMenuItem->params->get($sectionName) != $sectionId)
					{
						break;
					}
				}
				else
				{
					if ($oneMenuItem->params->get($sectionName, false) !== false)
					{
						break;
					}
				}
			}

			return $oneMenuItem;
		}

		return null;
	}

	/**
	 * Get Parent Category
	 *
	 * @param int $id
	 *
	 * @return null
	 *
	 * @deprecated  Use please new function RedshopHelperCategory::getCategoryById
	 */
	public function getParentCategory($id = 0)
	{
		if ($result = RedshopHelperCategory::getCategoryById($id))
		{
			return $result->parent_id;
		}

		return null;
	}

	/**
	 * Get Category Product
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return string
	 */
	public function getCategoryProduct($productId = 0)
	{
		if ($result = $this->getProductById($productId))
		{
			if (!empty($result->categories))
			{
				return is_array($result->categories) ? implode(',', $result->categories) : $result->categories;
			}
			elseif (!empty($result->category_id))
			{
				return $result->category_id;
			}
		}

		return '';
	}

	public function getProductCategory($id = 0)
	{
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();
		$and = '';

		if ($shopper_group_manufactures != "")
		{
			// Sanitize groups
			$shopGroupsIds = explode(',', $shopper_group_manufactures);
			JArrayHelper::toInteger($shopGroupsIds);

			$and .= " AND p.manufacturer_id IN (" . implode(',', $shopGroupsIds) . ") ";
		}

		$query = "SELECT p.product_id FROM " . $this->_table_prefix . "product_category_xref pc"
			. " LEFT JOIN " . $this->_table_prefix . "product AS p ON pc.product_id=p.product_id "
			. " WHERE category_id = " . (int) $id . " "
			. $and;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectlist();

		return $res;
	}

	/**
	 * Method to check product is downloadable or else
	 *
	 * @param   integer  $productId  Product Id
	 * @param   boolean  $return     If yes, return object. False return number of download
	 *
	 * @return  object|integer
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 *
	 * @see  RedshopHelperProductDownload::checkDownload()
	 */
	public function checkProductDownload($productId, $return = false)
	{
		return RedshopHelperProductDownload::checkDownload($productId, $return);
	}

	public function getProductMediaName($product_id)
	{
		$query = 'SELECT media_name FROM ' . $this->_table_prefix . 'media '
			. 'WHERE media_section = "product" '
			. 'AND media_type="download" '
			. 'AND published=1 AND section_id = ' . (int) $product_id;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();

		return $res;
	}

	public function getGiftcardData($gid)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "giftcard "
			. "WHERE giftcard_id = " . (int) $gid;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return $res;
	}

	public function getValidityDate($period, $data)
	{
		$todate = mktime(0, 0, 0, date('m'), date('d') + $period, date('Y'));
		$config = Redconfiguration::getInstance();

		$todate   = $config->convertDateFormat($todate);
		$fromdate = $config->convertDateFormat(strtotime(date('d M Y')));

		$data = str_replace("{giftcard_validity_from}", JText::_('COM_REDSHOP_FROM') . " " . $fromdate, $data);
		$data = str_replace("{giftcard_validity_to}", JText::_('COM_REDSHOP_TO') . " " . $todate, $data);

		return $data;
	}

	public function getAccessoryPrice($product_id = 0, $accessory_price = 0, $accessory_main_price = 0, $vat = 0, $user_id = 0)
	{
		$return = array();
		$saved  = 0;

		if (empty($accessory_price))
		{
			$accessory_price = 0;
		}

		if (empty($accessory_main_price))
		{
			$accessory_main_price = 0;
		}

		/*
		 * $vat = 0 (add vat to accessory price)
		 * $vat = 1 (Do not add vat to accessory price)
		 */
		if ($vat != 1)
		{
			$accessory_price_vat      = 0;
			$accessory_main_price_vat = 0;

			// Get vat for accessory price
			if ($accessory_price > 0)
			{
				$accessory_price_vat = $this->getProductTax($product_id, $accessory_price, $user_id);
			}

			if ($accessory_main_price > 0)
			{
				$accessory_main_price_vat = $this->getProductTax($product_id, $accessory_main_price, $user_id);
			}

			// Add VAT to accessory prices
			$accessory_price += $accessory_price_vat;
			$accessory_main_price += $accessory_main_price_vat;
		}

		$saved = $accessory_main_price - $accessory_price;

		if ($saved < 0)
		{
			$saved = 0;
		}

		//accessory Price
		$return[0] = $accessory_price;

		//accessory main price
		$return[1] = $accessory_main_price;

		//accessory saving price
		$return[2] = $saved;

		return $return;
	}

	public function getuserfield($orderitemid = 0, $section_id = 12)
	{
		$redTemplate     = Redtemplate::getInstance();
		$order_functions = order_functions::getInstance();
		$live_site       = JURI::root();
		$resultArr       = array();

		$userfield = $order_functions->getOrderUserfieldData($orderitemid, $section_id);

		if (count($userfield) > 0)
		{
			$orderItem  = $order_functions->getOrderItemDetail(0, 0, $orderitemid);
			$product_id = $orderItem[0]->product_id;

			$productdetail   = $this->getProductById($product_id);
			$productTemplate = $redTemplate->getTemplate("product", $productdetail->product_template);

			$returnArr    = $this->getProductUserfieldFromTemplate($productTemplate[0]->template_desc);
			$userFieldTag = $returnArr[1];

			for ($i = 0, $in = count($userFieldTag); $i < $in; $i++)
			{
				for ($j = 0, $jn = count($userfield); $j < $jn; $j++)
				{
					if ($userfield[$j]->name == $userFieldTag[$i])
					{
						if ($userfield[$j]->type == 10)
						{
							$files    = explode(",", $userfield[$j]->data_txt);
							$data_txt = "";

							for ($f = 0, $fn = count($files); $f < $fn; $f++)
							{
								$u_link = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
								$data_txt .= "<a href='" . $u_link . "' target='_blank'>" . $files[$f] . "</a> ";
							}

							if (trim($data_txt) != "")
							{
								$resultArr[] = '<span class="userfield-label"">' . $userfield[$j]->title
								. ':</span><span class="userfield-value">' . stripslashes($data_txt) . '</span>';
							}
						}
						else
						{
							if (trim($userfield[$j]->data_txt) != "")
							{
								$resultArr[] = '<span class="userfield-label"">' . $userfield[$j]->title
								. '</span> : <span class="userfield-value">' . stripslashes($userfield[$j]->data_txt);
							}
						}
					}
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
		}

		return $resultstr;
	}

	public function getProductUserfieldFromTemplate($templatedata = "", $giftcard = 0)
	{
		$userfields      = array();
		$userfields_lbl  = array();
		$retArr          = array();
		$template_middle = "";

		if ($giftcard)
		{
			$template_start = explode("{if giftcard_userfield}", $templatedata);

			if (count($template_start) > 1)
			{
				$template_end = explode("{giftcard_userfield end if}", $template_start[1]);

				if (count($template_end) > 0)
				{
					$template_middle = $template_end[0];
				}
			}
		}
		else
		{
			$template_start = explode("{if product_userfield}", $templatedata);

			if (count($template_start) > 1)
			{
				$template_end = explode("{product_userfield end if}", $template_start[1]);

				if (count($template_end) > 0)
				{
					$template_middle = $template_end[0];
				}
			}
		}

		if ($template_middle != "")
		{
			$tmpArr = explode('}', $template_middle);

			for ($i = 0, $in = count($tmpArr); $i < $in; $i++)
			{
				$val   = strpbrk($tmpArr[$i], "{");
				$value = str_replace("{", "", $val);

				if ($value != "")
				{
					if (strpos($template_middle, '{' . $value . '_lbl}') !== false)
					{
						$userfields_lbl[] = $value . '_lbl';
						$userfields[]     = $value;
					}
					else
					{
						$userfields_lbl[] = '';
						$userfields[]     = $value;
					}
				}
			}
		}

		$tmp = array();

		for ($i = 0, $in = count($userfields); $i < $in; $i++)
		{
			if (!in_array($userfields[$i], $userfields_lbl))
			{
				$tmp[] = $userfields[$i];
			}
		}

		$userfields = $tmp;
		$retArr[0]  = $template_middle;
		$retArr[1]  = $userfields;

		return $retArr;
	}

	public function GetProdcutUserfield($id = 'NULL', $section_id = 12)
	{
		$extraField  = extraField::getInstance();
		$redTemplate = Redtemplate::getInstance();
		$cart        = $this->_session->get('cart');

		$row_data = $extraField->getSectionFieldList($section_id, 1, 0);

		if ($section_id == 12)
		{
			$product_id = $cart[$id]['product_id'];
			$productdetail = $this->getProductById($product_id);
			$temp_name = "product";
			$temp_id   = $productdetail->product_template;
			$giftcard  = 0;
		}
		else
		{
			$temp_name = "giftcard";
			$temp_id   = 0;
			$giftcard  = 1;
		}

		$productTemplate = $redTemplate->getTemplate($temp_name, $temp_id);

		$returnArr    = $this->getProductUserfieldFromTemplate($productTemplate[0]->template_desc, $giftcard);
		$userFieldTag = $returnArr[1];

		$resultArr = array();

		for ($i = 0, $in = count($userFieldTag); $i < $in; $i++)
		{
			for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
			{
				if (array_key_exists($userFieldTag[$i], $cart[$id]) && $cart[$id][$userFieldTag[$i]])
				{
					if ($row_data[$j]->name == $userFieldTag[$i])
					{
						$strtitle = '';

						if ($row_data[$j]->title)
						{
							$strtitle = '<span class="product-userfield-title">' . $row_data[$j]->title . ':</span>';
						}

						$resultArr[] = $strtitle . '<span class="product-userfield-value">' . $cart[$id][$userFieldTag[$i]] . '</span>';
					}
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
		}

		return $resultstr;
	}

	public function GetProdcutfield($id = 'NULL', $section_id = 1)
	{
		$extraField = extraField::getInstance();
		$cart       = $this->_session->get('cart');
		$product_id = $cart[$id]['product_id'];
		$row_data   = $extraField->getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = '<span class="product-field-title">' . $main_result->title . ':</span><span class="product-field-value">' . $main_result->data_txt . '</span>';
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public function GetProdcutfield_order($orderitemid = 'NULL', $section_id = 1)
	{
		$extraField      = extraField::getInstance();
		$order_functions = order_functions::getInstance();
		$orderItem       = $order_functions->getOrderItemDetail(0, 0, $orderitemid);

		$product_id = $orderItem[0]->product_id;

		$row_data = $extraField->getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = '<span class="product-order-title">' . $main_result->title . ':</span><span class="product-order-value">' . $main_result->data_txt . '</span>';
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public function insertProdcutUserfield($id = 'NULL', $cart = array(), $order_item_id = 0, $section_id = 12)
	{
		$db = JFactory::getDbo();

		$extraField = extraField::getInstance();
		$row_data   = $extraField->getSectionFieldList($section_id, 1);

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			if (array_key_exists($row_data[$i]->name, $cart[$id]) && $cart[$id][$row_data[$i]->name])
			{
				$user_fields = $cart[$id][$row_data[$i]->name];

				if (trim($user_fields) != '')
				{
					$sql = "INSERT INTO " . $this->_table_prefix . "fields_data "
						. "(fieldid,data_txt,itemid,section) "
						. "value (" . (int) $row_data[$i]->id . "," . $db->quote(addslashes($user_fields)) . ","
						. (int) $order_item_id . "," . $db->quote($section_id) . ")";
					$this->_db->setQuery($sql);
					$this->_db->execute();
				}
			}
		}

		return;
	}

	/**
	 * Get Product Attribute
	 *
	 * @param   int  $productId          Product id
	 * @param   int  $attributeSetId     Attribute set id
	 * @param   int  $attributeId        Attribute id
	 * @param   int  $published          Published attribute set
	 * @param   int  $attributeRequired  Attribute required
	 * @param   int  $notAttributeId     Not attribute id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.3  Use RedshopHelperProduct_Attribute::getProductAttribute() instead.
	 */
	public function getProductAttribute($productId = 0, $attributeSetId = 0, $attributeId = 0, $published = 0, $attributeRequired = 0,
	                                    $notAttributeId = 0)
	{
		return RedshopHelperProduct_Attribute::getProductAttribute(
			$productId, $attributeSetId, $attributeId, $published, $attributeRequired, $notAttributeId
		);
	}

	/**
	 * Get Attribute Property
	 *
	 * @param   int  $propertyId      Property id
	 * @param   int  $attributeId     Attribute id
	 * @param   int  $productId       Product id
	 * @param   int  $attributeSetId  Attribute set id
	 * @param   int  $required        Required
	 * @param   int  $notPropertyId   Not property id
	 *
	 * @return  mixed
	 *
	 * @deprecated   2.0.3  Use RedshopHelperProduct_Attribute::getAttributeProperties() instead.
	 */
	public function getAttibuteProperty($propertyId = 0, $attributeId = 0, $productId = 0, $attributeSetId = 0, $required = 0, $notPropertyId = 0)
	{
		return RedshopHelperProduct_Attribute::getAttributeProperties(
			$propertyId, $attributeId, $productId, $attributeSetId, $required, $notPropertyId
		);
	}

	public function getAttibutePropertyWithStock($property)
	{
		$stockroomhelper     = rsstockroomhelper::getInstance();
		$property_with_stock = array();

		for ($p = 0, $countProperty = count($property); $p < $countProperty; $p++)
		{
			if ($stockroomhelper->isStockExists($property[$p]->property_id, $section = "property"))
			{
				$property_with_stock[] = $property[$p];
			}
			else
			{
				if ($subPropertyAll = $this->getAttibuteSubProperty(0, $property[$p]->value))
				{
					foreach ($subPropertyAll as $subProperty)
					{
						if ($stockroomhelper->isStockExists($subProperty->subattribute_color_id, $section = "subproperty"))
						{
							$property_with_stock[] = $property[$p];
							break;
						}
					}
				}
			}
		}

		return $property_with_stock;
	}

	public function getAttibuteSubPropertyWithStock($subproperty)
	{
		$stockroomhelper        = rsstockroomhelper::getInstance();
		$subproperty_with_stock = array();

		for ($p = 0, $pn = count($subproperty); $p < $pn; $p++)
		{
			$isStock = $stockroomhelper->isStockExists($subproperty[$p]->subattribute_color_id, $section = "subproperty");

			if ($isStock)
			{
				$subproperty_with_stock[] = $subproperty[$p];
			}
		}

		return $subproperty_with_stock;
	}

	/**
	 * Method for get sub properties
	 *
	 * @param   int  $subproperty_id  Sub-Property ID
	 * @param   int  $property_id     Property ID
	 *
	 * @return  mixed                List of sub-properties data.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperProduct_Attribute::getAttributeSubProperties() instead
	 */
	public function getAttibuteSubProperty($subproperty_id = 0, $property_id = 0)
	{
		return RedshopHelperProduct_Attribute::getAttributeSubProperties($subproperty_id, $property_id);
	}

	public function getAttributeTemplate($data_add = "", $display = true)
	{
		$attribute_template      = array();
		$attribute_template_data = array();
		$redTemplate             = Redtemplate::getInstance();
		$displayname             = "attribute_template";
		$nodisplayname           = "attributewithcart_template";

		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			$displayname   = "attributewithcart_template";
			$nodisplayname = "attribute_template";
		}

		if (!$display)
		{
			$displayname = $nodisplayname;
		}

		if ($displayname == "attribute_template")
		{
			if (is_null($this->_attribute_template))
			{
				$this->_attribute_template = $attribute_template = RedshopHelperTemplate::getTemplate($displayname);
			}
			else
			{
				$attribute_template = $this->_attribute_template;
			}
		}
		else
		{
			if (is_null($this->_attributewithcart_template))
			{
				$this->_attributewithcart_template = $attribute_template = RedshopHelperTemplate::getTemplate($displayname);
			}
			else
			{
				$attribute_template = $this->_attributewithcart_template;
			}
		}

		if ($data_add != "")
		{
			for ($i = 0, $in = count($attribute_template); $i < $in; $i++)
			{
				if (strpos($data_add, "{" . $displayname . ":" . $attribute_template[$i]->template_name . "}") !== false)
				{
					$attribute_template_data = $attribute_template[$i];
				}
			}
		}

		return $attribute_template_data;
	}

	/**
	 * Method for get Product Accessories.
	 *
	 * @param   string  $accessory_id      ID of accessory.
	 * @param   string  $product_id        ID of product.
	 * @param   int     $child_product_id  ID of child product.
	 * @param   int     $cid               ID of category.
	 *
	 * @return  array                 List of accessories.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperAccessory::getProductAccessories() instead.
	 */
	public function getProductAccessory($accessory_id = '', $product_id = '', $child_product_id = 0, $cid = 0)
	{
		return RedshopHelperAccessory::getProductAccessories($accessory_id, $product_id, $child_product_id, $cid);
	}

	public function getAddtoCartTemplate($data_add = "")
	{
		$redTemplate = Redtemplate::getInstance();

		if (is_null($this->_cart_template))
		{
			$this->_cart_template = $cart_template = $redTemplate->getTemplate("add_to_cart");
		}
		else
		{
			$cart_template = $this->_cart_template;
		}

		$cart_template_data = array();

		if ($data_add != "")
		{
			for ($i = 0, $in = count($cart_template); $i < $in; $i++)
			{
				if (strpos($data_add, "{form_addtocart:" . $cart_template[$i]->template_name . "}") !== false)
				{
					$cart_template_data = $cart_template[$i];

					if (count($cart_template_data) > 0 && $cart_template_data->template_desc == "")
					{
						$cart_template_data->template_desc = '<div style="clear: left;"></div><div class="cart-wrapper"><div class="cart-quantity">{quantity_lbl}: {addtocart_quantity}</div><div class="cart-link">{addtocart_image_aslink}</div></div>';
					}

					break;
				}
			}
		}

		return $cart_template_data;
	}

	public function getAccessoryTemplate($data_add = "")
	{
		$redTemplate = Redtemplate::getInstance();

		if (is_null($this->_acc_template))
		{
			$this->_acc_template = $acc_template = $redTemplate->getTemplate("accessory_template");
		}
		else
		{
			$acc_template = $this->_acc_template;
		}

		$acc_template_data = array();

		if ($data_add != "")
		{
			for ($i = 0, $in = count($acc_template); $i < $in; $i++)
			{
				if (strpos($data_add, "{accessory_template:" . $acc_template[$i]->template_name . "}") !== false)
				{
					$acc_template_data = $acc_template[$i];

					if (count($acc_template_data) > 0 && $acc_template_data->template_desc == "")
					{
						$acc_template_data->template_desc = '<div class="accessory"><div class="accessory_info"><h2>Accessories</h2>Add accessories by clicking in the box.</div>{accessory_product_start}<div class="accessory_box"><div class="accessory_left"><div class="accessory_image">{accessory_image}</div></div><div class="accessory_right"><div class="accessory_title"><h3>{accessory_title}</h3></div><div class="accessory_desc">{accessory_short_desc}</div><div class="accessory_readmore">{accessory_readmore}</div><div class="accessory_add">{accessory_price} {accessory_add_chkbox}</div><div class="accessory_qua">{accessory_quantity_lbl} {accessory_quantity}</div></div><div style="clear: left">&nbsp;&nbsp;</div></div>{accessory_product_end}</div><div style="clear: left">&nbsp;&nbsp;</div>';
					}

					break;
				}
			}
		}

		return $acc_template_data;
	}

	public function getRelatedProductTemplate($data_add = "")
	{
		$redTemplate   = Redtemplate::getInstance();
		$template      = $redTemplate->getTemplate("related_product");
		$template_data = array();

		for ($i = 0, $in = count($template); $i < $in; $i++)
		{
			if (strpos($data_add, "{related_product:" . $template[$i]->template_name . "}") !== false)
			{
				$template_data = $template[$i];

				if (count($template_data) > 0 && $template_data->template_desc == "")
				{
					$template_data->template_desc = '<div class="related_product_wrapper"><h2>Related Products</h2>{related_product_start}<div class="related_product_inside"><div class="related_product_left"><div class="related_product_image"><div class="related_product_image_inside">{relproduct_image}</div></div></div><div class="related_product_right"><div class="related_product_name">{relproduct_name}</div><div class="related_product_price">{relproduct_price}</div><div class="related_product_desc">{relproduct_s_desc}</div><div class="related_product_readmore">{read_more}</div></div><div class="related_product_bottom"><div class="related_product_attr">{attribute_template:attributes}</div><div class="related_product_addtocart">{form_addtocart:add_to_cart2}</div></div></div>{related_product_end}</div>';
				}

				break;
			}
		}

		return $template_data;
	}

	public function getRelatedProduct($product_id = 0, $related_id = 0)
	{
		$helper          = redhelper::getInstance();
		$and             = "";
		$orderby         = "ORDER BY p.product_id ASC ";
		$orderby_related = "";

		if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD'))
		{
			$orderby         = "ORDER BY " . Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD');
			$orderby_related = "";
		}

		if ($product_id != 0)
		{
			// Sanitize ids
			$productIds = explode(',', $product_id);
			JArrayHelper::toInteger($productIds);

			if (RedshopHelperUtility::isRedProductFinder())
			{
				$q = "SELECT extrafield  FROM #__redproductfinder_types where type_select='Productfinder_datepicker'";
				$this->_db->setQuery($q);
				$finaltypetype_result = $this->_db->loadObject();
			}
			else
			{
				$finaltypetype_result = array();
			}

			$and .= "AND r.product_id IN (" . implode(',', $productIds) . ") ";

			if (Redshop::getConfig()->get('TWOWAY_RELATED_PRODUCT'))
			{
				if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == "r.ordering ASC" || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == "r.ordering DESC")
				{
					$orderby         = "";
					$orderby_related = "ORDER BY " . Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD');
				}

				$InProduct = "";

				$query = "SELECT * FROM " . $this->_table_prefix . "product_related AS r "
					. "WHERE r.product_id IN (" . implode(',', $productIds) . ") OR r.related_id IN (" . implode(',', $productIds) . ")" . $orderby_related . "";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				$relatedArr = array();

				for ($i = 0, $in = count($list); $i < $in; $i++)
				{
					if ($list[$i]->product_id == $product_id)
					{
						$relatedArr[] = $list[$i]->related_id;
					}
					else
					{
						$relatedArr[] = $list[$i]->product_id;
					}
				}

				if (empty($relatedArr))
				{
					return array();
				}

				// Sanitize ids
				JArrayHelper::toInteger($relatedArr);
				$relatedArr = array_unique($relatedArr);

				$query = "SELECT " . $product_id . " AS mainproduct_id,p.* "
					. "FROM " . $this->_table_prefix . "product AS p "
					. "WHERE p.published = 1 ";
				$query .= ' AND p.product_id IN (' . implode(", ", $relatedArr) . ') ';
				$query .= $orderby;

				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				return $list;
			}
		}

		if ($related_id != 0)
		{
			$and .= "AND r.related_id = " . (int) $related_id . " ";
		}

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC' || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$add_e = ",e.*";
		}
		else
		{
			$add_e = " ";
		}

		$query = "SELECT r.product_id AS mainproduct_id,p.* " . $add_e . " "
			. "FROM " . $this->_table_prefix . "product_related AS r "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = r.related_id ";

		if (!empty($finaltypetype_result) && !empty($finaltypetype_result->extrafield)
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC'
				|| Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$query .= " LEFT JOIN " . $this->_table_prefix . "fields_data  AS e ON p.product_id = e.itemid ";
		}

		$query .= " WHERE p.published = 1 ";

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC' || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$query .= " AND e.fieldid = " . (int) $finaltypetype_result->extrafield . " AND e.section=17 ";
		}

		$query .= " $and GROUP BY r.related_id ";

		if ((Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC'
			|| Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC')
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) ASC";
			}
			else
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) DESC";
			}

			$query .= " ORDER BY " . $s;
		}
		else
		{
			$query .= " $orderby ";
		}

		$this->_db->setQuery($query);

		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function makeTotalPriceByOprand($price = 0, $oprandArr = array(), $priceArr = array())
	{
		$setEqual = true;

		for ($i = 0, $in = count($oprandArr); $i < $in; $i++)
		{
			$oprand   = $oprandArr[$i];
			$subprice = $priceArr[$i];

			if ($oprand == "-")
			{
				$price -= $subprice;
			}
			elseif ($oprand == "+")
			{
				$price += $subprice;
			}
			elseif ($oprand == "*")
			{
				$price *= $subprice;
			}
			elseif ($oprand == "/")
			{
				$price /= $subprice;
			}
			elseif ($oprand == "=")
			{
				$price    = $subprice;
				$setEqual = false;
				break;
			}
		}

		$retArr    = array();
		$retArr[0] = $setEqual;
		$retArr[1] = $price;

		return $retArr;
	}

	public function getProductOnSaleComment($product = array(), $data_add = "")
	{
		$redconfig = Redconfiguration::getInstance();

		if (strpos($data_add, "{if product_on_sale}") && strpos($data_add, "{product_on_sale end if}") !== false)
		{
			if ($product->product_on_sale == 1 && (($product->discount_stratdate == 0 && $product->discount_enddate == 0) || ($product->discount_stratdate <= time() && $product->discount_enddate >= time())))
			{
				$data_add = str_replace("{discount_start_date}", $redconfig->convertDateFormat($product->discount_stratdate), $data_add);
				$data_add = str_replace("{discount_end_date}", $redconfig->convertDateFormat($product->discount_enddate), $data_add);
				$data_add = str_replace("{if product_on_sale}", '', $data_add);
				$data_add = str_replace("{product_on_sale end if}", '', $data_add);
			}
			else
			{
				$template_pd_sdata = strstr($data_add, '{if product_on_sale}', true);
				$template_pd_edata = substr(strstr($data_add, '{product_on_sale end if}'), 24);
				$data_add = $template_pd_sdata . $template_pd_edata;
			}

			$data_add = str_replace("{discount_start_date}", '', $data_add);
			$data_add = str_replace("{discount_end_date}", '', $data_add);
		}

		return $data_add;
	}

	public function getProductNotForSaleComment($product = array(), $data_add = "", $attributes = array(), $is_relatedproduct = 0, $seoTemplate = "")
	{
		$showPrice = true;

		if ($product->expired || $product->not_for_sale == 1)
		{
			$showPrice = false;
		}

		if ($showPrice)
		{
			// Product show price without formatted
			$applytax = $this->getApplyVatOrNot($data_add);

			if ($applytax)
			{
				$GLOBAL ['without_vat'] = false;
			}
			else
			{
				$GLOBAL ['without_vat'] = true;
			}

			$data_add = RedshopHelperProductPrice::getShowPrice($product->product_id, $data_add, $seoTemplate, 0, $is_relatedproduct, $attributes);
		}
		else
		{
			$relPrefix = ($is_relatedproduct) ? 'rel' : '';
			$data_add  = str_replace("{" . $relPrefix . "product_price_lbl}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_novat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "price_excluding_vat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_table}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_old_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_saving}", "", $data_add);
		}

		return $data_add;
	}

	public function getSpecialProductComment($product = array(), $data_add = "")
	{
		if (strpos($data_add, "{if product_special}") !== false && strpos($data_add, "{product_special end if}") !== false)
		{
			if ($product->product_special == 0)
			{
				$template_pd_sdata = explode('{if product_special}', $data_add);
				$template_pd_edata = explode('{product_special end if}', $template_pd_sdata [1]);
				$data_add          = $template_pd_sdata[0] . $template_pd_edata[1];
			}

			$data_add = str_replace("{if product_special}", '', $data_add);
			$data_add = str_replace("{product_special end if}", '', $data_add);
		}

		return $data_add;
	}

	public function getAjaxDetailboxTemplate($product = array())
	{
		if (!Redshop::getConfig()->get('AJAX_CART_BOX'))
		{
			return array();
		}

		$redTemplate     = Redtemplate::getInstance();
		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		if (!$this->_ajaxdetail_templatedata)
		{
			$ajaxdetail_templatedata         = array();
			$default_ajaxdetail_templatedata = array();
			$ajaxdetail_template             = $redTemplate->getTemplate("ajax_cart_detail_box");

			for ($i = 0, $in = count($ajaxdetail_template); $i < $in; $i++)
			{
				if (strpos($producttemplate[0]->template_desc, "{ajaxdetail_template:" . $ajaxdetail_template[$i]->template_name . "}") !== false)
				{
					$ajaxdetail_templatedata = $ajaxdetail_template[$i];
					break;
				}

				if (Redshop::getConfig()->get('DEFAULT_AJAX_DETAILBOX_TEMPLATE') == $ajaxdetail_template[$i]->template_id)
				{
					$default_ajaxdetail_templatedata = $ajaxdetail_template[$i];
				}
			}

			if (empty($ajaxdetail_templatedata) && count($default_ajaxdetail_templatedata) > 0)
			{
				$ajaxdetail_templatedata = $default_ajaxdetail_templatedata;
			}

			if (count($ajaxdetail_templatedata) > 0 && $ajaxdetail_templatedata->template_desc == "")
			{
				$ajaxdetail_templatedata->template_desc = '<div id="ajax-cart"><div id="ajax-cart-attr">{attribute_template:attributes}</div><div id="ajax-cart-access">{accessory_template:accessory}</div>{if product_userfield}<div id="ajax-cart-user">{userfield-test}</div>{product_userfield end if}<div id="ajax-cart-label">{form_addtocart:add_to_cart2}</div></div>';
			}

			$this->_ajaxdetail_templatedata = $ajaxdetail_templatedata;
		}

		return $this->_ajaxdetail_templatedata;
	}

	public function replaceAccessoryData($product_id = 0, $relproduct_id = 0, $accessory = array(), $data_add, $isChilds = false, $selectAcc = array())
	{
		$user_id    = 0;
		$url        = JURI::base();
		$redconfig  = Redconfiguration::getInstance();
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$input      = JFactory::getApplication()->input;
		$viewacc    = $input->get('viewacc', 1);
		$layout     = $input->get('layout');
		$Itemid     = $input->get('Itemid');

		$isAjax = 0;
		$prefix = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax = 1;
			$prefix = "ajax_";
		}

		if ($relproduct_id != 0)
		{
			$product_id = $relproduct_id;
		}

		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectAtt            = array();

		if (count($selectAcc) > 0)
		{
			$selectedAccessory    = $selectAcc[0];
			$selectedAccessoryQua = $selectAcc[3];
			$selectAtt            = array($selectAcc[1], $selectAcc[2]);
		}

		$product            = $this->getProductById($product_id);
		$accessory_template = $this->getAccessoryTemplate($data_add);

		if (count($accessory_template) <= 0)
		{
			return $data_add;
		}

		$accessory_template_data = $accessory_template->template_desc;

		$totalAccessory = count($accessory);

		$attribute_template = $this->getAttributeTemplate($accessory_template_data);

		if ($totalAccessory > 0)
		{
			$acctemplate_data = $accessory_template_data;

			if (strpos($acctemplate_data, "{if accessory_main}") !== false && strpos($acctemplate_data, "{accessory_main end if}") !== false)
			{
				$acctemplate_data = explode('{if accessory_main}', $acctemplate_data);
				$accessory_start  = $acctemplate_data[0];
				$acctemplate_data = explode('{accessory_main end if}', $acctemplate_data[1]);
				$accessory_end    = $acctemplate_data[1];
				$accessory_middle = $acctemplate_data[0];

				if (strpos($accessory_middle, "{accessory_main_short_desc}") !== false)
				{
					$accessory_main_short_description = $redconfig->maxchar(
						$product->product_s_desc,
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
					);
					$accessory_middle = str_replace("{accessory_main_short_desc}",
						$accessory_main_short_description,
						$accessory_middle);
				}

				if (strpos($accessory_middle, "{accessory_main_title}") !== false)
				{
					$accessory_main_product_name = $redconfig->maxchar(
						$product->product_name,
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
					);
					$accessory_middle            = str_replace("{accessory_main_title}", $accessory_main_product_name, $accessory_middle);
				}

				$accessory_productdetail = "<a href='#' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
				$accessory_middle        = str_replace("{accessory_main_readmore}", $accessory_productdetail, $accessory_middle);
				$accessory_main_image    = $product->product_full_image;
				$accessorymainimage      = '';

				if (strpos($accessory_middle, "{accessory_main_image_3}") !== false)
				{
					$aimg_tag = '{accessory_main_image_3}';
					$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
					$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
				}
				elseif (strpos($accessory_middle, "{accessory_main_image_2}") !== false)
				{
					$aimg_tag = '{accessory_main_image_2}';
					$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
					$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
				}
				elseif (strpos($accessory_middle, "{accessory_main_image_1}") !== false)
				{
					$aimg_tag = '{accessory_main_image_1}';
					$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
					$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
				}
				else
				{
					$aimg_tag = '{accessory_main_image}';
					$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
					$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
				}

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_main_image))
				{
					$thumbUrl = RedShopHelperImages::getImagePath(
						$accessory_main_image,
						'',
						'thumb',
						'product',
						$aw_thumb,
						$ah_thumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

					if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
					{
						$accessorymainimage = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
							. "product/" . $accessory_main_image
							. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
							. "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
					}
					else
					{
						$accessorymainimage = "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' />";
					}
				}

				$accessory_middle = str_replace($aimg_tag, $accessorymainimage, $accessory_middle);
				$ProductPriceArr  = array();

				if (strpos($accessory_middle, "{accessory_mainproduct_price}") !== false || strpos($data_add, "{selected_accessory_price}") !== false)
				{
					$ProductPriceArr = $this->getProductNetPrice($product_id, $user_id, 1, $data_add);
				}

				if (strpos($accessory_middle, "{accessory_mainproduct_price}") !== false)
				{
					$product_price = '';

					if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
					{
						$accessory_mainproduct_price = $this->getPriceReplacement($ProductPriceArr['product_price']);
						$accessory_middle            = str_replace(
							"{accessory_mainproduct_price}",
							$accessory_mainproduct_price,
							$accessory_middle);
					}
				}

				$accessory_middle = $this->replaceProductInStock($product->product_id, $accessory_middle);
				$acctemplate_data = $accessory_start . $accessory_middle . $accessory_end;
			}

			$accessory_div = '';

			if (strpos($acctemplate_data, "{accessory_product_start}") !== false && strpos($acctemplate_data, "{accessory_product_end}") !== false)
			{
				$acctemplate_data    = explode('{accessory_product_start}', $acctemplate_data);
				$accessory_div_start = $acctemplate_data [0];
				$acctemplate_data    = explode('{accessory_product_end}', $acctemplate_data [1]);
				$accessory_div_end   = $acctemplate_data[1];

				$accessory_div_middle = $acctemplate_data[0];

				for ($a = 0, $an = count($accessory); $a < $an; $a++)
				{
					$ac_id    = $accessory [$a]->child_product_id;
					$c_p_data = $this->getProductById($ac_id);

					$commonid = $prefix . $product_id . '_' . $accessory [$a]->accessory_id;
					$accessory_div .= "<div id='divaccstatus" . $commonid . "' class='accessorystatus'>" . $accessory_div_middle . "</div>";

					$accessory_product_name = $redconfig->maxchar(
						$accessory [$a]->product_name,
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
						Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
					);
					$accessory_div          = str_replace("{accessory_title}", $accessory_product_name, $accessory_div);

					$accessory_div = str_replace("{product_number}", $accessory [$a]->product_number, $accessory_div);

					$accessory_image = $accessory [$a]->product_full_image;
					$accessoryimage  = '';

					if (strpos($accessory_div, "{accessory_image_3}") !== false)
					{
						$aimg_tag = '{accessory_image_3}';
						$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
						$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
					}
					elseif (strpos($accessory_div, "{accessory_image_2}") !== false)
					{
						$aimg_tag = '{accessory_image_2}';
						$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
						$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
					}
					elseif (strpos($accessory_div, "{accessory_image_1}") !== false)
					{
						$aimg_tag = '{accessory_image_1}';
						$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
						$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
					}
					else
					{
						$aimg_tag = '{accessory_image}';
						$ah_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
						$aw_thumb = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
					}

					$acc_prod_link      = JRoute::_('index.php?option=com_redshop&view=product&pid='
						. $ac_id . '&Itemid=' . $Itemid);
					$hidden_thumb_image = "<input type='hidden' name='acc_main_imgwidth' id='acc_main_imgwidth' value='"
						. $aw_thumb . "'><input type='hidden' name='acc_main_imgheight' id='acc_main_imgheight' value='"
						. $ah_thumb . "'>";

					// Trigger to change product image.
					$dispatcher->trigger('changeProductImage', array(&$accessoryimage, $accessory [$a], $acc_prod_link, $aw_thumb, $ah_thumb, Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX'), ''));

					if (empty($accessoryimage))
					{
						if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
						{
							if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_image))
							{
								$thumbUrl = RedShopHelperImages::getImagePath(
									$accessory_image,
									'',
									'thumb',
									'product',
									$aw_thumb,
									$ah_thumb,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
								$accessoryimage = "<a id='a_main_image" . $accessory [$a]->accessory_id
									. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessory_image
									. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
									. "<img id='main_image" . $accessory [$a]->accessory_id . "' class='redAttributeImage' src='" . $thumbUrl . "' />"
									. "</a>";
							}
							else
							{
								$thumbUrl = RedShopHelperImages::getImagePath(
									'noimage.jpg',
									'',
									'thumb',
									'',
									$aw_thumb,
									$ah_thumb,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
								$accessoryimage = "<a id='a_main_image" . $accessory [$a]->accessory_id
									. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
									. "noimage.jpg' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
									. "<img id='main_image" . $accessory [$a]->accessory_id . "' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
							}
						}
						else
						{
							if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_image))
							{
								$thumbUrl = RedShopHelperImages::getImagePath(
									$accessory_image,
									'',
									'thumb',
									'product',
									$aw_thumb,
									$ah_thumb,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
								$accessoryimage = "<a href='$acc_prod_link'><img id='main_image" . $accessory [$a]->accessory_id
									. "' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
							}
							else
							{
								$thumbUrl = RedShopHelperImages::getImagePath(
									'noimage.jpg',
									'',
									'thumb',
									'',
									$aw_thumb,
									$ah_thumb,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
								$accessoryimage = "<a href='$acc_prod_link'><img id='main_image" . $accessory [$a]->accessory_id
									. "' class='redAttributeImage' src='" . $thumbUrl. "' /></a>";
							}
						}
					}

					$accessory_div               = str_replace($aimg_tag, $accessoryimage . $hidden_thumb_image, $accessory_div);
					$accessory_short_description = $redconfig->maxchar($accessory [$a]->product_s_desc, Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX'));
					$accessory_div               = str_replace("{accessory_short_desc}", $accessory_short_description, $accessory_div);

					// Add manufacturer
					if (strpos($accessory_div, "{manufacturer_name}") !== false || strpos($accessory_div, "{manufacturer_link}") !== false)
					{
						$manufacturer = $this->getSection("manufacturer", $accessory [$a]->manufacturer_id);

						if (count($manufacturer) > 0)
						{
							$man_url          = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid='
								. $related_product[$r]->manufacturer_id . '&Itemid=' . $pItemid);
							$manufacturerLink = "<a class='btn btn-primary' href='" . $man_url . "'>" . JText::_("VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
							$accessory_div    = str_replace("{manufacturer_name}", $manufacturer->manufacturer_name, $accessory_div);
							$accessory_div    = str_replace("{manufacturer_link}", $manufacturerLink, $accessory_div);
						}
						else
						{
							$accessory_div = str_replace("{manufacturer_name}", '', $accessory_div);
							$accessory_div = str_replace("{manufacturer_link}", '', $accessory_div);
						}
					}

					// Get accessory final price with VAT rules
					$accessoryprice_withoutvat = $this->getAccessoryPrice(
						$product_id,
						$accessory[$a]->newaccessory_price,
						$accessory[$a]->accessory_main_price,
						1
					);

					if (strpos($accessory_div, "{without_vat}") === false)
					{
						$accessorypricelist = $this->getAccessoryPrice(
							$product_id,
							$accessory[$a]->newaccessory_price,
							$accessory[$a]->accessory_main_price
						);
					}
					else
					{
						$accessorypricelist = $accessoryprice_withoutvat;
					}

					$accessory_price_withoutvat = $accessoryprice_withoutvat[0];

					$accessory_price       = $accessorypricelist[0];
					$accessory_main_price  = $accessorypricelist[1];
					$saved_accessory_price = $accessorypricelist[2];

					// Get Formatted prices
					$saved_accessory_price = $this->getProductFormattedPrice($saved_accessory_price);
					$accessory_main_price  = $this->getProductFormattedPrice($accessory_main_price);
					$accessory_price_show  = $this->getProductFormattedPrice($accessory_price);

					if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
					{
						$accessory_div = str_replace("{accessory_price}", $accessory_price_show, $accessory_div);
						$accessory_div = str_replace("{accessory_main_price}", $accessory_main_price, $accessory_div);
						$accessory_div = str_replace("{accessory_price_saving}", $saved_accessory_price, $accessory_div);
					}
					else
					{
						$accessory_div = str_replace("{accessory_price}", '', $accessory_div);
						$accessory_div = str_replace("{accessory_main_price}", '', $accessory_div);
						$accessory_div = str_replace("{accessory_price_saving}", '', $accessory_div);
					}

					$readmorelink            = JRoute::_('index.php?option=com_redshop&view=product&pid='
						. $ac_id . '&Itemid=' . $Itemid);
					$accessory_productdetail = "<a href='" . $readmorelink . "' title='" . $accessory [$a]->product_name
						. "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
					$accessory_div = str_replace("{accessory_readmore}", $accessory_productdetail, $accessory_div);
					$accessory_div = str_replace("{accessory_readmore_link}", $readmorelink, $accessory_div);

					// Accessory attribute  Start
					$attributes_set = array();

					if ($c_p_data->attribute_set_id > 0)
					{
						$attributes_set = $this->getProductAttribute(0, $c_p_data->attribute_set_id);
					}

					$attributes = $this->getProductAttribute($ac_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);

					$accessory_div = $this->replaceAttributeData(
						$product_id,
						$accessory [$a]->accessory_id,
						$relproduct_id,
						$attributes,
						$accessory_div,
						$attribute_template,
						$isChilds,
						$selectAtt
					);
					$accessory_div = $this->replaceProductInStock($accessory [$a]->child_product_id, $accessory_div);

					// Accessory attribute  End

					$accchecked = "";

					if (
						(
							$isAjax == 1
							&& in_array($accessory [$a]->accessory_id, $selectedAccessory)
						)
						|| ($isAjax == 0
							&& $accessory [$a]->setdefault_selected))
					{
						$accchecked = "checked";
					}

					$accessory_checkbox = "<input type='checkbox' name='accessory_id_" . $prefix . $product_id
						. "[]' onClick='calculateTotalPrice(\"" . $product_id . "\",\"" . $relproduct_id . "\");' totalattributs='"
						. count($attributes) . "' accessoryprice='" . $accessory_price
						. "' accessorywithoutvatprice='" . $accessory_price_withoutvat . "' id='accessory_id_"
						. $commonid . "' value='" . $accessory [$a]->accessory_id . "' " . $accchecked . " />";
					$accessory_div      = str_replace("{accessory_add_chkbox}", $accessory_checkbox, $accessory_div);
					$accessory_div      = str_replace(
						"{accessory_add_chkbox_lbl}",
						JText::_('COM_REDSHOP_ACCESSORY_ADD_CHKBOX_LBL') . '&nbsp;' . $accessory [$a]->product_name,
						$accessory_div);

					if (strpos($accessory_div, "{accessory_quantity}") !== false)
					{
						if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
						{
							$key                = array_search($accessory [$a]->accessory_id, $selectedAccessory);
							$accqua             = ($accchecked != "" && isset($selectedAccessoryQua[$key]) && $selectedAccessoryQua[$key]) ? $selectedAccessoryQua[$key] : 1;
							$accessory_quantity = "<input type='text' name='accquantity_" . $prefix . $product_id . "[]' id='accquantity_" . $commonid . "' value='" . $accqua . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' onchange='validateInputNumber(this.id);'>";
							$accessory_div      = str_replace("{accessory_quantity}", $accessory_quantity, $accessory_div);
							$accessory_div      = str_replace("{accessory_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $accessory_div);
						}
						else
						{
							$accessory_div = str_replace("{accessory_quantity}", "", $accessory_div);
							$accessory_div = str_replace("{accessory_quantity_lbl}", "", $accessory_div);
						}
					}

					$fieldArray = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT, 1, 1);

					if (count($fieldArray) > 0)
					{
						for ($i = 0, $in = count($fieldArray); $i < $in; $i++)
						{
							$fieldValueArray = RedshopHelperExtrafields::getSectionFieldDataList($fieldArray[$i]->id, 1, $accessory [$a]->child_product_id);

							if ($fieldValueArray && $fieldValueArray->data_txt != ""
								&& $fieldArray[$i]->show_in_front == 1
								&& $fieldArray[$i]->published == 1 && $giftcard == 0)
							{
								$accessory_div = str_replace('{' . $fieldArray[$i]->name . '}', $fieldValueArray->data_txt, $accessory_div);
								$accessory_div = str_replace('{' . $fieldArray[$i]->name . '_lbl}', $fieldArray[$i]->title, $accessory_div);
							}
							else
							{
								$accessory_div = str_replace('{' . $fieldArray[$i]->name . '}', "", $accessory_div);
								$accessory_div = str_replace('{' . $fieldArray[$i]->name . '_lbl}', "", $accessory_div);
							}
						}
					}
				}

				$accessory_div = $accessory_div_start . $accessory_div . $accessory_div_end;
			}
			// Attribute ajax change
			if ($viewacc == 1)
			{
				$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", $accessory_div, $data_add);
			}
			else
			{
				if (Redshop::getConfig()->get('AJAX_CART_BOX') == 0)
				{
					$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", $accessory_div, $data_add);
				}
				else
				{
					$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", "", $data_add);
				}
			}

			if (strpos($data_add, "{selected_accessory_price}") !== false && $isAjax == 0)
			{
				$selected_accessory_price = $this->getPriceReplacement($ProductPriceArr['product_price']);
				$data_add                 = str_replace(
					"{selected_accessory_price}",
					"<div id='rs_selected_accessory_price' class='rs_selected_accessory_price'>" . $selected_accessory_price . "</div>",
					$data_add);
			}
			else
			{
				$data_add = str_replace("{selected_accessory_price}", "", $data_add);
			}

			// New tags replacement for accessory template section
			$data_add = RedshopTagsReplacer::_('accessory', $data_add, array('accessory' => $accessory));
			$data_add = str_replace("{accessory_product_start}", "", $data_add);
			$data_add = str_replace("{accessory_product_end}", "", $data_add);
		}
		else
		{
			$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", "", $data_add);
		}

		return $data_add;
	}

	/**
	 * Method for replace attribute data with allow add to cart in template.
	 *
	 * @param   int     $productId          Product ID
	 * @param   int     $accessoryId        Accessory ID
	 * @param   int     $relatedProductId   Related product ID
	 * @param   array   $attributes         List of attribute data.
	 * @param   string  $templateContent    HTML content of template.
	 * @param   object  $attributeTemplate  List of attribute templates.
	 * @param   bool    $isChild            Is child?
	 * @param   bool    $onlySelected       True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string                      HTML content with replaced data.
	 *
	 * @since   1.6.1
	 *
	 * @deprecated   2.0.3     Use RedshopHelperAttribute::replaceAttributeWithCartData() instead
	 */
	public function replaceAttributewithCartData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(),
	                                             $templateContent, $attributeTemplate = null, $isChild = false, $onlySelected = false)
	{
		return RedshopHelperAttribute::replaceAttributeWithCartData($productId, $accessoryId, $relatedProductId, $attributes, $templateContent,
			$attributeTemplate, $isChild, $onlySelected);
	}

	public function get_hidden_attribute_cartimage($product_id, $property_id, $subproperty_id)
	{
		$url      = JURI::base();
		$attrbimg = "";

		if ($property_id > 0)
		{
			$property = $this->getAttibuteProperty($property_id);

			//Display attribute image in cart
			if (count($property) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[0]->property_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $property[0]->property_image;
			}
		}

		if ($subproperty_id > 0)
		{
			$subproperty = $this->getAttibuteSubProperty($subproperty_id);

			if (count($subproperty) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[0]->subattribute_color_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "subcolor/" . $subproperty[0]->subattribute_color_image;
			}
		}

		return $attrbimg;
	}

	/**
	 * Method for replace attribute data in template.
	 *
	 * @param   int     $productId           Product ID
	 * @param   int     $accessoryId         Accessory ID
	 * @param   int     $relatedProductId    Related product ID
	 * @param   array   $attributes          List of attribute data.
	 * @param   string  $templateContent     HTML content of template.
	 * @param   object  $attributeTemplate   List of attribute templates.
	 * @param   bool    $isChild             Is child?
	 * @param   array   $selectedAttributes  Preselected attribute list.
	 * @param   int     $displayIndCart      Display in cart?
	 * @param   bool    $onlySelected        True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string
	 *
	 * @since  1.6.1
	 *
	 * @deprecated  2.0.3  Use RedshopHelperAttribute::replaceAttributeData() instead.
	 */
	public function replaceAttributeData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(), $templateContent,
	                                     $attributeTemplate = null, $isChild = false, $selectedAttributes = array(), $displayIndCart = 1,$onlySelected = false)
	{
		return RedshopHelperAttribute::replaceAttributeData($productId, $accessoryId, $relatedProductId, $attributes, $templateContent,
			$attributeTemplate, $isChild, $selectedAttributes, $displayIndCart, $onlySelected);
	}

	public function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $relatedprd_id = 0, $attribute_id = 0, $property_id = 0, $subatthtml = "", $layout = "", $selectSubproperty = array())
	{
		$redTemplate     = Redtemplate::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$attribute_table = "";
		$subproperty     = array();

		JHtml::script('com_redshop/thumbscroller.js', false, true);
		$chkvatArr = $this->_session->get('chkvat');
		$chktag    = $chkvatArr['chkvat'];

		$preprefix = "";
		$isAjax    = 0;

		if ($layout == "viewajaxdetail")
		{
			$preprefix = "ajax_";
			$isAjax    = 1;
		}

		if ($property_id != 0 && $attribute_id != 0)
		{
			$attributes      = $this->getProductAttribute(0, 0, $attribute_id);
			$attributes      = $attributes[0];
			$subproperty_all = $this->getAttibuteSubProperty(0, $property_id);
			// filter Out of stock data
			if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get('USE_STOCKROOM'))
			{
				$subproperty = $this->getAttibuteSubPropertyWithStock($subproperty_all);
			}
			else
			{
				$subproperty = $subproperty_all;
			}

			// Get stockroom and pre-order stockroom data.
			$subPropertyIds = array_map(
				function ($item)
				{
					return $item->value;
				},
				$subproperty
			);
			$stockrooms = RedshopHelperStockroom::getMultiSectionsStock($subPropertyIds, 'subproperty');
			$preOrderStockrooms = RedshopHelperStockroom::getMultiSectionsPreOrderStock($subPropertyIds, 'subproperty');

			foreach ($subproperty as $i => $item)
			{
				$subproperty[$i]->stock = isset($stockrooms[$item->value]) ? (int) $stockrooms[$item->value] : 0;
				$subproperty[$i]->preorder_stock = isset($preOrderStockrooms[$item->value]) ? (int) $preOrderStockrooms[$item->value] : 0;
			}
		}

		$stock = 0;

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relatedprd_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}
		else
		{
			$prefix = $preprefix . "prd_";
		}

		if ($relatedprd_id != 0)
		{
			$product_id = $relatedprd_id;
		}

		$product         = $this->getProductById($product_id);
		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		if (strpos($producttemplate[0]->template_desc, "{more_images_3}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_2}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_1}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}
		else
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}

		if ($subatthtml != "")
		{
			// Load plugin group
			JPluginHelper::importPlugin('redshop_product');

			if (count($subproperty) > 0)
			{
				$attribute_table = $subatthtml;
				$attribute_table .= '<span id="subprop_lbl" style="display:none;">'
					. JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . '</span>';
				$commonid            = $prefix . $product_id . '_' . $accessory_id . '_' . $attribute_id . '_'
					. $property_id;
				$subpropertyid       = 'subproperty_id_' . $commonid;
				$selectedsubproperty = 0;
				$imgAdded            = 0;

				$subproperty_woscrollerdiv = "";

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$subproperty_woscrollerdiv .= "<div class='subproperty_main_outer' id='subproperty_main_outer'>";
				}

				$subprop_Arry = array();

				for ($i = 0, $in = count($subproperty); $i < $in; $i++)
				{
					if (count($selectSubproperty) > 0)
					{
						if (in_array($subproperty[$i]->value, $selectSubproperty))
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}
					else
					{
						if ($subproperty[$i]->setdefault_selected)
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}

					if ($subproperty[$i]->subattribute_color_image)
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[$i]->subattribute_color_image))
						{
							$borderstyle    = ($selectedsubproperty == $subproperty[$i]->value) ? " 1px solid " : "";
							$thumbUrl       = RedShopHelperImages::getImagePath(
								$subproperty[$i]->subattribute_color_image,
								'',
								'thumb',
								'subcolor',
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'),
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							$subprop_Arry[] = $thumbUrl;

							$subproperty_woscrollerdiv .= "<div id='" . $subpropertyid . "_subpropimg_"
								. $subproperty[$i]->value . "' class='subproperty_image_inner'><a onclick='setSubpropImage(\""
								. $product_id . "\",\"" . $subpropertyid . "\",\"" . $subproperty[$i]->value
								. "\");calculateTotalPrice(\"" . $product_id . "\",\"" . $relatedprd_id
								. "\");displayAdditionalImage(\"" . $product_id . "\",\"" . $accessory_id . "\",\""
								. $relatedprd_id . "\",\"" . $property_id . "\",\"" . $subproperty[$i]->value
								. "\");'><img class='redAttributeImage'  src='" . $thumbUrl . "'></a></div>";

							$imgAdded++;
						}
					}

					$attributes_subproperty_vat_show   = 0;
					$attributes_subproperty_withoutvat = 0;
					$attributes_subproperty_oldprice   = 0;

					if ($subproperty [$i]->subattribute_color_price > 0)
					{
						$attributes_subproperty_oldprice = $subproperty [$i]->subattribute_color_price;

						$pricelist = $this->getPropertyPrice($subproperty[$i]->value, 1, 'subproperty');

						if (count($pricelist) > 0)
						{
							$subproperty[$i]->subattribute_color_price = $pricelist->product_price;
						}

						$attributes_subproperty_withoutvat = $subproperty [$i]->subattribute_color_price;

						if ($chktag)
						{
							$attributes_subproperty_vat_show = $this->getProducttax($product_id, $subproperty [$i]->subattribute_color_price);

							$attributes_subproperty_oldprice_vat = $this->getProducttax($product_id, $attributes_subproperty_oldprice);
						}

						$attributes_subproperty_vat_show += $subproperty [$i]->subattribute_color_price;
						$attributes_subproperty_oldprice += $attributes_subproperty_oldprice_vat;

						if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) && (!$attributes->hide_attribute_price))
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name) . " (" . $subproperty [$i]->oprand . $this->getProductFormattedPrice($attributes_subproperty_vat_show) . ")";
						}
						else
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
						}
					}
					else
					{
						$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
					}

					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_name' . $subproperty [$i]->value . '" value="' . $subproperty [$i]->subattribute_color_name . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_oprand' . $subproperty [$i]->value . '" value="' . $subproperty [$i]->oprand . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_vat_show . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice_withoutvat' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_withoutvat . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_prooldprice' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_oldprice . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_stock' . $subproperty [$i]->value . '" value="' . $subproperty[$i]->stock . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_preOrderStock' . $subproperty [$i]->value . '" value="' . $subproperty[$i]->preorder_stock . '" />';
				}

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$subproperty_woscrollerdiv .= "</div>";
				}

				if (Redshop::getConfig()->get('USE_ENCODING'))
				{
					$displayPropertyName = mb_convert_encoding(urldecode($subproperty[0]->property_name), "ISO-8859-1", "UTF-8");

				}
				else
				{
					$displayPropertyName = urldecode($subproperty[0]->property_name);
				}

				if ($subproperty[0]->subattribute_color_title != "")
				{
					if (Redshop::getConfig()->get('USE_ENCODING'))
					{
						$displayPropertyName = mb_convert_encoding(
							urldecode($subproperty[0]->subattribute_color_title),
							"ISO-8859-1",
							"UTF-8"
						);
					}
					else
					{
						$displayPropertyName = urldecode($subproperty[0]->subattribute_color_title);
					}
				}

				// Run event when prepare sub-properties data.
				RedshopHelperUtility::getDispatcher()->trigger('onPrepareProductSubProperties', array($product, &$subproperty));

				$subproperties = array_merge(
					array(JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT') . ' ' . $displayPropertyName)),
					$subproperty
				);
				$attDisplayType = (isset($subproperty[0]->setdisplay_type)) ? $subproperty[0]->setdisplay_type : 'radio';

				// Init listing html-attributes
				$chkListAttributes = array(
					'id'          => $subpropertyid,
					'subpropName' => $displayPropertyName
				);

				// Only add required html-attibute if needed.
				if ($subproperty[0]->setrequire_selected)
				{
					$chkListAttributes['required'] = 'true';
				}

				$scrollerFunction = '';

				if ($imgAdded > 0 && strstr($attribute_table, "{subproperty_image_scroller}"))
				{
					$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(this.selectedIndex-1);";

					if ('radio' == $attDisplayType)
					{
						$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(\"" . $chk . "\");";
					}
				}

				// Prepare Javascript OnChange or OnClick function
				$onChangeJSFunction = $scrollerFunction
					. "calculateTotalPrice('" . $product_id . "','" . $relatedprd_id . "');"
					. "displayAdditionalImage('" . $product_id . "','" . $accessory_id . "','" . $relatedprd_id . "','" . $property_id . "',this.value);";

				// Radio or Checkbox
				if ('radio' == $attDisplayType)
				{
					unset($subproperties[0]);

					$attributeListType = ($subproperty[0]->setmulti_selected) ? 'redshopselect.checklist' : 'redshopselect.radiolist';

					$chkListAttributes['cssClassSuffix'] = ' no-group';
					$chkListAttributes['onClick']        = "javascript:" . $onChangeJSFunction;
				}
				// Dropdown list
				else
				{
					$attributeListType = 'select.genericlist';
					$chkListAttributes['onchange'] = "javascript:" . $onChangeJSFunction;
				}

				$lists['subproperty_id'] = JHTML::_(
					$attributeListType,
					$subproperties,
					$subpropertyid . '[]',
					$chkListAttributes,
					'value',
					'text',
					$selectedsubproperty,
					$subpropertyid
				);

				$subPropertyScroller = RedshopLayoutHelper::render(
					'product.subproperty_scroller',
					array(
							'subProperties'     => $subproperty,
							'commonId'          => $commonid,
							'productId'         => $product_id,
							'propertyId'        => $property_id,
							'subPropertyId'     => $subpropertyid,
							'accessoryId'       => $accessory_id,
							'relatedProductId'  => $relatedprd_id,
							'selectSubproperty' => $selectedsubproperty,
							'subPropertyArray'  => $subprop_Arry,
							'width'             => $mpw_thumb,
							'height'            => $mph_thumb
						),
					'',
					array(
							'component' => 'com_redshop'
						)
				);

				if ($imgAdded == 0 || $isAjax == 1)
				{
					$subPropertyScroller = "";
				}

				if ($subproperty[0]->setrequire_selected == 1)
				{
					$displayPropertyName = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? $displayPropertyName . "<span id='asterisk_right'> * </span>" : "<span id='asterisk_left'>* </span>" . $displayPropertyName;
				}
				$attribute_table = str_replace("{property_title}", $displayPropertyName, $attribute_table);
				$attribute_table = str_replace("{subproperty_dropdown}", $lists ['subproperty_id'], $attribute_table);

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", $subproperty_woscrollerdiv, $attribute_table);
				}
				elseif (strpos($subatthtml, "{subproperty_image_scroller}") !== false)
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", $subPropertyScroller, $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", "", $attribute_table);
				}
			}
		}

		return $attribute_table;
	}

	public function defaultAttributeDataPrice($product_id = 0, $product_showprice = 0, $data_add, $user_id = 0, $applyTax = 0, $attributes = array())
	{
		if (count($attributes) <= 0 || Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			return $product_showprice;
		}

		$attribute_template = $this->getAttributeTemplate($data_add);

		if (count($attribute_template) <= 0)
		{
			return $product_showprice;
		}

		foreach ($attributes as $attribute)
		{
			$properties = empty($attribute->properties) ? $this->getAttibuteProperty(0, $attribute->attribute_id) : $attribute->properties;

			if ($attribute->text != "" && count($properties) > 0)
			{
				$selectedPropertyId = array();
				$proprice           = array();
				$prooprand          = array();

				foreach ($properties as $property)
				{
					if ($property->setdefault_selected)
					{
						if ($property->property_price > 0)
						{
							$attributes_property_vat = 0;

							if ($applyTax)
							{
								$attributes_property_vat = $this->getProducttax($product_id, $property->property_price, $user_id);
							}

							$property->property_price += $attributes_property_vat;
						}

						$proprice[]           = $property->property_price;
						$prooprand[]          = $property->oprand;
						$selectedPropertyId[] = $property->property_id;
					}
				}

				if (!$attribute->allow_multiple_selection && count($proprice) > 0)
				{
					$proprice           = array($proprice[count($proprice) - 1]);
					$prooprand          = array($prooprand[count($prooprand) - 1]);
					$selectedPropertyId = array($selectedPropertyId[count($selectedPropertyId) - 1]);
				}
				// Add default selected Property price to product price
				$default_priceArr  = $this->makeTotalPriceByOprand($product_showprice, $prooprand, $proprice);
				$product_showprice = $default_priceArr[1];

				for ($i = 0, $countProperty = count($selectedPropertyId), $in = $countProperty; $i < $in; $i++)
				{
					$subproprice  = array();
					$subprooprand = array();
					$subproperty  = $this->getAttibuteSubProperty(0, $selectedPropertyId[$i]);

					for ($sp = 0, $countSubproperty = count($subproperty); $sp < $countSubproperty; $sp++)
					{
						if ($subproperty[$sp]->setdefault_selected)
						{
							if ($subproperty[$sp]->subattribute_color_price > 0)
							{
								$attributes_subproperty_vat = 0;

								if ($applyTax)
								{
									$attributes_subproperty_vat = $this->getProducttax(
										$product_id,
										$subproperty[$sp]->subattribute_color_price,
										$user_id
									);
								}

								$subproperty[$sp]->subattribute_color_price += $attributes_subproperty_vat;
							}

							$subproprice[]  = $subproperty[$sp]->subattribute_color_price;
							$subprooprand[] = $subproperty[$sp]->oprand;
						}
					}

					if (count($subproprice) > 0 && !$subproperty[0]->setmulti_selected)
					{
						$subproprice  = array($subproprice[count($subproprice) - 1]);
						$subprooprand = array($subprooprand[count($subprooprand) - 1]);
					}
					// Add default selected Property price to product price
					$default_priceArr  = $this->makeTotalPriceByOprand($product_showprice, $subprooprand, $subproprice);
					$product_showprice = $default_priceArr[1];
				}
			}
		}

		return $product_showprice;
	}

	public function replacePropertyAddtoCart($product_id = 0, $property_id = 0, $category_id = 0, $commonid = "", $property_stock = 0, $property_data = "", $cart_template = array(), $data_add = "")
	{
		$input           = JFactory::getApplication()->input;
		$user_id         = 0;
		$url             = JURI::base();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$Itemid          = $input->getInt('Itemid');

		$product = $this->getProductById($product_id);

		// Process the product plugin for property
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onPropertyAddtoCart', array(&$property_data, &$cart_template, &$property_stock, $property_id, $product));

		if ($property_stock <= 0)
		{
			$property_data = str_replace("{form_addtocart:$cart_template->template_name}", JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'), $property_data);

			return $property_data;
		}

		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
		$qunselect           = 1;
		$productArr          = $this->getProductNetPrice($product_id, $user_id, $qunselect, $data_add);
		$product_price       = $productArr['product_price'] * $qunselect;
		$product_price_novat = $productArr['product_price_novat'] * $qunselect;
		$product_old_price   = $productArr['product_old_price'] * $qunselect;

		if ($product->not_for_sale)
		{
			$product_price = 0;
		}

		$max_quantity = $product->max_order_product_quantity;
		$min_quantity = $product->min_order_product_quantity;

		$addtocartFormName = 'addtocart_' . $commonid . '_' . $property_id;
		$stockId           = $commonid . '_' . $property_id;
		$attribute_id      = 0;
		$arr               = explode("_", $commonid);

		if (count($arr) > 0)
		{
			$attribute_id = $arr[count($arr) - 1];
		}

		$cartform = "<form name='" . $addtocartFormName
			. "' id='" . $addtocartFormName
			. "' class='addtocart_formclass' action='' method='post'>";
		$cartform .= $cart_template->template_desc;

		$cartform .= "
			<input type='hidden' name='product_id' id='product_id' value='" . $product_id . "'>
			<input type='hidden' name='category_id' value='" . $category_id . "'>
			<input type='hidden' name='view' value='cart'>
			<input type='hidden' name='task' value='add'>
			<input type='hidden' name='option' value='com_redshop'>
			<input type='hidden' name='Itemid' id='Itemid' value='" . $Itemid . "'>
			<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

			<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
			<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
			<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
			<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
			<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
			<input type='hidden' name='accessory_price' id='accessory_price' value='0'>

			<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute=''>
			<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty=''>

			<input type='hidden' name='main_price' id='main_price" . $product_id . "' value='" . $product_price . "' />
			<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

			<input type='hidden' name='product_old_price' id='product_old_price" . $product_id . "' value='"
			. $product_old_price . "' />
			<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

			<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $product_id . "' value='"
			. $product_price_novat . "' />
			<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

			<input type='hidden' name='min_quantity' id='min_quantity' value='" . $min_quantity . "' requiredtext='"
			. JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
			<input type='hidden' name='max_quantity' id='max_quantity' value='" . $max_quantity . "' requiredtext='"
			. JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

			<input type='hidden' name='attribute_data' id='attribute_data' value='" . $attribute_id . "'>
			<input type='hidden' name='property_data' id='property_data' value='" . $property_id . "'>
			<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>

			<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
			<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
			<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
			<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
			<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
			<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
			<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $product_id .
			"' value='' />";

		if ($product->product_type == "subscription")
		{
			$sub_id = $input->getInt('subscription_id', 0);
			$cartform .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='" . $sub_id .
				"' />";
			$cartform .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
		}

		if ($product->min_order_product_quantity > 0)
		{
			$quan = $product->min_order_product_quantity;
		}
		else
		{
			$quan = 1;
		}

		if (strpos($cartform, "{addtocart_quantity}") !== false)
		{
			$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'><input class='quantity inputbox input-mini' type='text' name='quantity' id='quantity" .
				$product_id . "' value='" . $quan . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') .
				"' onchange='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
			$cartform           = str_replace("{addtocart_quantity}", $addtocart_quantity, $cartform);
			$cartform           = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
		}
		elseif (strpos($cartform, "{addtocart_quantity_selectbox}") !== false)
		{
			$addtocart_quantity = "<input class='quantity' type='hidden' name='quantity' id='quantity" . $product_id . "' value='" .
				$quan . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";

			if ((Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') != ""
					&& $product->quantity_selectbox_value == '')
				|| $product->quantity_selectbox_value != '')
			{
				$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
				$quaboxarr       = explode(",", $selectbox_value);
				$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
				sort($quaboxarr);
				$qselect = "<select name='quantity' id='quantity" . $product_id . "'  OnChange='calculateTotalPrice("
					. $product_id . ",0);'>";

				for ($q = 0, $qn = count($quaboxarr); $q < $qn; $q++)
				{
					if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
					{
						$quantityselect = ($quan == intval($quaboxarr[$q])) ? "selected" : "";
						$qselect .= "<option value='" . intVal($quaboxarr[$q]) . "' " . $quantityselect . ">"
							. intVal($quaboxarr[$q]) . "</option>";
					}
				}

				$qselect .= "</select>";
				$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'>" . $qselect . "</span>";
			}

			$cartform = str_replace("{addtocart_quantity_selectbox}", $addtocart_quantity, $cartform);
			$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
		}
		else
		{
			$cartform .= "<input class='quantity' type='hidden' name='quantity' id='quantity" . $product_id . "' value='" . $quan
				. "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";
		}

		$tooltip             = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$ADD_OR_LBL          = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : JText::_('COM_REDSHOP_ADD_TO_CART');
		$ADD_CART_IMAGE      = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? Redshop::getConfig()->get('REQUESTQUOTE_IMAGE') : Redshop::getConfig()->get('ADDTOCART_IMAGE');
		$ADD_CART_BACKGROUND = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND') : Redshop::getConfig()->get('ADDTOCART_BACKGROUND');

		$cartTag   = '';
		$cartIcon  = '';
		$cartTitle = ' title="' . $ADD_OR_LBL . '" ';

		$onclick = 'onclick="if(displayAddtocartProperty(\'' . $addtocartFormName . '\',\'' . $product_id . '\',\'' .
			$attribute_id . '\',\'' . $property_id . '\')){checkAddtocartValidation(\'' . $addtocartFormName . '\',\'' .
			$product_id . '\',0,0,\'\',0,0,0);}" ';
		$class   = 'class=""';
		$title   = 'title=""';

		if (strpos($cartform, "{addtocart_tooltip}") !== false)
		{
			$class    = 'class="editlinktip hasTip"';
			$title    = ' title="' . $tooltip . '" ';
			$cartform = str_replace("{addtocart_tooltip}", $cartform, "");
		}

		if (strpos($cartform, "{addtocart_button}") !== false)
		{
			$cartTag  = "{addtocart_button}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '" class="icon_cart"><input type="button" ' .
				$onclick . $cartTitle . ' name="addtocart_button" value="' . $ADD_OR_LBL . '" /></span>';
		}

		if (strpos($cartform, "{addtocart_link}") !== false)
		{
			$cartTag  = "{addtocart_link}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '" ' . $onclick . $cartTitle .
				' style="cursor: pointer;" class="tag_cart">' . $ADD_OR_LBL . '</span>';
		}

		if (strpos($cartform, "{addtocart_image_aslink}") !== false)
		{
			$cartTag  = "{addtocart_image_aslink}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '" class="img_linkcart"><img ' . $onclick .
				$cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$ADD_CART_IMAGE . '" /></span>';
		}

		if (strpos($cartform, "{addtocart_image}") !== false)
		{
			$cartTag  = "{addtocart_image}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '><div ' . $onclick .
				$cartTitle . ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$ADD_CART_BACKGROUND . ');background-position:bottom;background-repeat:no-repeat;" class="img_cart">' . $ADD_OR_LBL .
				'</div></span>';
		}

		$cartform = str_replace($cartTag, '<span id="stockaddtocart' . $stockId . '"></span>' . $cartIcon, $cartform);

		// Trigger event on Add to Cart
		$dispatcher->trigger('onAddtoCart', array(&$cartform, $product, $addtocartFormName, $property_id));

		$cartform .= "</form>";

		$property_data = str_replace("{form_addtocart:$cart_template->template_name}", $cartform, $property_data);

		return $property_data;
	}

	public function replaceCartTemplate($product_id = 0, $category_id = 0, $accessory_id = 0, $relproduct_id = 0, $data_add = "", $isChilds = false, $userfieldArr = array(), $totalatt = 0, $totalAccessory = 0, $count_no_user_field = 0, $module_id = 0, $giftcard_id = 0)
	{
		$user_id          = 0;
		$redconfig        = Redconfiguration::getInstance();
		$extraField       = extraField::getInstance();
		$stockroomhelper  = rsstockroomhelper::getInstance();
		$input            = JFactory::getApplication()->input;
		$product_quantity = $input->get('product_quantity');
		$Itemid           = $input->getInt('Itemid');
		$user             = JFactory::getUser();
		$product_preorder = "";

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$field_section = 12;

		if ($relproduct_id != 0)
		{
			$product_id = $relproduct_id;
		}
		elseif ($giftcard_id != 0)
		{
			$product_id = $giftcard_id;
		}

		if ($giftcard_id != 0)
		{
			$product       = $this->getGiftcardData($giftcard_id);
			$field_section = 13;
		}
		else
		{
			$product = $this->getProductById($product_id);

			if(isset($product->preorder))
			{
				$product_preorder = $product->preorder;
			}
		}

		$taxexempt_addtocart = $this->taxexempt_addtocart($user_id, 1);

		$cart_template = $this->getAddtoCartTemplate($data_add);

		if (count($cart_template) <= 0 && $data_add != "")
		{
			$cart_template                = new stdclass;
			$cart_template->template_name = "";
			$cart_template->template_desc = "";
		}

		if ($data_add == "" && count($cart_template) <= 0)
		{
			$cart_template                = new stdclass;
			$cart_template->template_name = "notemplate";
			$cart_template->template_desc = "<div>{addtocart_image_aslink}</div>";
			$data_add                     = "{form_addtocart:$cart_template->template_name}";
		}

		$layout = $input->getCmd('layout');
		$cart   = $this->_session->get('cart');

		$isAjax                 = 0;
		$preprefix              = "";
		$preselected_attrib_img = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax    = 1;
			$preprefix = "ajax_";
		}

		$prefix = $preprefix . "prd_";

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relproduct_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}

		if (!empty($module_id))
		{
			$prefix = $prefix . $module_id . "_";
		}

		$totrequiredatt  = "";
		$totrequiredprop = '';

		$isPreorderStockExists = '';

		if ($giftcard_id != 0)
		{
			$product_price       = $product->giftcard_price;
			$product_price_novat = 0;
			$product_old_price   = 0;
			$isStockExists       = true;
			$max_quantity        = 0;
			$min_quantity        = 0;
		}
		else
		{
			// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
			if ($isChilds)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", "", $data_add);

				return $data_add;
			}
			elseif ($this->isProductDateRange($userfieldArr, $product_id))
			{
				// New type custome field - Selection based on selected conditions
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", JText::_('COM_REDSHOP_PRODUCT_DATE_FIELD_EXPIRED'), $data_add);

				return $data_add;
			}
			elseif ($product->not_for_sale)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif (!$taxexempt_addtocart)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif (!Redshop::getConfig()->get('SHOW_PRICE'))
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif ($product->expired == 1)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", Redshop::getConfig()->get('PRODUCT_EXPIRE_TEXT'), $data_add);

				return $data_add;
			}

			// Get stock for Product

			$isStockExists         = $stockroomhelper->isStockExists($product_id);

			if ($totalatt > 0 && !$isStockExists)
			{
				$property = $this->getAttibuteProperty(0, 0, $product_id);

				for ($att_j = 0, $countProperty = count($property); $att_j < $countProperty; $att_j++)
				{
					$isSubpropertyStock = false;
					$sub_property       = $this->getAttibuteSubProperty(0, $property[$att_j]->property_id);

					for ($sub_j = 0, $countSubproperty = count($sub_property); $sub_j < $countSubproperty; $sub_j++)
					{
						$isSubpropertyStock = $stockroomhelper->isStockExists(
							$sub_property[$sub_j]->subattribute_color_id,
							'subproperty'
						);

						if ($isSubpropertyStock)
						{
							$isStockExists = $isSubpropertyStock;
							break;
						}
					}

					if ($isSubpropertyStock)
					{
						break;
					}
					else
					{
						$isPropertystock = $stockroomhelper->isStockExists($property[$att_j]->property_id, "property");

						if ($isPropertystock)
						{
							$isStockExists = $isPropertystock;
							break;
						}
					}
				}
			}

			$qunselect = $this->GetDefaultQuantity($product_id, $data_add);

			$productArr          = $this->getProductNetPrice($product_id, $user_id, $qunselect, $data_add);
			$product_price       = $productArr['product_price'] * $qunselect;
			$product_price_novat = $productArr['product_price_novat'] * $qunselect;
			$product_old_price   = $productArr['product_old_price'] * $qunselect;

			if ($product->not_for_sale)
			{
				$product_price = 0;
			}

			$max_quantity = $product->max_order_product_quantity;
			$min_quantity = $product->min_order_product_quantity;

		}

		$stockdisplay        = false;
		$preorderdisplay     = false;
		$cartdisplay         = false;

		$display_text = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

		if (!$isStockExists)
		{

			if (($product_preorder == "global"
					&& Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				|| ($product_preorder == "yes")
				|| ($product_preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
			)
			{
				// Get preorder stock for Product

				$isPreorderStockExists = $stockroomhelper->isPreorderStockExists($product_id);

				if ($totalatt > 0 && !$isPreorderStockExists)
				{
					$property = $this->getAttibuteProperty(0, 0, $product_id);

					for ($att_j = 0, $countProperty = count($property); $att_j < $countProperty; $att_j++)
					{
						$isSubpropertyStock = false;
						$sub_property       = $this->getAttibuteSubProperty(0, $property[$att_j]->property_id);
						$countSubproperty   = count($sub_property);

						for ($sub_j = 0; $sub_j < $countSubproperty; $sub_j++)
						{
							$isSubpropertyStock = $stockroomhelper->isPreorderStockExists(
								$sub_property[$sub_j]->subattribute_color_id,
								'subproperty'
							);

							if ($isSubpropertyStock)
							{
								$isPreorderStockExists = $isSubpropertyStock;
								break;
							}
						}

						if ($isSubpropertyStock)
						{
							break;
						}
						else
						{
							$isPropertystock = $stockroomhelper->isPreorderStockExists(
								$property[$att_j]->property_id,
								"property"
							);

							if ($isPropertystock)
							{
								$isPreorderStockExists = $isPropertystock;
								break;
							}
						}
					}
				}

				// Check preorder stock
				if (!$isPreorderStockExists)
				{
					$preorder_stock_flag = true;
					$stockdisplay        = true;
					$add_cart_flag       = true;
					$display_text        = JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');

				}
				else
				{
					//$pre_order_value = 1;
					$preorderdisplay     = true;
					$add_cart_flag       = true;
					$p_availability_date = "";

					if ($product->product_availability_date != "")
					{
						$p_availability_date = $redconfig->convertDateFormat($product->product_availability_date);
					}
				}

			}
			else
			{
				$stockdisplay  = true;
				$add_cart_flag = true;
			}
		}
		else
		{
			$cartdisplay   = true;
			$add_cart_flag = true;
		}

		$p_availability_date = "";
		$ADD_OR_PRE_LBL      = JText::_('COM_REDSHOP_PRE_ORDER');
		$ADD_OR_PRE_TOOLTIP  = str_replace("{availability_date}", $p_availability_date, Redshop::getConfig()->get('ALLOW_PRE_ORDER_MESSAGE'));
		$ADD_OR_PRE_BTN      = Redshop::getConfig()->get('PRE_ORDER_IMAGE');
		$tooltip             = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$ADD_OR_LBL          = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : JText::_('COM_REDSHOP_ADD_TO_CART');
		$ADD_CART_IMAGE      = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? Redshop::getConfig()->get('REQUESTQUOTE_IMAGE') : Redshop::getConfig()->get('ADDTOCART_IMAGE');
		$ADD_CART_BACKGROUND = (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) ? Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND') : Redshop::getConfig()->get('ADDTOCART_BACKGROUND');
		$ADD_OR_TOOLTIP      = "";

		if ($totalatt > 0)
		{
			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $this->getProductAttribute(0, $product->attribute_set_id, 0, 1, 1);
			}

			$requiredattribute = $this->getProductAttribute($product_id, 0, 0, 1, 1);
			$requiredattribute = array_merge($requiredattribute, $attributes_set);

			for ($i = 0, $in = count($requiredattribute); $i < $in; $i++)
			{
				$totrequiredatt .= JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredattribute[$i]->attribute_name) . "\n";
			}

			$requiredproperty = $this->getAttibuteProperty(0, 0, $product_id, 0, 1);

			for ($y = 0, $yn = count($requiredproperty); $y < $yn; $y++)
			{
				$totrequiredprop .= JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredproperty[$y]->property_name) . "\n";
			}
		}

		$stockId = $prefix . $product_id;

		if ($add_cart_flag)
		{
			if ($giftcard_id == 0 && $category_id == 0)
			{
				$category_id = $this->getCategoryProduct($product_id);
			}

			$addtocartFormName = 'addtocart_' . $prefix . $product_id; //$category_id
			$cartform          = "<form name='" . $addtocartFormName . "' id='" . $addtocartFormName
				. "' class='addtocart_formclass' action='' method='post'>";
			$cartform .= $cart_template->template_desc;

			if (count($userfieldArr) > 0)
			{
				$product_userhiddenfileds = '<table>';
				$idx                      = 0;

				if (isset ($cart ['idx']))
				{
					$idx = ( int ) ($cart ['idx']);
				}

				$cart_id = '';

				for ($j = 0; $j < $idx; $j++)
				{
					if ($giftcard_id != 0)
					{
						if ($cart [$j] ['giftcard_id'] == $product_id)
						{
							$cart_id = $j;
						}
					}
					else
					{
						if ($cart [$j] ['product_id'] == $product_id)
						{
							$cart_id = $j;
						}
					}
				}

				for ($ui = 0, $countUserfieldArr = count($userfieldArr); $ui < $countUserfieldArr; $ui++)
				{
					$result_arr = $extraField->list_all_user_fields(
						$userfieldArr[$ui],
						$field_section,
						"hidden",
						$cart_id,
						$isAjax, $product_id
					);

					$product_userhiddenfileds .= $result_arr[1];
				}

				$product_userhiddenfileds .= '</table>';
				$cartform .= $product_userhiddenfileds;
			}

			//Start Hidden attribute image in cart
			$attributes = $this->getProductAttribute($product_id);

			if (count($attributes) > 0)
			{
				$selectedPropertyId    = 0;
				$selectedsubpropertyId = 0;

				for ($a = 0, $an = count($attributes); $a < $an; $a++)
				{
					$selectedId = array();
					$property   = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id, $product_id);

					if ($attributes[$a]->text != "" && count($property) > 0)
					{
						for ($i = 0, $in = count($property); $i < $in; $i++)
						{
							if ($property[$i]->setdefault_selected)
							{
								$selectedId[] = $property[$i]->property_id;
							}
						}

						if (count($selectedId) > 0)
						{
							$selectedPropertyId = $selectedId[count($selectedId) - 1];
							$subproperty        = $this->getAttibuteSubProperty(0, $selectedPropertyId);
							$selectedId         = array();

							for ($sp = 0, $countSubproperty = count($subproperty); $sp < $countSubproperty; $sp++)
							{
								if ($subproperty[$sp]->setdefault_selected)
								{
									$selectedId[] = $subproperty[$sp]->subattribute_color_id;
								}
							}

							if (count($selectedId) > 0)
							{
								$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
							}
						}
					}
				}

				$preselected_attrib_img = $this->get_hidden_attribute_cartimage(
					$product_id,
					$selectedPropertyId,
					$selectedsubpropertyId
				);

			}
			//End
			$cartform .= "
				<input type='hidden' name='preorder_product_stock' id='preorder_product_stock" . $product_id .
				"' value='" . $isPreorderStockExists . "'>
		        <input type='hidden' name='product_stock' id='product_stock" . $product_id . "' value='" .
				$isStockExists . "'>
				<input type='hidden' name='product_preorder' id='product_preorder" . $product_id . "' value='" .
				$product_preorder . "'>
				<input type='hidden' name='product_id' id='product_id' value='" . $product_id . "'>
				<input type='hidden' name='category_id' value='" . $category_id . "'>
				<input type='hidden' name='view' value='cart'>
				<input type='hidden' name='task' value='add'>
				<input type='hidden' name='option' value='com_redshop'>
				<input type='hidden' name='Itemid' id='Itemid' value='" . $Itemid . "'>
				<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

				<input type='hidden' name='main_price' id='main_price" . $product_id . "' value='" . $product_price .
				"' />
				<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

				<input type='hidden' name='product_old_price' id='product_old_price" . $product_id . "' value='" .
				$product_old_price . "' />
				<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

				<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $product_id . "' value='" .
				$product_price_novat . "' />
				<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

				<input type='hidden' name='min_quantity' id='min_quantity' value='" . $min_quantity .
				"' requiredtext='" . JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
				<input type='hidden' name='max_quantity' id='max_quantity' value='" . $max_quantity .
				"' requiredtext='" . JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

				<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
				<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
				<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
				<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
				<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
				<input type='hidden' name='accessory_price' id='accessory_price' value='0'>
				<input type='hidden' name='accessory_price_withoutvat' id='accessory_price_withoutvat' value='0'>

				<input type='hidden' name='attribute_data' id='attribute_data' value='0'>
				<input type='hidden' name='property_data' id='property_data' value='0'>
				<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>
				<input type='hidden' name='attribute_price' id='attribute_price' value='0'>
				<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute='" . $totrequiredatt . "'>
				<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty='" . $totrequiredprop . "'>

				<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
				<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
				<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
				<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
				<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
				<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
				<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $product_id
				. "' value='" . $preselected_attrib_img . "' />";

			if ($giftcard_id != 0)
			{
				$cartform .= "<input type='hidden' name='giftcard_id' id= 'giftcard_id' value='" . $giftcard_id . "'>
							<input type='hidden' name='reciver_email' id='reciver_email' value='" . @$cart['reciver_email'] . "'>
							<input type='hidden' name='reciver_name' id='reciver_name' value='" . @$cart['reciver_name'] . "'>";

				if ($product->customer_amount == 1)
					$cartform .= "<input type='hidden' name='customer_amount' id='customer_amount' value='" . @$cart['customer_amount'] . "'>";
			}
			else
			{
				if ($product->product_type == "subscription")
				{
					$sub_id = $input->getInt('subscription_id', 0);
					$cartform .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='"
						. $sub_id . "' />";
					$cartform .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
				}

				$ajaxdetail_templatedata = $this->getAjaxDetailboxTemplate($product);

				if (count($ajaxdetail_templatedata) > 0)
				{
					$ajax_cart_detail_temp_desc = $ajaxdetail_templatedata->template_desc;
					/*
					 * attribute, accessory, userfield check for ajax detail template
					 */
					// 	make attribute count 0. if there is no tag in ajax detail template
					if (strpos($ajax_cart_detail_temp_desc, "{attribute_template:") === false)
					{
						$totalatt = 0;
					}
					// 	make accessory count 0. if there is no tag in ajax detail template
					if (strpos($ajax_cart_detail_temp_desc, "{accessory_template:") === false)
					{
						$totalAccessory = 0;
					}
					// make userfields 0.if there is no tag available in ajax detail template
					if (strpos($ajax_cart_detail_temp_desc, "{if product_userfield}") !== false)
					{
						$ajax_extra_field1       = explode("{if product_userfield}", $ajax_cart_detail_temp_desc);
						$ajax_extra_field2       = explode("{product_userfield end if}", $ajax_extra_field1 [1]);
						$ajax_extra_field_center = $ajax_extra_field2 [0];

						if (strpos($ajax_extra_field_center, "{") === false)
						{
							$count_no_user_field = 0;
						}
					}
					else
					{
						$count_no_user_field = 0;
					}
				}
			}

			if ($product_quantity)
			{
				$quan = $product_quantity;
			}
			else
			{
				if ($giftcard_id != 0)
				{
					$quan = 1;
				}
				elseif ($product->min_order_product_quantity > 0)
				{
					$quan = $product->min_order_product_quantity;
				}
				else
				{
					$quan = 1;
				}
			}

			$addtocart_quantity = '';

			if (strpos($cartform, "{addtocart_quantity}") !== false)
			{
				$addtocart_quantity = "<span id='stockQuantity" . $stockId
					. "'><input class='quantity inputbox input-mini' type='text' name='quantity' id='quantity" . $product_id . "' value='" . $quan
					. "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY')
					. "' onblur='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
				$cartform           = str_replace("{addtocart_quantity}", $addtocart_quantity, $cartform);
				$cartform           = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			elseif (strpos($cartform, "{addtocart_quantity_increase_decrease}") !== false)
			{
				$addtocart_quantity .= '<input class="quantity" type="text"  id="quantity' . $product_id
					. '" name="quantity" size="1"  value="' . $quan . '" onkeypress="return event.keyCode!=13"/>';

				$addtocart_quantity .= '<input type="button" class="myupbutton" onClick="quantity' . $product_id
					. '.value = (+quantity' . $product_id . '.value+1)">';

				$addtocart_quantity .= '<input type="button" class="mydownbutton" onClick="quantity' . $product_id
					. '.value = (quantity' . $product_id . '.value); var qty1 = quantity' . $product_id
					. '.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity' . $product_id . '.value--;return false;">';

				$addtocart_quantity .= '<input type="hidden" name="product_id" value="' . $product_id . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="Itemid" value="' . $Itemid . '">
				<input type="hidden" name="task" value="">';
				$cartform = str_replace("{addtocart_quantity_increase_decrease}", $addtocart_quantity, $cartform);
				$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			elseif (strpos($cartform, "{addtocart_quantity_selectbox}") !== false)
			{
				$addtocart_quantity = "<input class='quantity_select' type='hidden' name='quantity' id='quantity" . $product_id . "' value='"
					. $quan . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";

				if ((Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') != "" && $product->quantity_selectbox_value == '')
					|| $product->quantity_selectbox_value != '')
				{
					$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
					$quaboxarr       = explode(",", $selectbox_value);
					$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
					sort($quaboxarr);
					$qselect = "<select name='quantity' id='quantity" . $product_id
						. "'  OnChange='calculateTotalPrice(" . $product_id . "," . $relproduct_id . ");'>";

					for ($q = 0, $qn = count($quaboxarr); $q < $qn; $q++)
					{
						if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
						{
							$quantityselect = ($quan == intval($quaboxarr[$q])) ? "selected" : "";
							$qselect .= "<option value='" . intVal($quaboxarr[$q]) . "' " . $quantityselect . ">"
								. intVal($quaboxarr[$q]) . "</option>";
						}
					}

					$qselect .= "</select>";
					$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'>" . $qselect . "</span>";
				}

				$cartform = str_replace("{addtocart_quantity_selectbox}", $addtocart_quantity, $cartform);
				$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			else
			{
				$cartform .= "<input class='quantity_select' type='hidden' name='quantity' id='quantity" . $product_id . "' value='"
					. $quan . "' maxlength='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "' size='" . Redshop::getConfig()->get('DEFAULT_QUANTITY') . "'>";
			}

			$stockstyle    = '';
			$cartstyle     = '';
			$preorderstyle = '';

			if ($preorderdisplay)
			{
				$stockstyle    = 'style="display:none"';
				$cartstyle     = 'style="display:none"';
				$preorderstyle = '';

				if (Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$preorderstyle = 'style="display:none"';

				}
			}

			if ($stockdisplay)
			{
				$stockstyle = '';

				if (Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$stockstyle = 'style="display:none"';

				}

				$cartstyle     = 'style="display:none"';
				$preorderstyle = 'style="display:none"';

			}

			if ($cartdisplay)
			{
				$stockstyle    = 'style="display:none"';
				$cartstyle     = '';
				$preorderstyle = 'style="display:none"';

				if (Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$cartstyle = 'style="display:none"';

				}
			}

			$cartTag   = '';
			$cartIcon  = '';
			$cartTitle = ' title="' . $ADD_OR_TOOLTIP . '" ';

			// Trigger event which hepls us to add new JS functions to the Add To Cart button onclick
			$addToCartClickJS = $dispatcher->trigger('onAddToCartClickJS', array($product, $cart));

			if (!empty($addToCartClickJS))
			{
				$addToCartClickJS = implode('', $addToCartClickJS);
			}
			else
			{
				$addToCartClickJS = "";
			}

			if ($giftcard_id)
				$onclick = ' onclick="' . $addToCartClickJS . 'if(validateEmail()){if(displayAddtocartForm(\'' .
					$addtocartFormName . '\',\'' .
					$product_id . '\',\'' .
					$relproduct_id . '\',\'' .
					$giftcard_id . '\', \'user_fields_form\')){checkAddtocartValidation(\'' .
					$addtocartFormName . '\',\'' .
					$product_id . '\',\'' .
					$relproduct_id . '\',\'' .
					$giftcard_id . '\', \'user_fields_form\',\'' .
					$totalatt . '\',\'' .
					$totalAccessory . '\',\'' .
					$count_no_user_field . '\');}}" ';
			else
			{
				$onclick = ' onclick="' . $addToCartClickJS . 'if(displayAddtocartForm(\'' . $addtocartFormName . '\',\'' . $product_id
					. '\',\'' . $relproduct_id . '\',\'' . $giftcard_id
					. '\', \'user_fields_form\')){checkAddtocartValidation(\'' . $addtocartFormName . '\',\''
					. $product_id . '\',\'' . $relproduct_id . '\',\'' . $giftcard_id . '\', \'user_fields_form\',\''
					. $totalatt . '\',\'' . $totalAccessory . '\',\'' . $count_no_user_field . '\');}" ';
			}

			$class = '';
			$title = '';

			if (strpos($cartform, "{addtocart_tooltip}") !== false)
			{
				$class    = 'class="editlinktip hasTip"';
				$title    = ' title="' . $tooltip . '" ';
				$cartform = str_replace("{addtocart_tooltip}", "", $cartform);
			}

			if (strpos($cartform, "{addtocart_button}") !== false)
			{
				$cartTag = "{addtocart_button}";

				if (Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartstyle
						. ' class="pdaddtocart"><input type="button" ' . $onclick . $cartTitle . ' name="addtocart_button" value="'
						. $ADD_OR_LBL . '" /></span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
						. ' ><span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartstyle
						. ' class="pdaddtocart"><input type="button" ' . $cartTitle . ' name="addtocart_button" value="' . $ADD_OR_LBL
						. '" /></span></a>';
				}
			}

			if (strpos($cartform, "{addtocart_link}") !== false)
			{
				$cartTag = "{addtocart_link}";

				if (Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
						. '" ' . $onclick . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_link btn btn-primary">' . $ADD_OR_LBL . '</span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
						. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
						. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_link btn btn-primary">' . $ADD_OR_LBL . '</span></a>';
				}
			}

			if (strpos($cartform, "{addtocart_image_aslink}") !== false)
			{
				$cartTag = "{addtocart_image_aslink}";

				if (Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_IMAGE))
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_img_link"><img ' . $onclick . $cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_IMAGE . '" /></span>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId . '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_img_link">' . $ADD_OR_LBL . '</span></a>';

				}
				else
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_IMAGE))
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_img_link"><img ' . $cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="'
							. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_IMAGE . '" /></span></a>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_img_link">' . $ADD_OR_LBL . '</span></a>';

				}

			}

			if (strpos($cartform, "{addtocart_image}") !== false)
			{
				$cartTag = "{addtocart_image}";

				if (Redshop::getConfig()->get('AJAX_CART_BOX') != 1)
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_BACKGROUND))
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_imgage"><div ' . $onclick . $cartTitle
							. ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH
							. $ADD_CART_BACKGROUND . ');background-position:bottom;background-repeat:no-repeat;">'
							. $ADD_OR_LBL . '</div></span>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_imgage">' . $ADD_OR_LBL . '</span></a>';

				}
				else
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_BACKGROUND))
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" class="pdaddtocart_imgage"><div ' . $cartTitle . ' align="center" style="cursor:pointer;background:url('
							. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_BACKGROUND
							. ');background-position:bottom;background-repeat:no-repeat;">' . $ADD_OR_LBL . '</div></span></a>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;" class="pdaddtocart_imgage">' . $ADD_OR_LBL . '</span></a>';

				}
			}
			// pre-Order
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_OR_PRE_BTN))
			{
				$cartIconPreorder = '<span class="preordercart_order" id="preordercart' . $stockId . '" ' . $preorderstyle . '><img ' . $onclick
					. $cartTitle . ' alt="' . $ADD_OR_PRE_LBL . '" style="cursor: pointer;" src="'
					. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_OR_PRE_BTN . '" /></span>';
			}
			else
			{
				$cartIconPreorder = '<span class="preordercart_order_m" id="preordercart' . $stockId . '" ' . $preorderstyle
					. '><a href="javascript:;" ' . $onclick . '>' . JTEXT::_('COM_REDSHOP_PREORDER_BTN') . '</a></span>';
			}

			$cartform = str_replace($cartTag, '<span class="stockaddtocart" id="stockaddtocart' . $stockId . '" ' . $stockstyle
				. ' class="stock_addtocart">' . $display_text . '</span>' . $cartIconPreorder . $cartIcon, $cartform);

			// Trigger event on Add to Cart
			$dispatcher->trigger('onAddtoCart', array(&$cartform, $product, $addtocartFormName, 0));

			$cartform .= "</form>";

			$data_add = str_replace("{form_addtocart:$cart_template->template_name}", $cartform, $data_add);
		}

		return $data_add;
	}

	/**
	 * Method for replace wishlist tag in template.
	 *
	 * @param   int     $productId        Product ID
	 * @param   string  $templateContent  HTML data of template content
	 *
	 * @return  string                    HTML data of replaced content.
	 *
	 * @since   1.5
	 *
	 * @deprecated  2.0.3    Use RedshopHelperWishlist::replaceWishlistTag() instead
	 */
	public function replaceWishlistButton($productId = 0, $templateContent = '', $formId = '')
	{
		return RedshopHelperWishlist::replaceWishlistTag($productId, $templateContent, $formId);
	}

	public function replaceCompareProductsButton($product_id = 0, $category_id = 0, $data_add = "", $is_relatedproduct = 0)
	{
		$prefix = ($is_relatedproduct == 1) ? "related" : "";

		// For compare product div...
		if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') != "")
		{
			if (strpos($data_add, '{' . $prefix . 'compare_product_div}') !== false)
			{
				$compare_product_div = RedshopLayoutHelper::render('product.compare');

				$data_add = str_replace("{compare_product_div}", $compare_product_div, $data_add);
			}

			if (strpos($data_add, '{' . $prefix . 'compare_products_button}') !== false)
			{
				if ($category_id == 0)
				{
					$category_id = $this->getCategoryProduct($product_id);
				}

				$compareButton = new stdClass;
				$compareButton->text = JText::_("COM_REDSHOP_ADD_TO_COMPARE");
				$compareButton->value = $product_id . '.' . $category_id;

				$compareButtonAttributes = array(
					'cssClassSuffix' => ' no-group'
				);

				$compare_product = JHTML::_(
					'redshopselect.checklist',
					array($compareButton),
					'rsProductCompareChk',
					$compareButtonAttributes,
					'value',
					'text',
					(new RedshopProductCompare)->getItemKey($product_id)
				);
				$data_add = str_replace("{" . $prefix . "compare_products_button}", $compare_product, $data_add);
			}
		}
		else
		{
			$data_add = str_replace("{" . $prefix . "compare_product_div}", "", $data_add);
			$data_add = str_replace("{" . $prefix . "compare_products_button}", "", $data_add);
		}

		return $data_add;
	}

	public function makeAccessoryCart($attArr = array(), $product_id = 0, $user_id = 0, $data = '')
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$data                  = $this->getcartTemplate();
		$chktag                = $this->getApplyattributeVatOrNot($data[0]->template_desc, $user_id);
		$setPropEqual          = true;
		$setSubpropEqual       = true;
		$displayaccessory      = "";
		$accessory_total_price = 0;
		$accessory_vat_price   = 0;

		if (count($attArr) > 0)
		{
			for ($i = 0, $in = count($attArr); $i < $in; $i++)
			{
				$acc_vat = 0;

				if ($attArr[$i]['accessory_price'] > 0)
				{
					$acc_vat = $this->getProducttax($product_id, $attArr[$i]['accessory_price'], $user_id);
				}

				$accessory_price = $attArr[$i]['accessory_price'];

				if (!empty($chktag))
				{
					$accessory_price     = $attArr[$i]['accessory_price'] + $acc_vat;
					$accessory_vat_price = $acc_vat;
				}

				$attchildArr = $attArr[$i]['accessory_childs'];

				for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
				{
					$prooprand      = array();
					$proprice       = array();
					$provatprice    = array();
					$provat         = array();
					$subprooprand   = array();
					$subproprice    = array();
					$subprovatprice = array();
					$subprovat      = array();

					$propArr = $attchildArr[$j]['attribute_childs'];

					for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
					{
						$property_price = $propArr[$k]['property_price'];
						$acc_vat        = 0;
						$acc_propvat    = 0;

						if ($propArr[$k]['property_price'] > 0)
						{
							$acc_propvat = $this->getProducttax($product_id, $propArr[$k]['property_price'], $user_id);
						}

						if (!empty($chktag))
						{
							$property_price = $property_price + $acc_propvat;
							$acc_vat        = $acc_propvat;
						}

						$prooprand[$k]   = $propArr[$k]['property_oprand'];
						$proprice[$k]    = $propArr[$k]['property_price'];
						$provatprice[$k] = $property_price;
						$provat[$k]      = $acc_vat;

						$subpropArr = $propArr[$k]['property_childs'];

						for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
						{
							$acc_vat           = 0;
							$acc_subpropvat    = 0;
							$subproperty_price = $subpropArr[$l]['subproperty_price'];

							if ($subpropArr[$l]['subproperty_price'] > 0)
							{
								$acc_subpropvat = $this->getProducttax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							if (!empty($chktag))
							{
								$subproperty_price = $subproperty_price + $acc_subpropvat;
								$acc_vat           = $acc_subpropvat;
							}


							$subprooprand[$k][$l]   = $subpropArr[$l]['subproperty_oprand'];
							$subproprice[$k][$l]    = $subpropArr[$l]['subproperty_price'];
							$subprovatprice[$k][$l] = $subproperty_price;
							$subprovat[$k][$l]      = $acc_vat;
						}
					}

					/// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
					if ($setPropEqual && $setSubpropEqual)
					{
						$accessory_priceArr = $this->makeTotalPriceByOprand($accessory_price, $prooprand, $provatprice);
						$accessory_vatArr   = $this->makeTotalPriceByOprand($accessory_vat_price, $prooprand, $provat);
						//$setPropEqual = $accessory_priceArr[0];
						$accessory_price     = $accessory_priceArr[1];
						$accessory_vat_price = $accessory_vatArr[1];
					}

					for ($t = 0, $tn = count($propArr); $t < $tn; $t++)
					{
						$subElementArr = $propArr[$t]['property_childs'];

						if ($setPropEqual && $setSubpropEqual && isset($subprovatprice[$t]))
						{
							$accessory_priceArr = $this->makeTotalPriceByOprand(
								$accessory_price,
								$subprooprand[$t],
								$subprovatprice[$t]
							);
							$accessory_vatArr   = $this->makeTotalPriceByOprand(
								$accessory_vat_price,
								$subprooprand[$t],
								$subprovat[$t]
							);
							$accessory_price     = $accessory_priceArr[1];
							$accessory_vat_price = $accessory_vatArr[1];
						}
					}

					// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
				}

				$accessory_total_price += ($accessory_price);
			}

			$displayaccessory .= RedshopLayoutHelper::render(
				'product.product_accessory',
				array(
						'accessories' => $attArr,
						'productId'   => $product_id,
						'userId'      => $user_id,
						'checkTag'    => $chktag
					),
				'',
				array(
						'component' => 'com_redshop'
					)
			);
		}

		$accessory_total_price = $accessory_total_price - $accessory_vat_price;

		return array($displayaccessory, $accessory_total_price, $accessory_vat_price);
	}

	public function getcartTemplate()
	{
		if (empty($this->_cartTemplateData))
		{
			$redTemplate = Redtemplate::getInstance();

			if (!Redshop::getConfig()->get('USE_AS_CATALOG') || Redshop::getConfig()->get('USE_AS_CATALOG'))
				$this->_cartTemplateData = $redTemplate->getTemplate("cart");
			else
				$this->_cartTemplateData = $redTemplate->getTemplate("catalogue_cart");
		}

		return $this->_cartTemplateData;
	}

	public function makeAttributeCart($attributes = array(), $productId = 0, $userId = 0, $newProductPrice = 0, $quantity = 1, $data = '')
	{
		$user            = JFactory::getUser();
		$stockroomhelper = rsstockroomhelper::getInstance();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$sel                  = 0;
		$selP                 = 0;
		$applyVat             = $this->getApplyattributeVatOrNot($data, $userId);
		$setPropEqual         = true;
		$setSubpropEqual      = true;
		$selectedAttributs    = array();
		$selectedProperty     = array();
		$productOldprice      = 0;
		$productVatPrice      = 0;

		if ($newProductPrice != 0)
		{
			$productPrice = $newProductPrice;

			if ($productPrice > 0)
			{
				$productVatPrice = $this->getProductTax($productId, $productPrice, $userId);
			}
		}
		else
		{
			$productPrices   = $this->getProductNetPrice($productId, $userId, $quantity, $data);

			// Using price without vat to proceed with calcualtion - we will apply vat in the end.
			$productPrice    = $productPrices['product_price_novat'];
			$productVatPrice = $productPrices['productVat'];
			$productOldprice = $productPrices['product_old_price_excl_vat'];
		}

		$isStock          = $stockroomhelper->isStockExists($productId);
		$isPreorderStock  = $stockroomhelper->isPreorderStockExists($productId);
		$displayAttribute = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$propertiesOperator        = array();
			$propertiesPrice           = array();
			$propertiesPriceWithVat    = array();
			$propertiesVat             = array();
			$subPropertiesOperator     = array();
			$subPropertiesPrice        = array();
			$subPropertiesPriceWithVat = array();
			$subPropertiesVat          = array();

			$properties = $attributes[$i]['attribute_childs'];

			if (count($properties) > 0)
			{
				$displayAttribute++;
			}

			for ($k = 0, $kn = count($properties); $k < $kn; $k++)
			{
				$propertyVat             = 0;
				$propertyOperator        = $properties[$k]['property_oprand'];
				$propertyPriceWithoutVat = (isset($properties[$k]['property_price'])) ? $properties[$k]['property_price'] : 0;
				$property                = $this->getAttibuteProperty($properties[$k]['property_id']);
				$propertyPrice           = $propertyPriceWithoutVat;

				if ($propertyPriceWithoutVat > 0)
				{
					// Set property vat to 1 when price is 1. For * and / math rules.
					if ($propertyPriceWithoutVat == 1
						&& ($propertyOperator == '*' || $propertyOperator == '/'))
					{
						$propertyVat = 1;
					}

					if ($propertyOperator != '*' && $propertyOperator != '/')
					{
						$propertyVat = $this->getProducttax($productId, $propertyPriceWithoutVat, $userId);
					}
				}

				$isStock         = $stockroomhelper->isStockExists($properties[$k]['property_id'], "property");
				$isPreorderStock = $stockroomhelper->isPreorderStockExists($properties[$k]['property_id'], "property");

				$propertiesOperator[$k]     = $propertyOperator;
				$propertiesPrice[$k]        = $propertyPriceWithoutVat;
				$propertiesPriceWithVat[$k] = $propertyPrice;
				$propertiesVat[$k]          = $propertyVat;
				$subProperties              = $properties[$k]['property_childs'];

				for ($l = 0, $ln = count($subProperties); $l < $ln; $l++)
				{
					if ($l == 0)
					{
						$selectedProperty[$selP++] = $properties[$k]['property_id'];
					}

					// Continue if there is no subproperty id
					if (!(int) $subProperties[$l]['subproperty_id'])
					{
						continue;
					}

					$subPropertyVat             = 0;
					$subPropertyOperator        = $subProperties[$l]['subproperty_oprand'];
					$subPropertyPriceWithoutVat = $subProperties[$l]['subproperty_price'];
					$subPropertyPrice           = $subPropertyPriceWithoutVat;

					if ($subPropertyPriceWithoutVat > 0)
					{
						// Set property vat to 1 when price is 1. For * and / math rules.
						if ($subPropertyPriceWithoutVat == 1
							&& ($subPropertyOperator == '*' || $subPropertyOperator == '/'))
						{
							$subPropertyVat = 1;
						}

						if ($subPropertyOperator != '*' &&  $subPropertyOperator != '/')
						{
							$subPropertyVat = $this->getProducttax($productId, $subPropertyPriceWithoutVat, $userId);
						}
					}

					$isStock         = $stockroomhelper->isStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);
					$isPreorderStock = $stockroomhelper->isPreorderStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);

					$subPropertiesOperator[$k][$l]     = $subPropertyOperator;
					$subPropertiesPrice[$k][$l]        = $subPropertyPriceWithoutVat;
					$subPropertiesPriceWithVat[$k][$l] = $subPropertyPrice;
					$subPropertiesVat[$k][$l]          = $subPropertyVat;
				}
			}

			// FOR PROPERTY AND SUBPROPERTY PRICE CALCULATION
			$propertyPrices = $this->makeTotalPriceByOprand($productPrice, $propertiesOperator, $propertiesPriceWithVat);
			$productPrice   = $propertyPrices[1];

			$propertyOldPriceVats = $this->makeTotalPriceByOprand($productOldprice, $propertiesOperator, $propertiesPrice);
			$productOldprice      = $propertyOldPriceVats[1];

			for ($t = 0, $tn = count($properties); $t < $tn; $t++)
			{
				$selectedAttributs[$sel++] = $attributes[$i]['attribute_id'];

				if ($setPropEqual && $setSubpropEqual && isset($subPropertiesPriceWithVat[$t]))
				{
					$subPropertyPrices    = $this->makeTotalPriceByOprand($productPrice, $subPropertiesOperator[$t], $subPropertiesPriceWithVat[$t]);

					$productPrice     = $subPropertyPrices[1];

					$subPropertyOldPriceVats = $this->makeTotalPriceByOprand($productOldprice, $subPropertiesOperator[$t], $subPropertiesPrice[$t]);
					$productOldprice   = $subPropertyOldPriceVats[1];
				}
			}
		}

		$displayattribute = RedshopLayoutHelper::render(
			'product.product_attribute',
			array(
					'attributes'       => $attributes,
					'data'             => $data,
					'displayAttribute' => $displayAttribute
				),
			'',
			array(
					'component' => 'com_redshop',
					'client'    => 0
				)
		);

		$productVatOldPrice = 0;

		if ($productOldprice > 0)
		{
			$productVatOldPrice = $this->getProductTax($productId, $productOldprice, $userId);
		}

		// Recalculate VAT if set to apply vat for attribute
		if ($applyVat)
		{
			$productVatPrice = $this->getProducttax($productId, $productPrice, $userId);
		}

		// Todo: For QA to check all cases.
		/*if ($this->getApplyVatOrNot($data, $userId))
		{
			$productPrice += $productVatPrice;
		}*/

		$data = array(
			$displayattribute,
			$productPrice,
			$productVatPrice,
			$selectedAttributs,
			$isStock,
			$productOldprice,
			$productVatOldPrice,
			$isPreorderStock,
			$selectedProperty
		);

		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onMakeAttributeCart', array(&$data, $attributes, $productId));

		return $data;
	}

	public function makeAccessoryOrder($order_item_id = 0)
	{
		$order_functions  = order_functions::getInstance();
		$displayaccessory = "";
		$orderItemdata    = $order_functions->getOrderItemAccessoryDetail($order_item_id);

		if (count($orderItemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>"
				. JText::_("COM_REDSHOP_ACCESSORY") . "</div>";
			foreach ($orderItemdata as $data)
			{
				$accessory_quantity = " [" . JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " "
					. $data->product_quantity . "] ";
				$displayaccessory .= "<div class='checkout_accessory_title'>"
					. urldecode($data->order_acc_item_name)
					. " ("
					. $this->getProductFormattedPrice($data->order_acc_price + $data->order_acc_vat)
					. ")" . $accessory_quantity . "</div>";
				$makeAttributeOrder = $this->makeAttributeOrder($order_item_id, 1, $data->product_id);
				$displayaccessory   .= $makeAttributeOrder->product_attribute;
			}
		}
		else
		{
			$orderItemdata    = $order_functions->getOrderItemDetail(0, 0, $order_item_id);
			$displayaccessory = $orderItemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public function makeAttributeOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $stock = 0, $export = 0, $data = '')
	{
		$stockroomhelper   = rsstockroomhelper::getInstance();
		$order_functions   = order_functions::getInstance();
		$displayattribute  = "";
		$chktag            = $this->getApplyattributeVatOrNot($data);
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$orderItemdata     = $order_functions->getOrderItemDetail(0, 0, $order_item_id);
		$cartAttributes    = array();

		$products = $this->getProductById($orderItemdata[0]->product_id);

		if (count($orderItemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $orderItemdata[0]->product_attribute;
			$quantity          = $orderItemdata[0]->product_quantity;
			$stockroom_id      = $orderItemdata[0]->stockroom_id;
		}

		$orderItemAttdata = $order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		// Get Attribute middle template
		$attribute_middle_template = $this->getAttributeTemplateLoop($data);
		$attribute_final_template = '';

		if (count($orderItemAttdata) > 0)
		{
			for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
			{
				$attribute = $this->getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);

				// Assign Attribute middle template in tmp variable
				$tmp_attribute_middle_template = $attribute_middle_template;
				$tmp_attribute_middle_template = str_replace(
					"{product_attribute_name}", urldecode($orderItemAttdata[$i]->section_name), $tmp_attribute_middle_template
				);

				$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail(
					$order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id
				);

				// Initialize attribute calculated price
				$propertyCalculatedPriceSum = $orderItemdata[0]->product_item_old_price;

				for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
				{
					$property_price = $orderPropdata[$p]->section_price;
					$productAttributeCalculatedPrice = 0;

					if ($stock == 1)
					{
						$stockroomhelper->manageStockAmount($orderPropdata[$p]->section_id, $quantity, $orderPropdata[$p]->stockroom_id, "property");
					}

					$property = $this->getAttibuteProperty($orderPropdata[$p]->section_id);

					if (!empty($chktag))
					{
						$property_price = $orderPropdata[$p]->section_price + $orderPropdata[$p]->section_vat;
					}

					// Show actual productive price
					if ($export == 0 && $property_price > 0)
					{
						$propertyOperand                     = $orderPropdata[$p]->section_oprand;
						$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
							$propertyCalculatedPriceSum, $propertyOperand, $property_price
						);
						$productAttributeCalculatedPrice     = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
						$propertyCalculatedPriceSum          = $productAttributeCalculatedPriceBase;
					}

					$disPrice = '';
					$hideAttributePrice = count($attribute) > 0 ? $attribute[0]->hide_attribute_price : 0;

					if (strpos($data, '{product_attribute_price}') !== false)
					{
						if ($export == 1)
						{
							$disPrice = ' (' . $orderPropdata[$p]->section_oprand . Redshop::getConfig()->get('CURRENCY_SYMBOL') . $property_price . ')';
						}
						elseif (!$hideAttributePrice)
						{
							$disPrice = " (" . $orderPropdata[$p]->section_oprand . $this->getProductFormattedPrice($property_price) . ")";
						}
					}

					// Replace attribute property price and value
					$tmp_attribute_middle_template = str_replace("{product_attribute_value}", urldecode($orderPropdata[$p]->section_name), $tmp_attribute_middle_template);
					$tmp_attribute_middle_template = str_replace("{product_attribute_value_price}", $disPrice, $tmp_attribute_middle_template);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template .= $tmp_attribute_middle_template;

					// Initialize attribute child array
					$attributeChilds = array(
						'property_id' => $orderPropdata[$p]->section_id,
						'property_name' => $orderPropdata[$p]->section_name,
						'property_oprand' => $orderPropdata[$p]->section_oprand,
						'property_price' => $property_price,
						'property_childs' => array()
					);

					$orderSubpropdata = $order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

					for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
					{
						$subproperty_price = $orderSubpropdata[$sp]->section_price;

						if ($stock == 1)
						{
							$stockroomhelper->manageStockAmount($orderSubpropdata[$sp]->section_id, $quantity, $orderSubpropdata[$sp]->stockroom_id, "subproperty");
						}

						$subproperty = $this->getAttibuteSubProperty($orderSubpropdata[$sp]->section_id);

						if (!empty($chktag))
						{
							$subproperty_price = $orderSubpropdata[$sp]->section_price + $orderSubpropdata[$sp]->section_vat;
						}

						// Show actual productive price
						if ($export == 0 && $subproperty_price > 0)
						{
							$subPropertyOperand                  = $orderSubpropdata[$sp]->section_oprand;
							$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
							$propertyCalculatedPriceSum, $subPropertyOperand, $subproperty_price
							);
							$productAttributeCalculatedPrice     = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
							$propertyCalculatedPriceSum          = $productAttributeCalculatedPriceBase;
						}

						$attributeChilds['property_childs'][] = array(
							'subproperty_id'           => $orderSubpropdata[$sp]->section_id,
							'subproperty_name'         => $orderSubpropdata[$sp]->section_name,
							'subproperty_oprand'       => $orderSubpropdata[$sp]->section_oprand,
							'subattribute_color_title' => urldecode($subproperty[0]->subattribute_color_title),
							'subproperty_price'        => $subproperty_price
						);
					}

					// Format Calculated price using Language variable
					$productAttributeCalculatedPrice = $this->getProductFormattedPrice($productAttributeCalculatedPrice);
					$productAttributeCalculatedPrice = JText::sprintf('COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE', $productAttributeCalculatedPrice);
					$tmp_attribute_middle_template   = str_replace(
						"{product_attribute_calculated_price}",
						$productAttributeCalculatedPrice,
						$tmp_attribute_middle_template
					);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template = $tmp_attribute_middle_template;

					// Initialize attribute child array
					$attribute[0]->attribute_childs[] = $attributeChilds;
				}

				// Prepare cart type attribute array
				$cartAttributes[] = get_object_vars($attribute[0]);
			}

			$displayattribute = RedshopLayoutHelper::render(
				'product.order_attribute',
				array(
						'orderItemAttdata' => $orderItemAttdata,
						'data'             => $data,
						'orderItemId'      => $order_item_id,
						'isAccessory'      => $is_accessory,
						'chktag'           => $chktag,
						'export'           => $export
					),
				'',
				array(
						'component' => 'com_redshop',
						'client'    => 0
					)
			);
		}
		else
		{
			$displayattribute = $product_attribute;
		}

		if (isset($products->use_discount_calc) && $products->use_discount_calc == 1)
		{
			$displayattribute = $displayattribute . $orderItemdata[0]->discount_calc_data;
		}

		$data                                 = new stdClass;
		$data->product_attribute              = $displayattribute;
		$data->attribute_middle_template      = $attribute_final_template;
		$data->attribute_middle_template_core = $attribute_middle_template;
		$data->cart_attribute                 = $cartAttributes;

		return $data;
	}

	/**
	 * Method to get string between inputs
	 *
	 * @param   string  $start   Starting string where you need to start search
	 * @param   string  $end     Ending string where you need to end search
	 * @param   string  $string  Target string from where need to search
	 *
	 * @return  array           Matched string array
	 */
	function findStringBetween($start, $end, $string)
	{
		preg_match_all('/' . preg_quote($start, '/') . '([^\.)]+)' . preg_quote($end, '/') . '/i', $string, $m);

		return $m[1];
	}

	/**
	 * Method to get attribute template loop
	 *
	 * @param   string  $template  Attribute Template data
	 *
	 * @return  string             Template middle data
	 */
	public function getAttributeTemplateLoop($template)
	{
		$start   = "{product_attribute_loop_start}";
		$end     = "{product_attribute_loop_end}";
		$matches = $this->findStringBetween($start, $end, $template);

		$template_middle = '';

		if (count($matches) > 0)
		{
			$template_middle = $matches[0];
		}

		return $template_middle;
	}

	public function makeAccessoryQuotation($quotation_item_id = 0, $quotation_status = 2)
	{
		$quotationHelper  = quotationHelper::getInstance();
		$displayaccessory = "";
		$Itemdata         = $quotationHelper->getQuotationItemAccessoryDetail($quotation_item_id);

		if (count($Itemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>" . JText::_("COM_REDSHOP_ACCESSORY") . ":</div>";

			for ($i = 0, $in = count($Itemdata); $i < $in; $i++)
			{
				$displayaccessory .= "<div class='checkout_accessory_title'>" . urldecode($Itemdata[$i]->accessory_item_name) . " ";

				if ($quotation_status != 1 || ($quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == 1))
				{
					$displayaccessory .= "(" . $this->getProductFormattedPrice($Itemdata[$i]->accessory_price + $Itemdata[$i]->accessory_vat) . ")";
				}

				$displayaccessory .= "</div>";
				$displayaccessory .= $this->makeAttributeQuotation(
					$quotation_item_id,
					1,
					$Itemdata[$i]->accessory_id,
					$quotation_status
				);

			}
		}
		else
		{
			$Itemdata         = $quotationHelper->getQuotationProduct(0, $quotation_item_id);
			$displayaccessory = $Itemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public function makeAttributeQuotation($quotation_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quotation_status = 2, $stock = 0)
	{
		$quotationHelper  = quotationHelper::getInstance();
		$displayattribute = "";

		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$Itemdata          = $quotationHelper->getQuotationProduct(0, $quotation_item_id);

		if (count($Itemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $Itemdata[0]->product_attribute;
			$quantity          = $Itemdata[0]->product_quantity;
		}

		$ItemAttdata = $quotationHelper->getQuotationItemAttributeDetail(
			$quotation_item_id,
			$is_accessory,
			"attribute",
			$parent_section_id
		);

		$displayattribute = RedshopLayoutHelper::render(
			'product.quotation_attribute',
			array(
					'itemAttdata'     => $ItemAttdata,
					'quotationItemId' => $quotation_item_id,
					'isAccessory'     => $is_accessory,
					'quotationStatus' => $quotation_status,
					'parentSectionId' => $parent_section_id,
					'stock'           => $stock
				),
			'',
			array(
					'client'    => 0,
					'component' => 'com_redshop'
				)
		);

		return $displayattribute;
	}

	/*
	 * load Products Under categoriesd ACL Sopper Group
	 *
	 *  return : "," separated product string
	 */
	public function loadAclProducts()
	{
		$user    = JFactory::getUser();
		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($user->id);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];
		//$shopperGroupId = $this->_userhelper->getShopperGroup($user->id);

		if ($user->id > 0)
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = '" . $user->id . "' AND sg.shopper_group_portal=1 ";
		else
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = " . (int) $shopperGroupId . " AND sg.shopper_group_portal=1";

		$this->_db->setQuery($catquery);
		$category_ids_obj = $this->_db->loadObjectList();
		if(empty($category_ids_obj))
		{
			return "";
		}
		else
		{
			$category_ids = $category_ids_obj[0]->shopper_group_categories;
		}

		// Sanitize ids
		$catIds = explode(',', $category_ids);
		JArrayHelper::toInteger($catIds);

		$query = "SELECT product_id
						FROM `#__redshop_product_category_xref` WHERE category_id IN (" . implode(',', $catIds) . ")";

		$this->_db->setQuery($query);
		$shopperprodata = $this->_db->loadObjectList();
		$aclProduct     = array();

		for ($i = 0, $in = count($shopperprodata); $i < $in; $i++)
		{
			$aclProduct[] = $shopperprodata[$i]->product_id;
		}

		if (count($aclProduct) > 0)
			$aclProduct = implode(",", $aclProduct);
		else
			$aclProduct = "";

		return $aclProduct;
	}

	/*
	 * redSHOP Unit conversation
	 * @params: $globalUnit
	 * $params: $calcUnit
	 *
	 * $globalUnit: base conversation unit
	 * $calcUnit: Unit ratio which to convert
	 */

	public function getUnitConversation($globalUnit, $calcUnit)
	{
		/*
		 * calculation for setting unit value
		 */
		$unit = 1;
		switch ($calcUnit)
		{
			case "mm":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 1;
						break;

					case "cm":
						$unit = 0.1;
						break;

					case "m":
						$unit = 0.001;
						break;

					case "inch":
						$unit = 0.0393700787;
						break;
					case "feet":
						$unit = 0.0032808399;
						break;
				}

				break;

			case "cm":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 10;
						break;

					case "cm":
						$unit = 1;
						break;

					case "m":
						$unit = 0.01;
						break;

					case "inch":
						$unit = 0.393700787;
						break;
					case "feet":
						$unit = 0.032808399;
						break;
				}

				break;

			case "m":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 1000;
						break;

					case "cm":
						$unit = 100;
						break;

					case "m":
						$unit = 1;
						break;

					case "inch":
						$unit = 39.3700787;
						break;
					case "feet":
						$unit = 3.2808399;
						break;
				}

				break;

			case "inch":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 25.4;
						break;

					case "cm":
						$unit = 2.54;
						break;

					case "m":
						$unit = 0.0254;
						break;

					case "inch":
						$unit = 1;
						break;
					case "feet":
						$unit = 0.0833333333;
						break;
				}

				break;

			case "feet":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 304.8;
						break;

					case "cm":
						$unit = 30.48;
						break;

					case "m":
						$unit = 0.3048;
						break;

					case "inch":
						$unit = 12;
						break;
					case "feet":
						$unit = 1;
						break;
				}

				break;

			case "kg":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 2.20462262;
						break;

					case "gram":
						$unit = 1000;
						break;

					case "kg":
						$unit = 1;
						break;
				}

				break;

			case "pounds":
			case "lbs":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 1;
						break;

					case "gram":
						$unit = 453.59237;
						break;

					case "kg":
						$unit = 0.45359237;
						break;
				}

				break;

			case "gram":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 0.00220462262;
						break;

					case "gram":
						$unit = 1;
						break;

					case "kg":
						$unit = 0.001;
						break;
				}

				break;

		}

		return $unit;
	}

	// Get Product subscription price
	public function getProductSubscriptionDetail($product_id, $subscription_id)
	{
		$query = "SELECT * "
			. " FROM " . $this->_table_prefix . "product_subscription"
			. " WHERE "
			. " product_id = " . (int) $product_id . " AND subscription_id = " . (int) $subscription_id;
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	// Get User Product subscription detail
	public function getUserProductSubscriptionDetail($order_item_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_subscribe_detail AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_subscription AS ps ON ps.subscription_id=p.subscription_id "
			. "WHERE order_item_id = " . (int) $order_item_id;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function insertProductDownload($product_id, $user_id, $order_id, $media_name, $serial_number)
	{
		$db = JFactory::getDbo();

		// download data
		$downloadable_product = RedshopHelperProductDownload::checkDownload($product_id, true); //die();

		$product_download_limit = ($downloadable_product->product_download_limit > 0) ? $downloadable_product->product_download_limit : Redshop::getConfig()->get('PRODUCT_DOWNLOAD_LIMIT');

		$product_download_days      = ($downloadable_product->product_download_days > 0) ? $downloadable_product->product_download_days : Redshop::getConfig()->get('PRODUCT_DOWNLOAD_DAYS');
		$product_download_clock     = ($downloadable_product->product_download_clock > 0) ? $downloadable_product->product_download_clock : 0;
		$product_download_clock_min = ($downloadable_product->product_download_clock_min > 0) ? $downloadable_product->product_download_clock_min : 0;

		$product_download_days = (date("H") > $product_download_clock && $product_download_days == 0) ? 1 : $product_download_days;

		$product_download_days_time = (time() + ($product_download_days * 24 * 60 * 60));

		$endtime = mktime($product_download_clock, $product_download_clock_min, 0, date("m", $product_download_days_time), date("d", $product_download_days_time), date("Y", $product_download_days_time));

		// if download product is set to infinit
		$endtime = ($downloadable_product->product_download_infinite == 1) ? 0 : $endtime;

		// Generate Download Token
		$token = md5(uniqid(mt_rand(), true));

		$sql = "INSERT INTO " . $this->_table_prefix . "product_download "
			. "(product_id,user_id,order_id, end_date, download_max, download_id, file_name,product_serial_number) "
			. "VALUES(" . (int) $product_id . ", " . (int) $user_id . ", " . (int) $order_id . ", "
			. (int) $endtime . ", " . (int) $product_download_limit . ", "
			. $db->quote($token) . ", " . $db->quote($media_name) . "," . $db->quote($serial_number) . ")";
		$this->_db->setQuery($sql);
		$this->_db->execute();

		return true;
	}

	/*
	 *  Get serial number for downloadable product only retrive one number.
	 */

	public function getProdcutSerialNumber($product_id, $is_used = 0)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_serial_number "
			. "WHERE product_id = " . (int) $product_id . " "
			. " AND is_used = " . (int) $is_used . " "
			. " LIMIT 0,1";
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObject();

		if (count($rs) > 0)
		{
			// Update serial number...
			$this->updateProdcutSerialNumber($rs->serial_id);
		}
		else
		{
			$rs->serial_number = "";
		}

		return $rs;
	}

	/*
	 *  Update used seraial number status
	 */
	public function updateProdcutSerialNumber($serial_id)
	{
		$update_query = "UPDATE " . $this->_table_prefix . "product_serial_number "
			. " SET is_used='1' WHERE serial_id = " . (int) $serial_id;
		$this->_db->setQuery($update_query);
		$this->_db->execute();
	}

	public function getSubscription($product_id = 0)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_subscription "
			. "WHERE product_id = " . (int) $product_id . " "
			. "ORDER BY subscription_id ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Function Get Question Answers
	 *
	 * @param   int  $questionId  default 0
	 * @param   int  $productId   default 0
	 * @param   int  $faq         is FAQ
	 * @param   int  $front       show in Front or Not
	 *
	 * @return  array
	 */
	public function getQuestionAnswer($questionId = 0, $productId = 0, $faq = 0, $front = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$and = "";

		if ($questionId != 0)
		{
			if ($faq != 0)
			{
				$and .= " AND " . $db->qn('q.parent_id') . " = " . $db->q($questionId);
			}
			else
			{
				$and .= " AND " . $db->qn('q.id') . " = " . $db->q($questionId);
			}
		}
		else
		{
			$and .= " AND " . $db->qn('q.product_id') . " = " . $db->q($productId) . " AND " . $db->q('q.parent_id') . " = 0 ";
		}

		if ($front != 0)
		{
			$and .= " AND q.published='1' ";
		}

		// Avoid db killing
		if (!empty($and))
		{
			$query->select(
				$db->qn(
					[
						'q.id', 'q.parent_id', 'q.product_id',
						'q.question', 'q.user_id', 'q.user_name',
						'q.user_email', 'q.published', 'q.question_date',
						'q.ordering', 'q.telephone', 'q.address'
					]
				)
			)
				->from($db->qn('#__redshop_customer_question', 'q'))
				->where($db->qn('q.id') . ' > 0 ' . $and)
				->order($db->qn('q.ordering'));

			$db->setQuery($query);

			return $db->loadObjectList();
		}

		return null;
	}

	/**
	 * Get Product Rating
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return string
	 */
	public function getProductRating($productId)
	{
		$finalAvgReviewData = '';

		if ($productData = $this->getProductById($productId))
		{
			$avgRating = 0;

			if ($productData->count_rating > 0)
			{
				$avgRating = round($productData->sum_rating / $productData->count_rating);
			}

			if ($avgRating > 0)
			{
				$finalAvgReviewData = RedshopLayoutHelper::render(
					'product.rating',
					array(
						'avgRating' => $avgRating,
						'countRating' => $productData->count_rating
					)
				);
			}
		}

		return $finalAvgReviewData;
	}

	/**
	 * Get Product Review List
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return object
	 */
	public function getProductReviewList($productId)
	{
		// Initialize variables.
		$db = JFactory::getDbo();

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select('pr.*')
			->select($db->qn('ui.firstname'))
			->select($db->qn('ui.lastname'))
			->from($db->qn('#__redshop_product_rating', 'pr'))
			->leftjoin(
				$db->qn('#__redshop_users_info', 'ui')
				. ' ON '
				. $db->qn('ui.user_id') . '=' . $db->qn('pr.userid')
				. ' AND ' . $db->qn('ui.address_type') . '=' . $db->q('BT')
			)
			->where($db->qn('pr.product_id') . ' = ' . (int) $productId)
			->where($db->qn('pr.published') . ' = 1')
			->where($db->qn('pr.email') . ' != ' . $db->q(''))
			->order($db->qn('pr.favoured') . ' DESC')
			->group($db->qn('pr.rating_id'));

		try
		{
			$reviews = $db->setQuery($query)->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $reviews;
	}

	/**
	 * Method for calculate two price with oprand symbol
	 *
	 * @param   float   $firstPrice   First price
	 * @param   string  $oprand       Operation symbol
	 * @param   float   $secondPrice  Second price
	 *
	 * @return  float
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function calOprandPrice($firstPrice, $oprand, $secondPrice)
	{
		switch ($oprand)
		{
			case "+":
				return $firstPrice + $secondPrice;

			case "-":
				return $firstPrice - $secondPrice;

			case "*":
				return $firstPrice * $secondPrice;

			case "/":
				return $firstPrice / $secondPrice;

			case "=":
				return $secondPrice;

			default:
				return $firstPrice;
		}
	}

	public function makeCompareProductDiv()
	{
		$input           = JFactory::getApplication()->input;
		$Itemid          = $input->get('Itemid');
		$cmd             = $input->get('cmd');
		$compare_product = $this->_session->get('compare_product');

		if (!$compare_product)
		{
			return;
		}

		$div    = "<ul id='compare_ul'>";
		$moddiv = '`<table border="0" cellpadding="5" cellspacing="0" width="100%">';
		$idx    = (int) ($compare_product['idx']);

		for ($i = 0; $i < $idx; $i++)
		{
			$product    = $this->getProductById($compare_product[$i]["product_id"]);
			$product_id = $compare_product[$i]["product_id"];

			$category_id = $compare_product[$i]["category_id"];

			$product_link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&Itemid='
				. $Itemid);

			$div .= "<li>" . $product->product_name . " <a onClick='javascript:add_to_compare(" . $product_id
				. "," . $category_id . ",\"remove\")' href='javascript:void(0)'>" . JText::_('COM_REDSHOP_DELETE')
				. "</a></li>";
			$moddiv .= '<tr valign="top"><td width="95%"><span><a href="' . $product_link . '">' . $product->product_name
				. '</a></span></td>';
			$moddiv .= '<td width="5%"><span><a href="javascript:void(0);" onClick="javascript:remove_compare('
				. $product_id . ',' . $category_id . ')">' . JText::_('COM_REDSHOP_DELETE') . '</a></span></td></tr>';
		}

		$moddiv .= "</table>";

		/* if function called directly than don't include module div */
		if ($cmd == "")
			$moddiv = "";

		$div .= "</ul><div id='totalCompareProduct' style='display:none;' >" . $idx . "</div>" . $moddiv;

		return $div;
	}

	/*
	 * function which will return product tag array form  given template
	 *
	 */
	public function product_tag($template_id, $section, $template_data)
	{
		$db = JFactory::getDbo();

		$q = "SELECT name from " . $this->_table_prefix . "fields where section = " . $db->quote($section);

		$this->_db->setQuery($q);

		$fields = $this->_db->loadColumn();

		$tmp1 = explode("{", $template_data);

		$str = array();

		for ($h = 0, $hn = count($tmp1); $h < $hn; $h++)
		{
			$word = explode("}", $tmp1[$h]);

			if (in_array($word[0], $fields))
				$str[] = $word[0];
		}

		return $str;
	}

	public function getJcommentEditor($product = array(), $data_add = "")
	{
		$app       = JFactory::getApplication();
		$product_reviews = "";
		$product_id      = $product->product_id;

		if ($product_id && strpos($data_add, "{jcomments off}") === false && strpos($data_add, "{jcomments on}") !== false)
		{
			$comments = $app->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';

			if (file_exists($comments))
			{
				require_once $comments;
				$product_reviews = JComments::showComments($product_id, 'com_redshop', $product->product_name);
			}

			$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		}

		$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		$data_add = str_replace("{jcomments off}", "", $data_add);

		return $data_add;
	}

	public function getSelectedAccessoryArray($data = array())
	{
		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectedProperty     = array();
		$selectedSubproperty  = array();

		if (!empty($data['accessory_data']))
		{
			$accessoryData    = explode("@@", $data['accessory_data']);
			$accQuantityData = explode("@@", $data['acc_quantity_data']);

			for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
			{
				if (empty($accessoryData[$i]))
				{
					continue;
				}

				$selectedAccessory[]    = $accessoryData[$i];
				$selectedAccessoryQua[] = $accQuantityData[$i];
			}
		}

		if (!empty($data['acc_property_data']))
		{
			$accessoryPropertyData = explode('@@', $data['acc_property_data']);

			for ($i = 0, $in = count($accessoryPropertyData); $i < $in; $i++)
			{
				$accessoryPropertyData1 = explode('##', $accessoryPropertyData[$i]);
				$countAccessoryProperty = count($accessoryPropertyData1);

				if ($countAccessoryProperty == 0)
				{
					continue;
				}

				for ($ia = 0; $ia < $countAccessoryProperty; $ia++)
				{
					$accessoryPropertyData2 = explode(',,', $accessoryPropertyData1[$ia]);
					$countAccessoryProperty2 = count($accessoryPropertyData2);

					if ($countAccessoryProperty2 == 0)
					{
						continue;
					}

					for ($ip = 0; $ip < $countAccessoryProperty2; $ip++)
					{
						if ($accessoryPropertyData2[$ip] == "")
						{
							continue;
						}

						$selectedProperty[] = $accessoryPropertyData2[$ip];
					}
				}
			}
		}

		if (!empty($data['acc_subproperty_data']))
		{
			$accessorySubpropertyData = explode('@@', $data['acc_subproperty_data']);

			for ($i = 0, $in = count($accessorySubpropertyData); $i < $in; $i++)
			{
				$accessorySubpropertyData1 = explode('##', $accessorySubpropertyData[$i]);
				$countAccessorySubroperty = count($accessorySubpropertyData1);

				if ($countAccessorySubroperty == 0)
				{
					continue;
				}

				for ($ia = 0; $ia < $countAccessorySubroperty; $ia++)
				{
					$accessorySubpropertyData2 = explode(',,', $accessorySubpropertyData1[$ia]);
					$countAccessorySubroperty2 = count($accessorySubpropertyData2);

					if ($countAccessorySubroperty2 == 0)
					{
						continue;
					}

					for ($ip = 0; $ip < $countAccessorySubroperty2; $ip++)
					{
						$accessorySubpropertyData3 = explode('::', $accessorySubpropertyData2[$ip]);
						$countAccessorySubroperty3 = count($accessorySubpropertyData3);

						if ($countAccessorySubroperty3 == 0)
						{
							continue;
						}

						for ($isp = 0; $isp < $countAccessorySubroperty3; $isp++)
						{
							if ($accessorySubpropertyData3[$isp] == "")
							{
								continue;
							}

							$selectedSubproperty[] = $accessorySubpropertyData3[$isp];
						}
					}
				}
			}
		}

		return array($selectedAccessory, $selectedProperty, $selectedSubproperty, $selectedAccessoryQua);
	}

	public function getSelectedAttributeArray($data = array())
	{
		$selectedProperty    = array();
		$selectedsubproperty = array();

		if (!empty($data['property_data']))
		{
			$acc_property_data = explode('##', $data['property_data']);

			for ($ia = 0, $countProperty = count($acc_property_data); $ia < $countProperty; $ia++)
			{
				$acc_property_data1 = explode(',,', $acc_property_data[$ia]);
				$countProperty1 = count($acc_property_data1);

				for ($ip = 0; $ip < $countProperty1; $ip++)
				{
					if ($acc_property_data1[$ip] != "")
					{
						$selectedProperty[] = $acc_property_data1[$ip];
					}
				}
			}
		}

		if (!empty($data['subproperty_data']))
		{
			$acc_subproperty_data = explode('##', $data['subproperty_data']);
			$countSubproperty = count($acc_subproperty_data);

			for ($ia = 0; $ia < $countSubproperty; $ia++)
			{
				$acc_subproperty_data1 = @explode('::', $acc_subproperty_data[$ia]);
				$countSubproperty1 = count($acc_subproperty_data1);

				for ($ip = 0; $ip < $countSubproperty1; $ip++)
				{
					$acc_subproperty_data2 = explode(',,', $acc_subproperty_data1[$ip]);
					$countSubproperty2 = count($acc_subproperty_data2);

					for ($isp = 0; $isp < $countSubproperty2; $isp++)
					{
						if ($acc_subproperty_data2[$isp] != "")
						{
							$selectedsubproperty[] = $acc_subproperty_data2[$isp];
						}
					}
				}
			}
		}

		$ret = array($selectedProperty, $selectedsubproperty);

		return $ret;
	}

	public function replaceProductInStock($product_id = 0, $data_add, $attributes = array(), $attribute_template = array())
	{
		if (count($attribute_template) <= 0)
		{
			$attributes = array();
		}

		$stock_status_flag     = false;
		$totalatt              = count($attributes);
		$Id                    = $product_id;
		$sec                   = "product";
		$selectedPropertyId    = 0;
		$selectedsubpropertyId = 0;

		for ($a = 0, $an = count($attributes); $a < $an; $a++)
		{
			$selectedId = array();
			$property   = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id, $product_id);

			if ($attributes[$a]->text != "" && count($property) > 0)
			{
				for ($i = 0, $in = count($property); $i < $in; $i++)
				{
					if ($property[$i]->setdefault_selected)
					{
						$selectedId[] = $property[$i]->property_id;
					}
				}

				if (count($selectedId) > 0)
				{
					if ($attributes[$a]->allow_multiple_selection)
					{
						$selectedPropertyId = implode(",", $selectedId);
					}
					else
					{
						$selectedPropertyId = $selectedId[count($selectedId) - 1];
					}

					$Id  = $selectedPropertyId;
					$sec = "property";
				}

				if (count($selectedId) > 0)
				{
					$stock_status_flag = true;
					$i                 = count($selectedId) - 1;
					$subproperty       = $this->getAttibuteSubProperty(0, $selectedId[$i]);
					$selectedId        = array();

					for ($sp = 0, $countSubproperty = count($subproperty); $sp < $countSubproperty; $sp++)
					{
						if ($subproperty[$sp]->setdefault_selected)
						{
							$selectedId[] = $subproperty[$sp]->subattribute_color_id;
						}
					}

					if (count($selectedId) > 0)
					{
						if ($subproperty[0]->setmulti_selected)
						{
							$selectedsubpropertyId = implode(",", $selectedId);
						}
						else
						{
							$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
						}

						$Id  = $selectedsubpropertyId;
						$sec = "subproperty";
					}
				}
			}
		}

		$stockroomhelper = rsstockroomhelper::getInstance();
		$productinstock  = $stockroomhelper->getStockAmountwithReserve($Id, $sec);

		if ($productinstock == 0)
		{
			$product_detail   = $this->getProductById($product_id);
			$product_preorder = $product_detail->preorder;

			if (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				|| ($product_preorder == "yes")
				|| ($product_preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				$productinpreorderstock = $stockroomhelper->getPreorderStockAmountwithReserve($Id, $sec);
			}
		}

		if (strpos($data_add, "{products_in_stock}") !== false)
		{
			$data_add = str_replace("{products_in_stock}", JText::_('COM_REDSHOP_PRODUCT_IN_STOCK_LBL')
				. ' <span id="displayProductInStock' . $product_id . '">' . $productinstock . '</span>', $data_add);
		}

		if (strpos($data_add, "{product_stock_amount_image}") !== false)
		{
			$stockamountList  = $stockroomhelper->getStockAmountImage($Id, $sec, $productinstock);
			$stockamountImage = "";

			if (count($stockamountList) > 0)
			{
				$stockamountImage = RedshopLayoutHelper::render(
					'product.stock_amount_image',
					array(
						'product_id' => $product_id,
						'stockamountImage' => $stockamountList[0]
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);
			}

			$data_add = str_replace("{product_stock_amount_image}", $stockamountImage, $data_add);
		}

		return $data_add;
	}

	/*
	 * function to check product is parent
	 * or it has childs
	 *
	 * @return: integer
	 */

	public function getChildProduct($product_id = 0)
	{
		$query = "SELECT product_parent_id,product_id,product_name,product_number FROM " . $this->_table_prefix
			. "product "
			. "WHERE product_parent_id = " . (int) $product_id . " AND published = 1 ORDER BY product_id";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/*
	 * function to get products parent id
	 *
	 * @return: int
	 */
	public function getMainParentProduct($parent_id)
	{
		$query = "SELECT product_parent_id FROM " . $this->_table_prefix . "product "
			. "WHERE published=1 "
			. "AND product_id = " . (int) $parent_id;
		$this->_db->setQuery($query);
		$product_parent_id = $this->_db->loadResult();

		if ($product_parent_id != 0)
		{
			$parent_id = $this->getMainParentProduct($product_parent_id);
		}

		return $parent_id;
	}

	/**
	 * Get formatted number
	 *
	 * @param   float    $price          Price amount
	 * @param   boolean  $convertSigned  True for convert negative price to absolution price.
	 *
	 * @return  string                   Formatted price.
	 */
	public function redpriceDecimal($price, $convertSigned = true)
	{
		$price = ($convertSigned == true) ? abs($price) : $price;

		return number_format($price, Redshop::getConfig()->get('PRICE_DECIMAL'), '.', '');
	}

	public function redunitDecimal($price)
	{
		if (Redshop::getConfig()->get('UNIT_DECIMAL') != "")
		{
			return number_format($price, Redshop::getConfig()->get('UNIT_DECIMAL'), '.', '');
		}

		return $price;
	}

	public function isProductDateRange($userfieldArr, $product_id)
	{
		$isEnable = true;

		if (count($userfieldArr) <= 0)
		{
			$isEnable = false;

			return $isEnable;
		}

		if (!array_key_exists('15', self::$productDateRange))
		{
			$query = $this->_db->getQuery(true)
				->select('name, id')
				->from($this->_db->qn('#__redshop_fields'))
				->where('type = 15');
			$this->_db->setQuery($query);
			self::$productDateRange['15'] = $this->_db->loadObject();
		}

		$fieldData = self::$productDateRange['15'];

		if (!$fieldData)
		{
			$isEnable = false;

			return $isEnable;
		}

		$field_name = $fieldData->name;

		if (is_array($userfieldArr))
		{
			if (in_array($field_name, $userfieldArr))
			{
				$field_id  = $fieldData->id;
				$dateQuery = "select data_txt from " . $this->_table_prefix . "fields_data where fieldid = " . (int) $field_id . " AND itemid = " . (int) $product_id;
				$this->_db->setQuery($dateQuery);
				$datedata = $this->_db->loadObject();

				if (count($datedata) > 0)
				{
					$data_txt             = $datedata->data_txt;
					$mainsplit_date_total = preg_split(" ", $data_txt);
					$mainsplit_date       = preg_split(":", $mainsplit_date_total[0]);

					$dateStart  = mktime(
						0,
						0,
						0,
						date('m', $mainsplit_date[0]),
						date('d', $mainsplit_date[0]),
						date('Y', $mainsplit_date[0])
					);
					$dateEnd    = mktime(
						23,
						59,
						59,
						date('m', $mainsplit_date[1]),
						date('d', $mainsplit_date[1]),
						date('Y', $mainsplit_date[1])
					);
					$todayStart = mktime(
						0,
						0,
						0,
						date('m'),
						date('d'),
						date('Y')
					);
					$todayEnd   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

					if ($dateStart <= $todayStart && $dateEnd >= $todayEnd)
					{
						// Show add to cart button
						$isEnable = false;
					}
				}
				else
				{
					// Show add to cart button
					$isEnable = false;
				}
			}
			else
			{
				// Show add to cart button
				$isEnable = false;
			}
		}
		else
		{
			// Show add to cart button
			$isEnable = false;
		}

		return $isEnable;
	}

	public function getassociatetag($product_id = 0)
	{
		$query = " SELECT a.product_id,at.tag_id,rg.tag_name,ty.type_name FROM  #__redproductfinder_associations as a left outer join #__redproductfinder_association_tag as at on a.id=at.association_id left outer join #__redproductfinder_tags as rg on at.tag_id=rg.id left outer join #__redproductfinder_types as ty on at.type_id=ty.id where a.product_id='" . $product_id . "' ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectlist();

		return $res;
	}

	public function getCategoryCompareTemplate($cid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('t.template_id'))
			->from($db->qn('#__redshop_template', 't'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('t.template_id') . ' = ' . $db->qn('c.compare_template_id'))
			->where($db->qn('c.id') . ' = ' . $db->q((int) $cid))
			->where($db->qn('t.published') . ' = 1');

		$tmpName = $db->setQuery($query)->loadResult();

		if ($tmpName == '')
		{
			$tmpName = Redshop::getConfig()->get('COMPARE_TEMPLATE_ID');
		}

		return $tmpName;
	}

	public function getProductCaterories($productId, $displayLink = 0)
	{
		$prodCatsObjectArray = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.name'))
			->select($db->qn('c.id'))
			->from($db->qn('#__redshop_category'))
			->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $productId))
			->where($db->qn('c.published') . ' = 1');

		$rows = $db->setQuery($query)->loadObjectList();

		for ($i = 0, $in = count($rows); $i < $in; $i++)
		{
			$ppCat = $pCat = '';
			$row   = $rows[$i];

			$query = $db->getQuery(true)
				->select($db->qn('parent_id'))
				->select($db->qn('name'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('id') . ' = ' . $db->q((int) $row->id));

			$parentCat = $db->setQuery($query)->loadObject();

			if (count($parentCat) > 0 && $parentCat->parent_id)
			{
				$pCat  = $parentCat->name;

				$query = $db->getQuery(true)
					->select($db->qn('parent_id'))
					->select($db->qn('name'))
					->from($db->qn('#__redshop_category'))
					->where($db->qn('id') . ' = ' . $db->q((int) $parentCat->parent_id));

				$pparentCat = $db->setQuery($query)->loadObject();

				if (count($pparentCat) > 0 && $pparentCat->parent_id)
				{
					$ppCat = $pparentCat->name;
				}
			}

			$spacediv  = (isset($pCat) && $pCat) ? " > " : "";
			$pspacediv = (isset($ppCat) && $ppCat) ? " > " : "";
			$catlink   = '';

			if ($displayLink)
			{
				$redhelper = redhelper::getInstance();
				$catItem   = RedshopHelperUtility::getCategoryItemid($row->id);

				if(!(boolean) $catItem)
				{
					$catItem = JFactory::getApplication()->input->getInt('Itemid');
				}

				$catlink   = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
					. $row->id . '&Itemid=' . $catItem);
			}

			$prodCatsObject        = new stdClass;
			$prodCatsObject->name  = $ppCat . $pspacediv . $pCat . $spacediv . $row->name;
			$prodCatsObject->link  = $catlink;
			$prodCatsObjectArray[] = $prodCatsObject;
		}

		return $prodCatsObjectArray;
	}

	public function getdisplaymainImage($product_id = 0, $property_id = 0, $subproperty_id = 0, $pw_thumb = 0, $ph_thumb = 0, $redview = "")
	{
		$url                 = JURI::base();
		$product             = $this->getProductById($product_id);
		$redhelper           = redhelper::getInstance();
		$aHrefImageResponse  = '';
		$imagename           = '';
		$aTitleImageResponse = '';
		$mainImageResponse   = '';
		$productmainimg      = '';
		$Arrreturn           = array();
		$product             = $this->getProductById($product_id);
		$type                = '';
		$pr_number           = $product->product_number;
		$attrbimg            = '';
		//$refererpath=explode("view=",$_SERVER['HTTP_REFERER']);
		//$getview=explode("&",$refererpath[1]);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_thumb_image))
		{
			$type                = 'product';
			$imagename           = $product->product_thumb_image;
			$aTitleImageResponse = $product->product_name;
			$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
		}
		elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
		{
			$altText = $product->product_name;
			$dispatcher    = RedshopHelperUtility::getDispatcher();
			$dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

			$type                = 'product';
			$imagename           = $product->product_full_image;
			$aTitleImageResponse = $altText;
			$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
		}
		else
		{
			if (Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE') && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
			{
				$type                = 'product';
				$imagename           = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				$aTitleImageResponse = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
			}
		}

		if ($property_id > 0)
		{
			$property  = $this->getAttibuteProperty($property_id);
			$pr_number = $property[0]->property_number;

			if (count($property) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "property/"
					. $property[0]->property_main_image))
			{
				$type                = 'property';
				$imagename           = $property[0]->property_main_image;
				$aTitleImageResponse = $property[0]->text;
			}
			//Display attribute image in cart
			if (count($property) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/"
					. $property[0]->property_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $property[0]->property_image;
			}
		}

		if ($subproperty_id > 0)
		{
			$subproperty = $this->getAttibuteSubProperty($subproperty_id);
			$pr_number   = $subproperty[0]->subattribute_color_number;
			//Display Sub-Property Number
			if (count($subproperty) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/"
					. $subproperty[0]->subattribute_color_main_image))
			{
				$type                = 'subproperty';
				$imagename           = $subproperty[0]->subattribute_color_main_image;
				$aTitleImageResponse = $subproperty[0]->text;
				//$attrbimg=REDSHOP_FRONT_IMAGES_ABSPATH."subproperty/".$subproperty[0]->subattribute_color_image;
			}
			//Subproperty image in cart
			if (count($subproperty) > 0 && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/"
					. $subproperty[0]->subattribute_color_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "subcolor/" . $subproperty[0]->subattribute_color_image;
			}

		}

		if (!empty($imagename) && !empty($type))
		{
			if ((Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE')) && $type == 'product')
			{
				$productmainimg = RedshopHelperMedia::watermark('product', $imagename, $pw_thumb, $ph_thumb, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0');
			}
			else
			{
				$productmainimg = RedShopHelperImages::getImagePath(
					$imagename,
					'',
					'thumb',
					$type,
					$pw_thumb,
					$ph_thumb,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			if ((Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE')) && $type == 'product')
			{
				$aHrefImageResponse = RedshopHelperMedia::watermark('product', $imagename, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
			}
			else
			{
				$aHrefImageResponse = REDSHOP_FRONT_IMAGES_ABSPATH . $type . "/" . $imagename;
			}

			$altText = $product->product_name;

			$dispatcher    = RedshopHelperUtility::getDispatcher();
			$dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

			$mainImageResponse = "<img id='main_image" . $product_id . "' src='" . $productmainimg . "' alt='"
				. $altText . "' title='" . $altText . "'>";

			if ((!Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX') || !Redshop::getConfig()->get('PRODUCT_DETAIL_IS_LIGHTBOX')) || $redview == "category")
				$mainImageResponse = $productmainimg;
		}

		$Arrreturn['aHrefImageResponse'] = $aHrefImageResponse;
		$Arrreturn['mainImageResponse']  = $mainImageResponse;
		$Arrreturn['productmainimg']     = $productmainimg;

		$Arrreturn['aTitleImageResponse'] = $aTitleImageResponse;
		$Arrreturn['imagename']           = $imagename;
		$Arrreturn['type']                = $type;
		$Arrreturn['attrbimg']            = $attrbimg;
		$Arrreturn['pr_number']           = $pr_number;

		return $Arrreturn;
	}

	/**
	 * Method for get additional images of product.
	 *
	 * @param   integer  $productId         Id of product
	 * @param   integer  $accessoryId       Accessory Id
	 * @param   integer  $relatedProductId  Related product ID
	 * @param   integer  $propertyId        Property ID
	 * @param   integer  $subPropertyId     Sub-property ID
	 * @param   integer  $mainImgWidth      Main image width
	 * @param   integer  $mainImgHeight     Main image height
	 * @param   string   $redView           redshop View
	 * @param   string   $redLayout         redshop layout
	 *
	 * @return  array
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 *
	 * @see  RedshopHelperProductTag::displayAdditionalImage
	 */
	public function displayAdditionalImage(
		$productId = 0, $accessoryId = 0, $relatedProductId = 0, $propertyId = 0, $subPropertyId = 0, $mainImgWidth = 0,
		$mainImgHeight = 0, $redView = "", $redLayout = ""
	)
	{
		return RedshopHelperProductTag::displayAdditionalImage(
			$productId, $accessoryId, $relatedProductId, $propertyId, $subPropertyId, $mainImgWidth, $mainImgHeight, $redView, $redLayout
		);
	}

	public function getProductFinderDatepickerValue($templatedata = "", $productid = 0, $fieldArray = array(), $giftcard = 0)
	{
		$extraField = extraField::getInstance();

		if (count($fieldArray) > 0)
		{
			for ($i = 0, $in = count($fieldArray); $i < $in; $i++)
			{
				$fieldValueArray = $extraField->getSectionFieldDataList($fieldArray[$i]->id, 17, $productid);

				if ($fieldValueArray->data_txt != ""
					&& $fieldArray[$i]->show_in_front == 1
					&& $fieldArray[$i]->published == 1
					&& $giftcard == 0)
				{
					$templatedata = str_replace('{' . $fieldArray[$i]->name . '}', $fieldValueArray->data_txt, $templatedata);
					$templatedata = str_replace('{' . $fieldArray[$i]->name . '_lbl}', $fieldArray[$i]->title, $templatedata);
				}
				else
				{
					$templatedata = str_replace('{' . $fieldArray[$i]->name . '}', "", $templatedata);
					$templatedata = str_replace('{' . $fieldArray[$i]->name . '_lbl}', "", $templatedata);
				}
			}
		}

		return $templatedata;
	}

	/**
	 * Parse related product template
	 *
	 * @param   string   $template_desc  Template Contents
	 * @param   integer  $product_id     Product Id
	 *
	 * @todo    Move this functionality to library helper and convert this code into JLayout
	 *
	 * @return  string   Parsed Template HTML
	 */
	public function getRelatedtemplateView($template_desc, $product_id)
	{
		$extra_field      = extraField::getInstance();
		$config           = Redconfiguration::getInstance();
		$redTemplate      = Redtemplate::getInstance();
		$redhelper        = redhelper::getInstance();
		$related_product  = $this->getRelatedProduct($product_id);
		$related_template = $this->getRelatedProductTemplate($template_desc);
		$fieldArray       = $extra_field->getSectionFieldList(17, 0, 0);

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		if (count($related_template) > 0)
		{
			if (count($related_product) > 0
				&& strpos($related_template->template_desc, "{related_product_start}") !== false
				&& strpos($related_template->template_desc, "{related_product_end}") !== false)
			{
				$related_template_data = '';
				$product_start         = explode("{related_product_start}", $related_template->template_desc);
				$product_end           = explode("{related_product_end}", $product_start [1]);

				$tempdata_div_start  = $product_start [0];
				$tempdata_div_middle = $product_end [0];
				$tempdata_div_end    = $product_end [1];

				$attribute_template = $this->getAttributeTemplate($tempdata_div_middle);

				// Extra field display
				$extraFieldName = $extra_field->getSectionFieldNameArray(1, 1, 1);

				for ($r = 0, $rn = count($related_product); $r < $rn; $r++)
				{
					$related_template_data .= $tempdata_div_middle;

					$dispatcher->trigger('onPrepareRelatedProduct', array(&$related_template_data, $related_product[$r]));

					$ItemData = $this->getMenuInformation(0, 0, '', 'product&pid=' . $related_product[$r]->product_id);

					if (count($ItemData) > 0)
					{
						$pItemid = $ItemData->id;
					}
					else
					{
						$pItemid = RedshopHelperUtility::getItemId($related_product[$r]->product_id);
					}

					$rlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $related_product[$r]->product_id . '&cid=' . $related_product[$r]->cat_in_sefurl . '&Itemid=' . $pItemid);

					if (strpos($related_template_data, "{relproduct_image_3}") !== false)
					{
						$rpimg_tag = '{relproduct_image_3}';
						$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_3');
						$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_3');
					}
					elseif (strpos($related_template_data, "{relproduct_image_2}") !== false)
					{
						$rpimg_tag = '{relproduct_image_2}';
						$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_2');
						$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_2');
					}
					elseif (strpos($related_template_data, "{relproduct_image_1}") !== false)
					{
						$rpimg_tag = '{relproduct_image_1}';
						$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
						$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
					}
					else
					{
						$rpimg_tag = '{relproduct_image}';
						$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
						$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
					}

					$hidden_thumb_image    = "<input type='hidden' name='rel_main_imgwidth' id='rel_main_imgwidth' value='"
						. $rpw_thumb . "'><input type='hidden' name='rel_main_imgheight' id='rel_main_imgheight' value='"
						. $rph_thumb . "'>";
					$relimage              = $this->getProductImage($related_product [$r]->product_id, $rlink, $rpw_thumb, $rph_thumb);
					$related_template_data = str_replace($rpimg_tag, $relimage . $hidden_thumb_image, $related_template_data);

					if (strpos($related_template_data, "{relproduct_link}") !== false)
					{
						$rpname = "<a href='" . $rlink . "' title='" . $related_product [$r]->product_name . "'>"
							. $config->maxchar($related_product [$r]->product_name, Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX'))
							. "</a>";
					}
					else
					{
						$rpname = $config->maxchar($related_product [$r]->product_name, Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX'));
					}

					$rpdesc       = $config->maxchar($related_product [$r]->product_desc, Redshop::getConfig()->get('RELATED_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_DESC_END_SUFFIX'));
					$rp_shortdesc = $config->maxchar($related_product [$r]->product_s_desc, Redshop::getConfig()->get('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'));

					$related_template_data = str_replace("{relproduct_link}", '', $related_template_data);

					if (strpos($related_template_data, "{relproduct_link}") !== false)
					{
						$related_template_data = str_replace("{relproduct_name}", "", $related_template_data);
					}
					else
					{
						$related_template_data = str_replace("{relproduct_name}", $rpname, $related_template_data);
					}

					$related_template_data = str_replace("{relproduct_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $related_template_data);
					$related_template_data = str_replace("{relproduct_number}", $related_product [$r]->product_number, $related_template_data);
					$related_template_data = str_replace("{relproduct_s_desc}", $rp_shortdesc, $related_template_data);
					$related_template_data = str_replace("{relproduct_desc}", $rpdesc, $related_template_data);

					// ProductFinderDatepicker Extra Field Start
					$related_template_data = $this->getProductFinderDatepickerValue($related_template_data, $related_product[$r]->product_id, $fieldArray);

					if (strpos($related_template_data, "{manufacturer_name}") !== false || strpos($related_template_data, "{manufacturer_link}") !== false)
					{
						$manufacturer = $this->getSection("manufacturer", $related_product [$r]->manufacturer_id);

						if (count($manufacturer) > 0)
						{
							$man_url               = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $related_product[$r]->manufacturer_id . '&Itemid=' . $pItemid);
							$manufacturerLink      = "<a class='btn btn-primary' href='" . $man_url . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
							$related_template_data = str_replace("{manufacturer_name}", $manufacturer->manufacturer_name, $related_template_data);
							$related_template_data = str_replace("{manufacturer_link}", $manufacturerLink, $related_template_data);
						}
						else
						{
							$related_template_data = str_replace("{manufacturer_name}", '', $related_template_data);
							$related_template_data = str_replace("{manufacturer_link}", '', $related_template_data);
						}
					}

					$rmore = '<a href="' . $rlink . '" title="' . $related_product [$r]->product_name . '">'
						. JText::_('COM_REDSHOP_READ_MORE')
						. '</a>';
					$related_template_data = str_replace("{read_more}", $rmore, $related_template_data);
					$related_template_data = str_replace("{read_more_link}", $rlink, $related_template_data);

					/*
					 *  related product Required Attribute start
					 * 	this will parse only Required Attributes
					 */
					$relid          = $related_product [$r]->product_id;
					$attributes_set = array();

					if ($related_product [$r]->attribute_set_id > 0)
					{
						$attributes_set = $this->getProductAttribute(0, $related_product [$r]->attribute_set_id);
					}

					$attributes = $this->getProductAttribute($relid);
					$attributes = array_merge($attributes, $attributes_set);

					$related_template_data = $this->replaceAttributeData($related_product[$r]->mainproduct_id, 0, $related_product[$r]->product_id, $attributes, $related_template_data, $attribute_template);

					// Check product for not for sale
					$related_template_data = $this->getProductNotForSaleComment($related_product[$r], $related_template_data, $attributes, 1);

					$related_template_data = $this->replaceCartTemplate($related_product[$r]->mainproduct_id, 0, 0, $related_product[$r]->product_id, $related_template_data, false, 0, count($attributes), 0, 0);
					$related_template_data = $this->replaceCompareProductsButton($related_product[$r]->product_id, 0, $related_template_data, 1);
					$related_template_data = $this->replaceProductInStock($related_product[$r]->product_id, $related_template_data);

					$related_template_data = $this->getProductOnSaleComment($related_product[$r], $related_template_data);
					$related_template_data = $this->getSpecialProductComment($related_product[$r], $related_template_data);

					$isCategorypage = (JFactory::getApplication()->input->getCmd('view') == "category") ? 1 : 0;

					//  Extra field display
					$related_template_data = $this->getExtraSectionTag($extraFieldName, $related_product[$r]->product_id, "1", $related_template_data, $isCategorypage);

					// Related product attribute price list
					$related_template_data = $this->replaceAttributePriceList($related_product[$r]->product_id, $related_template_data);

					if (strpos($related_template_data, "{wishlist_link}") !== false)
					{
						$wishlistLink = "<div class=\"wishlist\">" . $this->replaceWishlistButton($related_product[$r]->product_id, '{wishlist_link}') ."</div>";
						$related_template_data =  str_replace("{wishlist_link}", $wishlistLink, $related_template_data);
					}

					$childproduct = $this->getChildProduct($related_product[$r]->product_id);

					if (count($childproduct) > 0)
					{
						$attributes = array();
					}
					else
					{
						// Get attributes
						$attributes_set = array();

						if ($related_product[$r]->attribute_set_id > 0)
						{
							$attributes_set = $this->getProductAttribute(0, $related_product[$r]->attribute_set_id, 0, 1);
						}

						$attributes = $this->getProductAttribute($related_product[$r]->product_id);
						$attributes = array_merge($attributes, $attributes_set);
					}

					$totalatt = count($attributes);

					$attributeproductStockStatus = array();

					$productAvailabilityDate = strstr($related_template_data, "{product_availability_date}");
					$stockNotifyFlag         = strstr($related_template_data, "{stock_notify_flag}");
					$stockStatus             = strstr($related_template_data, "{stock_status");

					if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
					{
						$attributeproductStockStatus = $this->getproductStockStatus($related_product[$r]->product_id, $totalatt);
					}

					$related_template_data = $this->replaceProductStockdata(
						$related_product[$r]->product_id,
						0,
						0,
						$related_template_data,
						$attributeproductStockStatus
					);

					$dispatcher->trigger('onAfterDisplayRelatedProduct', array(&$related_template_data, $related_product[$r]));
				}

				$related_template_data = $tempdata_div_start . $related_template_data . $tempdata_div_end;

				$template_desc = str_replace("{related_product:$related_template->template_name}", $related_template_data, $template_desc);

				$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
			}
			else
			{
				$template_desc = str_replace("{related_product:$related_template->template_name}", "", $template_desc);
			}
		}

		$template_desc = RedshopHelperText::replaceTexts($template_desc);

		return $template_desc;
	}

	/**
	 * replace related product attribute price list
	 *
	 * child product as related product concept is included
	 *    New Tag : {relproduct_attribute_pricelist} = related product attribute price list
	 *
	 * @params: $id :  product id
	 * @params: $templatedata : template data
	 */
	public function replaceAttributePriceList($id, $templatedata)
	{
		$output     = "";
		$attributes = $this->getProductAttribute($id, 0, 0, 1);

		$k = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$attribute      = $attributes[$i];
			$attribute_name = $attribute->text;
			$attribute_id   = $attribute->value;
			$propertys      = $this->getAttibuteProperty(0, $attribute_id);

			for ($p = 0, $pn = count($propertys); $p < $pn; $p++)
			{
				$property = $propertys[$p];

				$property_id             = $property->value;
				$property_name           = $property->text;
				$proprty_price           = $property->property_price;
				$property_formated_price = $this->getProductFormattedPrice($proprty_price);
				$proprty_oprand          = $property->oprand;

				$output .= '<div class="related_plist_property_name' . $k . '">' . $property_formated_price . '</div>';

				$subpropertys = $this->getAttibuteSubProperty(0, $property_id);

				for ($s = 0, $sn = count($subpropertys); $s < $sn; $s++)
				{
					$subproperty = $subpropertys[$s];

					$subproperty_id    = $subproperty->value;
					$subproperty_name  = $subproperty->text;
					$subproprty_price  = $subproperty->subattribute_color_price;
					$subproprty_oprand = $subproperty->oprand;
				}

				$k++;
			}
		}
		#$output = ($output!="") ? "<div>".$output."</div>" : "";
		$templatedata = str_replace("{relproduct_attribute_pricelist}", $output, $templatedata);

		return $templatedata;
	}

	public function getCategoryNameByProductId($pid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->where($db->qn('c.name') . ' IS NOT NULL')
			->order($db->qn('c.id') . ' ASC')
			->setLimit(0, 1);

		return $db->setQuery($query)->loadResult();

	}

	public function removeOutofstockProduct($products)
	{
		$stockroomhelper = rsstockroomhelper::getInstance();
		$filter_products = array();

		for ($s = 0, $sn = count($products); $s < $sn; $s++)
		{
			$product = $products[$s];
			$pid     = $product->product_id;

			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $this->getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes = $this->getProductAttribute($product->product_id);
			$attributes = array_merge($attributes, $attributes_set);
			$totalatt   = count($attributes);

			$stock_amount = $stockroomhelper->getFinalStockofProduct($pid, $totalatt);

			if ($stock_amount)
			{
				$filter_products[] = $products[$s];
			}
		}

		return $filter_products;
	}

	public function getproductStockStatus($product_id = 0, $totalatt = 0, $selectedPropertyId = 0, $selectedsubpropertyId = 0)
	{
		$stockroomhelper            = rsstockroomhelper::getInstance();
		$producDetail               = $this->getProductById($product_id);
		$product_preorder           = trim($producDetail->preorder);
		$rsltdata                   = array();
		$rsltdata['preorder']       = 0;
		$rsltdata['preorder_stock'] = 0;

		if ($selectedPropertyId)
		{
			if ($selectedsubpropertyId)
			{
				// Count status for selected subproperty
				$stocksts = $stockroomhelper->isStockExists($selectedsubpropertyId, "subproperty");

				if (!$stocksts && (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes")))
				{
					$prestocksts                = $stockroomhelper->isPreorderStockExists($selectedsubpropertyId, "subproperty");
					$rsltdata['preorder']       = 1;
					$rsltdata['preorder_stock'] = $prestocksts;
				}
			}
			else
			{
				// Count status for selected property
				$stocksts = $stockroomhelper->isStockExists($selectedPropertyId, "property");

				if (!$stocksts && (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes")))
				{
					$prestocksts                = $stockroomhelper->isPreorderStockExists($selectedPropertyId, "property");
					$rsltdata['preorder']       = 1;
					$rsltdata['preorder_stock'] = $prestocksts;
				}
			}
		}
		else
		{
			$stocksts = $stockroomhelper->getFinalStockofProduct($product_id, $totalatt);

			if (!$stocksts && (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes")))
			{
				$prestocksts                = $stockroomhelper->getFinalPreorderStockofProduct($product_id, $totalatt);
				$rsltdata['preorder']       = 1;
				$rsltdata['preorder_stock'] = $prestocksts;
			}
		}

		$rsltdata['regular_stock'] = $stocksts;

		return $rsltdata;
	}

	public function replaceProductStockdata($product_id, $property_id, $subproperty_id, $data_add, $stockStatusArray)
	{
		if (strpos($data_add, "{stock_status") !== false)
		{
			$product = RedshopProduct::getInstance($product_id);

			$stocktag     = strstr($data_add, "{stock_status");
			$newstocktag  = explode("}", $stocktag);
			$realstocktag = $newstocktag[0] . "}";

			$stock_tag = substr($newstocktag[0], 1);
			$sts_array = explode(":", $stock_tag);

			$avail_class = "available_stock_cls";

			if (isset($sts_array[1]) && $sts_array[1] != "")
			{
				$avail_class = $sts_array[1];
			}

			$out_stock_class = "out_stock_cls";

			if (isset($sts_array[2]) && $sts_array[2] != "")
			{
				$out_stock_class = $sts_array[2];
			}

			$pre_order_class = "pre_order_cls";

			if (isset($sts_array[3]) && $sts_array[3] != "")
			{
				$pre_order_class = $sts_array[3];
			}

			if ($product->not_for_sale == 1)
			{
				$stock_status = '';
			}
			elseif (!isset($stockStatusArray['regular_stock']) || !$stockStatusArray['regular_stock'])
			{
				if (($stockStatusArray['preorder'] && !$stockStatusArray['preorder_stock']) || !$stockStatusArray['preorder'])
				{
					$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $out_stock_class
						. "' class='" . $out_stock_class . "'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div></span>";
				}
				else
				{
					$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $pre_order_class
						. "' class='" . $pre_order_class . "'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
				}
			}
			else
			{
				$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $avail_class . "' class='"
					. $avail_class . "'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
			}

			$data_add = str_replace($realstocktag, $stock_status, $data_add);
		}

		RedshopLayoutHelper::renderTag(
			'{stock_notify_flag}', $data_add, 'product', array(
				'productId' => $product_id, 'propertyId' => $property_id, 'subPropertyId' => $subproperty_id,
				'productStockStatus' => $stockStatusArray
			)
		);

		if (strstr($data_add, "{product_availability_date}"))
		{
			$redshopconfig = Redconfiguration::getInstance();
			$product       = $this->getProductById($product_id);

			if ((!isset($stockStatusArray['regular_stock']) || !$stockStatusArray['regular_stock']) && $stockStatusArray['preorder'])
			{
				if ($product->product_availability_date)
				{
					$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
						. $product_id . "'>" . JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL') . ": </span>", $data_add);
					$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
						. "'>" . $redshopconfig->convertDateFormat($product->product_availability_date) . "</span>", $data_add);
				}
				else
				{
					$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
						. $product_id . "'></span>", $data_add);
					$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
						. "'></span>", $data_add);
				}

			}
			else
			{
				$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
					. $product_id . "'></span>", $data_add);
				$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
					. "'></span>", $data_add);

			}
		}

		return $data_add;
	}

	/**
	 * Check already notified user
	 *
	 * @param   int  $user_id         User id
	 * @param   int  $product_id      Product id
	 * @param   int  $property_id     Property id
	 * @param   int  $subproperty_id  Sub property id
	 *
	 * @deprecated  1.5 Use RedshopHelperStockroom::isAlreadyNotifiedUser instead
	 *
	 * @return mixed
	 */
	public function isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id)
	{
		return RedshopHelperStockroom::isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id);
	}

	public function insertPaymentShippingField($cart = array(), $order_id = 0, $section_id = 18)
	{
		$db = JFactory::getDbo();

		$extraField = extraField::getInstance();
		$row_data   = $extraField->getSectionFieldList($section_id, 1);

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$user_fields = $cart['extrafields_values'][$row_data[$i]->name];

			if (trim($user_fields) != '')
			{
				$sql = "INSERT INTO #__redshop_fields_data "
					. "(fieldid,data_txt,itemid,section) "
					. "value ('" . (int) $row_data[$i]->id . "'," . $db->quote(addslashes($user_fields)) . "," . (int) $order_id
					. "," . $db->quote($section_id) . ")";
				$this->_db->setQuery($sql);
				$this->_db->execute();
			}
		}

		return;
	}

	public function getPaymentandShippingExtrafields($order, $section_id)
	{
		$extraField = extraField::getInstance();
		$row_data   = $extraField->getSectionFieldList($section_id, 1);
		$resultArr  = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->id, $section_id, $order->order_id);

			if (!is_null($main_result) && $main_result->data_txt != "" && $row_data[$j]->show_in_front == 1)
			{
				$resultArr[] = $main_result->title . " : " . $main_result->data_txt;
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	/**
	 * Return checked if product is in session of compare product cart else blank
	 *
	 * @param   integer  $productId  Id of product
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function checkCompareProduct($productId)
	{
		$productId = (int) $productId;

		if (!$productId)
		{
			return '';
		}

		$compareProducts = $this->_session->get('compare_product');

		if (!$compareProducts)
		{
			return '';
		}

		$idx = (int) ($compareProducts['idx']);

		for ($i = 0; $i < $idx; $i++)
		{
			if ($compareProducts[$i]["product_id"] == $productId)
			{
				return 'checked';
			}

		}

		return '';
	}

	/**
	 * Get Max and Min of Product Price
	 *
	 * @param   int  $productId  Product Id
	 *
	 * @return  array
	 */
	public function getProductMinMaxPrice($productId)
	{
		$attributes = $this->getProductAttribute($productId);
		$propertyIds = array();
		$subPropertyIds = array();
		$propertyPriceList = array();
		$subPropertyPriceList = array();


		foreach ($attributes as $key => $attribute)
		{
			foreach ($attribute->properties as $property)
			{
				$propertyIds[] = $property->property_id;
				$subProperties = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->property_id);

				foreach ($subProperties as $subProperty)
				{
					$subPropertyIds[] = $subProperty->value;
				}
			}
		}

		$db = JFactory::getDbo();

		if (!empty($productId))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_price'))
				->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));
			$productPriceList = $db->setQuery($query)->loadColumn();
		}

		if (!empty($propertyIds))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('property'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $propertyIds) . ')');
			$propertyPriceList = $db->setQuery($query)->loadColumn();
		}

		if (!empty($subPropertyIds))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('subproperty'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $subPropertyIds) . ')');
			$subPropertyPriceList = $db->setQuery($query)->loadColumn();
		}

		$productPriceList = array_unique(array_merge($productPriceList, $propertyPriceList, $subPropertyPriceList));
		$productPrice['min'] = min($productPriceList);
		$productPrice['max'] = max($productPriceList);

		return $productPrice;
	}
}
