<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Template\Cart;

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class productHelper
{
	public $_db = null;

	public $_table_prefix = null;

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

	/**
	 * ProductHelper constructor.
	 */
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
	 * @param   string $elementName Element name
	 *
	 * @return  object|null
	 *
	 * @since       1.6.0
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
	 * @param   integer $wishlistId Wish list id
	 * @param   integer $productId  Product Id
	 *
	 * @return  array
	 *
	 * @since       1.6.0
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
	 * @param   bool|JDatabaseQuery $query  Get query or false
	 * @param   int                 $userId User id
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
	 * @param   int $productId Product id
	 * @param   int $userId    User id
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
	 * @param   array $products Array product/s values
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
	 * @param   string $country Country code
	 *
	 * @return  boolean
	 *
	 * @since       1.6.0
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
	 * @param   int $productId Product id
	 * @param   int $userId    User id
	 * @param   int $quantity  Quantity
	 *
	 * @return mixed
	 * @deprecated 2.1.0
	 */
	public function getProductPrices($productId, $userId, $quantity = 1)
	{
		return RedshopHelperProduct::getProductPrices($productId, $userId, $quantity);
	}

	/**
	 * Get Product Special Price
	 *
	 * @param   float  $productPrice      Product price
	 * @param   string $discountStringIds Discount ids
	 * @param   int    $productId         Product id
	 *
	 * @return  null|object
	 *
	 * @deprecated  2.0.7  Use RedshopHelperProductPrice::getProductSpecialPrice
	 */
	public function getProductSpecialPrice($productPrice, $discountStringIds, $productId = 0)
	{
		return RedshopHelperProductPrice::getProductSpecialPrice($productPrice, $discountStringIds, $productId);
	}

	/**
	 * Get Product Special Id
	 *
	 * @param   int $userId User Id
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
			RedshopHelperUser::createUserSession($userId);
			
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
				$userArr = RedshopHelperUser::createUserSession($userId);
			}

			$shopperGroupId = isset($userArr['rs_user_shopperGroup']) ?
				$userArr['rs_user_shopperGroup'] : RedshopHelperUser::getShopperGroup($userId);

			$query = $db->getQuery(true)
				->select('dps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'dps'))
				->where('dps.shopper_group_id =' . (int) $shopperGroupId);
		}

		$result = $db->setQuery($query)->loadColumn();

		self::$productSpecialIds[$userId] = '0';

		if (!empty($result))
		{
			self::$productSpecialIds[$userId] .= ',' . implode(',', $result);
		}

		return self::$productSpecialIds[$userId];
	}

	/**
	 * Method for get product tax
	 *
	 * @param   integer $productId    Product Id
	 * @param   integer $productPrice Product price
	 * @param   integer $userId       User ID
	 * @param   integer $taxExempt    Tax exempt
	 *
	 * @return  integer
	 *
	 * @deprecated   2.0.6
	 */
	public function getProductTax($productId = 0, $productPrice = 0, $userId = 0, $taxExempt = 0)
	{
		return RedshopHelperProduct::getProductTax($productId, $productPrice, $userId, $taxExempt);
	}

	/**
	 * Method for replace tags about VAT information
	 *
	 * @param   string $data Template data.
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.6
	 *
	 * @see          RedshopHelperTax::replaceVatInformation
	 */
	public function replaceVatinfo($data)
	{
		return RedshopHelperTax::replaceVatInformation($data);
	}

	/**
	 * Check user for Tax Exemption approved
	 *
	 * @param   integer $userId                User Information Id - Login user id
	 * @param   integer $isShowButtonAddToCart Display Add to cart button for tax exemption user
	 *
	 * @return  boolean                          True if VAT applied else false
	 *
	 * @deprecated  2.0.6
	 *
	 * @see         RedshopHelperCart::taxExemptAddToCart
	 */
	public function taxexempt_addtocart($userId = 0, $isShowButtonAddToCart = 0)
	{
		return RedshopHelperCart::taxExemptAddToCart($userId, (boolean) $isShowButtonAddToCart);
	}

	/**
	 * Get VAT User information
	 *
	 * @param   integer $userId User ID
	 *
	 * @return  object
	 *
	 * @deprecated   2.0.6
	 */
	public function getVatUserinfo($userId = 0)
	{
		return RedshopHelperUser::getVatUserInformation($userId);
	}

	/**
	 * get VAT rates from product or global
	 *
	 * @param   int $productId Id current product
	 * @param   int $userId    Id current user
	 *
	 * @return  object|null  VAT rates information
	 *
	 * @deprecated  2.0.7
	 *
	 * @see         RedshopHelperTax::getVatRates
	 */
	public function getVatRates($productId = 0, $userId = 0)
	{
		return RedshopHelperTax::getVatRates($productId, $userId);
	}

	/**
	 * Get ExtraFields For Current Template
	 *
	 * @param   array  $filedNames     Field name list
	 * @param   string $templateData   Template data
	 * @param   int    $isCategoryPage Flag change extra fields in category page
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
	 * @param   array   $fieldNames      List of field names
	 * @param   integer $productId       ID of product
	 * @param   integer $section         Section
	 * @param   string  $templateContent Template content
	 * @param   integer $categoryPage    Argument for product section extra field for category page
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7
	 */
	public function getExtraSectionTag($fieldNames = array(), $productId = 0, $section = 0, $templateContent = '', $categoryPage = 0)
	{
		return RedshopHelperProductTag::getExtraSectionTag($fieldNames, $productId, $section, $templateContent, $categoryPage);
	}

	/**
	 * Method for replace price.
	 *
	 * @param   float $productPrice Product price
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7
	 */
	public function getPriceReplacement($productPrice)
	{
		return RedshopHelperProductPrice::priceReplacement($productPrice);
	}

	/**
	 * Format Product Price
	 *
	 * @param   float   $productPrice   Product price
	 * @param   boolean $convert        Decide to convert price in Multi Currency
	 * @param   string  $currencySymbol Product Formatted Price
	 *
	 * @return  string                    Formatted Product Price
	 *
	 * @deprecated  2.0.7
	 *
	 * @see         RedshopHelperProductPrice::formattedPrice
	 */
	public function getProductFormattedPrice($productPrice, $convert = true, $currencySymbol = '_NON_')
	{
		return RedshopHelperProductPrice::formattedPrice($productPrice, $convert, $currencySymbol);
	}

	/**
	 * Method for round product price
	 *
	 * @param   float $productPrice Product price
	 *
	 * @return  float
	 *
	 * @deprecated   2.0.7
	 */
	public function productPriceRound($productPrice)
	{
		return RedshopHelperProductPrice::priceRound($productPrice);
	}

	public function getProductparentImage($product_parent_id)
	{
		$result = RedshopHelperProduct::getProductById($product_parent_id);

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = $this->getProductparentImage($result->product_parent_id);
		}

		return $result;
	}

	/**
	 * Get Product image
	 *
	 * @param   integer $product_id              Product Id
	 * @param   string  $link                    Product link
	 * @param   integer $width                   Product image width
	 * @param   integer $height                  Product image height
	 * @param   integer $Product_detail_is_light Product detail is light
	 * @param   integer $enableHover             Enable hover
	 * @param   integer $suffixid                Suffix id
	 * @param   array   $preselectedresult       Preselected result
	 *
	 * @return  string   Product Image
	 *
	 * @deprecated 2.1.0 Use Redshop\Product\Image\Image::getImage()
	 * @see        Redshop\Product\Image\Image::getImage
	 */
	public function getProductImage($product_id = 0, $link = '', $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $suffixid = 0, $preselectedresult = array())
	{
		return Redshop\Product\Image\Image::getImage($product_id, $link, $width, $height, $Product_detail_is_light, $enableHover, $suffixid, $preselectedresult);
	}

	/**
	 * @param   stdClass $product                 Product data
	 * @param   string   $imagename               Image name
	 * @param   string   $linkimagename           Link image name
	 * @param   string   $link                    Link
	 * @param   integer  $width                   Width
	 * @param   integer  $height                  Height
	 * @param   integer  $Product_detail_is_light Product detail is light
	 * @param   integer  $enableHover             Enable hover or not
	 * @param   array    $preselectedResult       Pre selected results
	 * @param   integer  $suffixid                Suffix ID
	 *
	 * @return  string                              Html content with replaced.
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0 Use Redshop\Product\Image\Render()
	 * @see        Redshop\Product\Image\Render
	 */
	public function replaceProductImage($product, $imagename = "", $linkimagename = "", $link = "", $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $preselectedResult = array(), $suffixid = 0)
	{
		return Redshop\Product\Image\Render::replace($product, $imagename, $linkimagename, $link, $width, $height, $Product_detail_is_light, $enableHover, $preselectedResult, $suffixid);
	}

	public function getProductCategoryImage($product_id = 0, $category_img = '', $link = '', $width, $height)
	{
		$result     = RedshopHelperProduct::getProductById($product_id);
		$thum_image = "";
		$title      = " title='" . $result->product_name . "' ";
		$alt        = " alt='" . $result->product_name . "' ";

		if ($category_img && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $category_img))
		{
			if (Redshop::getConfig()->get('PRODUCT_IS_LIGHTBOX') == 1)
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				RedshopHelperMedia::watermark('product', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$linkimage         = RedshopHelperMedia::watermark('category', $category_img, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $linkimage . "' " . $title . "  rel=\"myallimg\">";
				$thum_image        .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";

				$thum_image .= "</a>";
			}
			else
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				RedshopHelperMedia::watermark('category', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $link . "' " . $title . ">";
				$thum_image        .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";
				$thum_image        .= "</a>";
			}
		}

		return $thum_image;
	}

	public function getProductMinDeliveryTime($product_id = 0, $section_id = 0, $section = '', $loadDiv = 1)
	{
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

	/**
	 * Method for get default quantity
	 *
	 * @param   integer $product_id Product ID
	 * @param   string  $data_add   Template html
	 *
	 * @return  integer
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Cart\Helper::getDefaultQuantity
	 */
	public function GetDefaultQuantity($product_id = 0, $data_add = "")
	{
		return \Redshop\Cart\Helper::getDefaultQuantity($product_id, $data_add);
	}

	/**
	 * Method for get product show price
	 *
	 * @param   integer $productId    Product ID
	 * @param   string  $templateHtml Template content
	 * @param   string  $seoTemplate  SEO template
	 * @param   int     $userId       User ID
	 * @param   int     $isRel        Is Rel
	 * @param   array   $attributes   Attributes
	 *
	 * @return  mixed|string
	 *
	 * @deprecated   2.0.7
	 *
	 * @see          RedshopHelperProductPrice::getShowPrice()
	 */
	public function getProductShowPrice($productId, $templateHtml, $seoTemplate = "", $userId = 0, $isRel = 0, $attributes = array())
	{
		return RedshopHelperProductPrice::getShowPrice($productId, $templateHtml, $seoTemplate, $userId, (boolean) $isRel, $attributes);
	}

	/**
	 * Method for get product net price
	 *
	 * @param   integer $productId  ID of product
	 * @param   integer $userId     ID of user
	 * @param   integer $quantity   Quantity for get
	 * @param   string  $dataAdd    Template data
	 * @param   array   $attributes Attributes list.
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.7
	 */
	public function getProductNetPrice($productId, $userId = 0, $quantity = 1, $dataAdd = '', $attributes = array())
	{
		return RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity, $dataAdd, $attributes);
	}

	/**
	 * Get Layout product quantity price
	 *
	 * @param   int $productId Product Id
	 * @param   int $userId    User Id
	 *
	 * @deprecated  1.5  Use RedshopHelperProduct::getProductQuantityPrice instead
	 *
	 * @return  mixed  Redshop Layout
	 */
	public function getProductQuantityPrice($productId, $userId)
	{
		return RedshopHelperProduct::getProductQuantityPrice($productId, $userId);
	}

	/**
	 * Method for get discount
	 *
	 * @param   integer $subTotal Sub-total amount
	 * @param   integer $userId   User ID
	 *
	 * @return  mixed
	 *
	 * @deprecated 2.0.3
	 * @see        RedshopHelperDiscount::getDiscount
	 */
	public function getDiscountId($subTotal = 0, $userId = 0)
	{
		return RedshopHelperDiscount::getDiscount($subTotal, $userId);
	}

	/**
	 * Method for get discount amount fromm cart
	 *
	 * @param   array   $cart   Cart data
	 * @param   integer $userId User ID
	 *
	 * @return  float
	 *
	 * @deprecated 2.1.0
	 */
	public function getDiscountAmount($cart = array(), $userId = 0)
	{
		return Redshop\Cart\Helper::getDiscountAmount($cart, $userId);
	}

	/**
	 * Method for get price of product
	 *
	 * @param   integer $product_id          Product ID
	 * @param   integer $show_price_with_vat True for include VAT. False for not include VAT
	 * @param   integer $user_id             User ID
	 *
	 * @return  float
	 * @since   2.1.0
	 */
	public function getProductPrice($product_id, $show_price_with_vat = 1, $user_id = 0)
	{
		return Redshop\Product\Price::getPrice($product_id, (boolean) $show_price_with_vat, $user_id);
	}

	/**
	 * Method for get additional media images
	 *
	 * @param   int    $section_id Section Id
	 * @param   string $section    Section name
	 * @param   string $mediaType  Media type
	 *
	 * @return  array
	 *
	 * @since       2.0.3
	 *
	 * @deprecated  2.0.7
	 */
	public function getAdditionMediaImage($section_id = 0, $section = "", $mediaType = "images")
	{
		return RedshopHelperMedia::getAdditionMediaImage($section_id, $section, $mediaType);
	}

	/**
	 * Get alternative text for media
	 *
	 * @param   string $mediaSection Media section
	 * @param   int    $sectionId    Section i
	 * @param   string $mediaName    Media name
	 * @param   int    $mediaId      Media id
	 * @param   string $mediaType    Media type
	 *
	 * @return  string                 Alternative text from media
	 *
	 * @deprecated  2.0.7
	 */
	public function getAltText($mediaSection, $sectionId, $mediaName = '', $mediaId = 0, $mediaType = 'images')
	{
		return RedshopHelperMedia::getAlternativeText($mediaSection, $sectionId, $mediaName, $mediaId, $mediaType);
	}

	/**
	 * Get redshop user information
	 *
	 * @param   int    $userId      Id joomla user
	 * @param   string $addressType Type user address BT (Billing Type) or ST (Shipping Type)
	 * @param   int    $userInfoId  Id redshop user
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::getUserInformation instead
	 *
	 * @return  object  Redshop user information
	 */
	public function getUserInformation($userId = 0, $addressType = 'BT', $userInfoId = 0)
	{
		return RedshopHelperUser::getUserInformation($userId, $addressType, $userInfoId);
	}

	/**
	 * Method for check if template apply VAT or not
	 *
	 * @param   string  $template Template content
	 * @param   integer $userId   User ID
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Template\Helper::isApplyVat
	 */
	public function getApplyVatOrNot($template = "", $userId = 0)
	{
		return \Redshop\Template\Helper::isApplyVat($template, $userId);
	}

	/**
	 * Method for check if template apply attribute VAT or not
	 *
	 * @param   string  $template Template content
	 * @param   integer $userId   User ID
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Template\Helper::isApplyAttributeVat
	 */
	public function getApplyattributeVatOrNot($template = "", $userId = 0)
	{
		return \Redshop\Template\Helper::isApplyAttributeVat($template, $userId);
	}

	/**
	 * Method for get default shopper group data
	 *
	 * @return   array
	 *
	 * @deprecated 2.1.0
	 */
	public function GetdefaultshopperGroupData()
	{
		return \Redshop\Helper\ShopperGroup::getDefault();
	}

	/**
	 * Get discount price from product with check discount date.
	 *
	 * @param   int $productId Product id
	 *
	 * @return  float
	 *
	 * @deprecated   2.0.7
	 *
	 * @see          RedshopHelperDiscount::getDiscountPriceBaseDiscountDate()
	 */
	public function checkDiscountDate($productId)
	{
		return RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($productId);
	}

	/**
	 * Method for get property price with discount
	 *
	 * @param   integer        $sectionId Section ID
	 * @param   string         $quantity  Quantity
	 * @param   string         $section   Section
	 * @param   integer        $userId    User ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperProduct_Attribute::getPropertyPrice() instead.
	 */
	public function getPropertyPrice($sectionId = 0, $quantity = '', $section = '', $userId = 0)
	{
		return RedshopHelperProduct_Attribute::getPropertyPrice($sectionId, $quantity, $section, $userId);
	}

	/**
	 * Method for get property or sub object
	 *
	 * @param   string $sectionId Section ID
	 * @param   string $section   Section
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

	/**
	 * Method for get list of pathway
	 *
	 * @param   array $category List of category
	 *
	 * @return  array             List of pathway
	 *
	 * @deprecated    2.0.7
	 */
	public function getBreadcrumbPathway($category = array())
	{
		return RedshopHelperBreadcrumb::getPathway($category);
	}

	public function getCategoryNavigationlist($category_id)
	{
		static $i = 0;
		static $category_list = array();

		$categorylist       = RedshopEntityCategory::getInstance($category_id)->getItem();
		$category_parent_id = $this->getParentCategory($category_id);

		if (!empty($categorylist) && $categorylist->parent_id > 0)
		{
			$cItemid = RedshopHelperRouter::getCategoryItemid($categorylist->id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = JFactory::getApplication()->input->get('Itemid');
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
	 * @param   integer $sectionId Section ID
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @deprecated    2.0.7
	 *
	 * @see           RedshopHelperBreadcrumb::generate()
	 */
	public function generateBreadcrumb($sectionId = 0)
	{
		RedshopHelperBreadcrumb::generate($sectionId);
	}

	/**
	 * Get section
	 *
	 * @param   string  $section Section name
	 * @param   integer $id      Section id
	 *
	 * @return  mixed|null
	 * @deprecated 2.1.0
	 */
	public function getSection($section = '', $id = 0)
	{
		// To avoid killing queries do not allow queries that get all the items
		if ($id != 0 && $section != '')
		{
			switch ($section)
			{
				case 'product':
					return RedshopHelperProduct::getProductById($id);
				case 'category':
					return RedshopHelperCategory::getCategoryById($id);
				default:
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_' . $section))
						->where($db->qn($section . '_id') . ' = ' . (int) $id);

					return $db->setQuery($query)->loadObject();
			}
		}

		return null;
	}

	/**
	 * Get menu detail
	 *
	 * @param   string $link Link
	 *
	 * @return  mixed|null
	 * @throws  Exception
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
	 * @param   int    $Itemid      Item id
	 * @param   int    $sectionId   Section id
	 * @param   string $sectionName Section name
	 * @param   string $menuView    Menu view
	 * @param   bool   $isRedshop   Is redshop
	 *
	 * @return mixed|null
	 */
	public function getMenuInformation($Itemid = 0, $sectionId = 0, $sectionName = '', $menuView = '', $isRedshop = true)
	{
		$menu   = JFactory::getApplication()->getMenu();
		$values = array();

		if ($menuView != "")
		{
			if ($items = explode('&', $menuView))
			{
				$values['view'] = $items[0];
				unset($items[0]);

				if (!empty($items))
				{
					foreach ($items as $item)
					{
						$value             = explode('=', $item);
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
			$menuItems = RedshopHelperRouter::getRedshopMenuItems();
		}
		else
		{
			$menuItems = $menu->getMenu();
		}

		foreach ($menuItems as $oneMenuItem)
		{
			if (!RedshopHelperRouter::checkMenuQuery($oneMenuItem, $values))
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
	 * @param   int $productId Product id
	 *
	 * @return string
	 */
	public function getCategoryProduct($productId = 0)
	{
		if ($result = RedshopHelperProduct::getProductById($productId))
		{
			if (!empty($result->categories))
			{
				return is_array($result->categories) ? implode(',', $result->categories) : $result->categories;
			}

			return $result->category_id;
		}

		return '';
	}

	public function getProductCategory($id = 0)
	{
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();
		$and                        = '';

		if ($shopper_group_manufactures != "")
		{
			// Sanitize groups
			$shopGroupsIds = explode(',', $shopper_group_manufactures);
			$shopGroupsIds = Joomla\Utilities\ArrayHelper::toInteger($shopGroupsIds);

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
	 * @param   integer $productId Product Id
	 * @param   boolean $return    If yes, return object. False return number of download
	 *
	 * @return  object|integer
	 *
	 * @deprecated   2.0.7
	 *
	 * @see          RedshopHelperProductDownload::checkDownload()
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

	/**
	 * Method for get giftcard data
	 *
	 * @param   integer $gid ID of giftcard
	 *
	 * @return object
	 *
	 * @deprecated  2.1.0
	 */
	public function getGiftcardData($gid)
	{
		return RedshopEntityGiftcard::getInstance($gid)->getItem();
	}

	public function getValidityDate($period, $data)
	{
		$todate = mktime(0, 0, 0, (int) date('m'), (int) date('d') + $period, (int) date('Y'));

		$todate   = RedshopHelperDatetime::convertDateFormat($todate);
		$fromdate = RedshopHelperDatetime::convertDateFormat(strtotime(date('d M Y')));

		$data = str_replace("{giftcard_validity_from}", JText::_('COM_REDSHOP_FROM') . " " . $fromdate, $data);
		$data = str_replace("{giftcard_validity_to}", JText::_('COM_REDSHOP_TO') . " " . $todate, $data);

		return $data;
	}

	/**
	 * Method for calculate accessory price
	 *
	 * @param   integer $productId          Product ID
	 * @param   integer $accessoryPrice     Accessory price
	 * @param   integer $accessoryMainPrice Accessory main price
	 * @param   integer $hasVAT             Is include VAT?
	 * @param   integer $userId             User ID
	 *
	 * @return  array
	 *
	 * @deprecated   2.1.0
	 * @see          \Redshop\Product\Accessory::getAccessoryPrice
	 */
	public function getAccessoryPrice($productId = 0, $accessoryPrice = 0, $accessoryMainPrice = 0, $hasVAT = 0, $userId = 0)
	{
		return \Redshop\Product\Accessory::getPrice($productId, $accessoryPrice, $accessoryMainPrice, $hasVAT, $userId);
	}

	public function getuserfield($orderitemid = 0, $section_id = 12)
	{
		$resultArr = array();

		$userfield = RedshopHelperOrder::getOrderUserFieldData($orderitemid, $section_id);

		if (!empty($userfield))
		{
			$orderItem  = RedshopHelperOrder::getOrderItemDetail(0, 0, $orderitemid);
			$product_id = $orderItem[0]->product_id;

			$productdetail   = RedshopHelperProduct::getProductById($product_id);
			$productTemplate = RedshopHelperTemplate::getTemplate("product", $productdetail->product_template);

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
								$u_link   = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
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

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
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

			if (!empty($template_start))
			{
				$template_end = explode("{giftcard_userfield end if}", $template_start[1]);

				if (!empty($template_end))
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

				if (!empty($template_end))
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
		$cart     = RedshopHelperCartSession::getCart();
		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		if ($section_id == 12)
		{
			$product_id    = $cart[$id]['product_id'];
			$productdetail = RedshopHelperProduct::getProductById($product_id);
			$temp_name     = "product";
			$temp_id       = $productdetail->product_template;
			$giftcard      = 0;
		}
		else
		{
			$temp_name = "giftcard";
			$temp_id   = 0;
			$giftcard  = 1;
		}

		$productTemplate = RedshopHelperTemplate::getTemplate($temp_name, $temp_id);

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

						$resultArr[] = $strtitle . ' <span class="product-userfield-value">' . $cart[$id][$userFieldTag[$i]] . '</span>';
					}
				}
			}
		}

		$resultstr = "";

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
	}

	public function GetProdcutfield($id = 'NULL', $section_id = 1)
	{
		$cart       = $this->_session->get('cart');
		$product_id = $cart[$id]['product_id'];
		$row_data   = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = RedshopHelperExtrafields::getData($row_data[$j]->id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = '<span class="product-field-title">' . $main_result->title . ': </span><span class="product-field-value">' . $main_result->data_txt . '</span>';
				}
			}
		}

		$resultstr = "";

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return implode("<br/>", $resultArr);
	}

	public function GetProdcutfield_order($orderitemid = 'NULL', $section_id = 1)
	{
		$orderItem = RedshopHelperOrder::getOrderItemDetail(0, 0, $orderitemid);

		$product_id = $orderItem[0]->product_id;

		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = RedshopHelperExtrafields::getData($row_data[$j]->id, $section_id, $product_id);

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

		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1);

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
	 * @param   int $productId         Product id
	 * @param   int $attributeSetId    Attribute set id
	 * @param   int $attributeId       Attribute id
	 * @param   int $published         Published attribute set
	 * @param   int $attributeRequired Attribute required
	 * @param   int $notAttributeId    Not attribute id
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
	 * @param   int $propertyId     Property id
	 * @param   int $attributeId    Attribute id
	 * @param   int $productId      Product id
	 * @param   int $attributeSetId Attribute set id
	 * @param   int $required       Required
	 * @param   int $notPropertyId  Not property id
	 *
	 * @return  mixed
	 *
	 * @deprecated   2.0.3  Use RedshopHelperProduct_Attribute::getAttributeProperties() instead.
	 * @see          RedshopHelperProduct_Attribute::getAttributeProperties
	 */
	public function getAttibuteProperty($propertyId = 0, $attributeId = 0, $productId = 0, $attributeSetId = 0, $required = 0, $notPropertyId = 0)
	{
		return RedshopHelperProduct_Attribute::getAttributeProperties(
			$propertyId, $attributeId, $productId, $attributeSetId, $required, $notPropertyId
		);
	}

	/**
	 * Method for get attribute with stock
	 *
	 * @param   array $property List of property
	 *
	 * @return  array
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Helper\Stockroom::getAttributePropertyWithStock
	 */
	public function getAttibutePropertyWithStock($property = array())
	{
		return \Redshop\Helper\Stockroom::getAttributePropertyWithStock($property);
	}

	/**
	 * Method for get sub-attribute with stock
	 *
	 * @param   array $subproperty List of property
	 *
	 * @return  array
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Helper\Stockroom::getAttributeSubPropertyWithStock
	 */
	public function getAttibuteSubPropertyWithStock($subproperty)
	{
		return \Redshop\Helper\Stockroom::getAttributeSubPropertyWithStock($subproperty);
	}

	/**
	 * Method for get sub properties
	 *
	 * @param   int $subproperty_id Sub-Property ID
	 * @param   int $property_id    Property ID
	 *
	 * @return  mixed                List of sub-properties data.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperProduct_Attribute::getAttributeSubProperties() instead
	 */
	public function getAttibuteSubProperty($subproperty_id = 0, $property_id = 0)
	{
		return RedshopHelperProduct_Attribute::getAttributeSubProperties($subproperty_id, $property_id);
	}

	/**
	 * Method for get attribute template
	 *
	 * @param   string  $templateHtml Template html
	 * @param   boolean $display      Is display?
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @deprecated   2.1.0
	 * @see          \Redshop\Template\Helper::getAttribute
	 */
	public function getAttributeTemplate($templateHtml = '', $display = true)
	{
		return \Redshop\Template\Helper::getAttribute($templateHtml, $display);
	}

	/**
	 * Method for get Product Accessories.
	 *
	 * @param   string $accessory_id     ID of accessory.
	 * @param   string $product_id       ID of product.
	 * @param   int    $child_product_id ID of child product.
	 * @param   int    $cid              ID of category.
	 *
	 * @return  array                 List of accessories.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperAccessory::getProductAccessories() instead.
	 */
	public function getProductAccessory($accessory_id = '', $product_id = '', $child_product_id = 0, $cid = 0)
	{
		return RedshopHelperAccessory::getProductAccessories($accessory_id, $product_id, $child_product_id, $cid);
	}

	/**
	 * Method for get add-to-cart template
	 *
	 * @param   string $templateHtml Template HTML
	 *
	 * @return  null|object
	 * @throws  \Exception
	 *
	 * @deprecated   2.1.0
	 * @see          \Redshop\Template\Helper::getAddToCart
	 */
	public function getAddtoCartTemplate($templateHtml = '')
	{
		return \Redshop\Template\Helper::getAddToCart($templateHtml);
	}

	/**
	 * Method for get accessory template
	 *
	 * @param   string $templateHtml Template HTML
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @deprecated   2.1.0
	 * @see          \Redshop\Template\Helper::getAccessory
	 */
	public function getAccessoryTemplate($templateHtml = "")
	{
		return \Redshop\Template\Helper::getAccessory($templateHtml);
	}

	/**
	 * Method for get add-to-cart template
	 *
	 * @param   string $templateHtml Template HTML
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @deprecated   2.1.0
	 * @see          \Redshop\Template\Helper::getRelatedProduct
	 */
	public function getRelatedProductTemplate($templateHtml = '')
	{
		return \Redshop\Template\Helper::getRelatedProduct($templateHtml);
	}

	/**
	 * @param   integer $productId Product id
	 * @param   integer $relatedId Related id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public function getRelatedProduct($productId = 0, $relatedId = 0)
	{
		$and             = "";
		$orderby         = "ORDER BY p.product_id ASC ";
		$orderby_related = "";

		if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD'))
		{
			$orderby         = "ORDER BY " . Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD');
			$orderby_related = "";
		}

		if ($productId != 0)
		{
			// Sanitize ids
			$productIds = explode(',', $productId);
			$productIds = Joomla\Utilities\ArrayHelper::toInteger($productIds);

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

				$query = "SELECT * FROM " . $this->_table_prefix . "product_related AS r "
					. "WHERE r.product_id IN (" . implode(',', $productIds) . ") OR r.related_id IN (" . implode(',', $productIds) . ")" . $orderby_related . "";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				$relatedArr = array();

				for ($i = 0, $in = count($list); $i < $in; $i++)
				{
					if ($list[$i]->product_id == $productId)
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
				$relatedArr = Joomla\Utilities\ArrayHelper::toInteger($relatedArr);
				$relatedArr = array_unique($relatedArr);

				$query = "SELECT " . $productId . " AS mainproduct_id,p.* "
					. "FROM " . $this->_table_prefix . "product AS p "
					. "WHERE p.published = 1 ";
				$query .= ' AND p.product_id IN (' . implode(", ", $relatedArr) . ') ';
				$query .= $orderby;

				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				return $list;
			}
		}

		if ($relatedId != 0)
		{
			$and .= "AND r.related_id = " . (int) $relatedId . " ";
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
				$data_add          = $template_pd_sdata . $template_pd_edata;
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
			$applytax = \Redshop\Template\Helper::isApplyVat($data_add);

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

	/**
	 * Method for get ajax detail box template
	 *
	 * @param   object $product Product data
	 *
	 * @return  object
	 * @throws  \Exception
	 *
	 * @deprecated    2.1.0
	 * @see           \Redshop\Template\Helper::getAjaxDetailBox
	 */
	public function getAjaxDetailboxTemplate($product)
	{
		return \Redshop\Template\Helper::getAjaxDetailBox($product);
	}

	/**
	 * Method for replace accessory data.
	 *
	 * @param   integer $product_id    Product ID
	 * @param   integer $relproduct_id Related product ID
	 * @param   array   $accessory     Accessories data.
	 * @param   string  $data_add      Template content
	 * @param   boolean $isChilds      True for accessory products is child.
	 * @param   array   $selectAcc     Selected accessory.
	 *
	 * @return  mixed|string
	 *
	 * @since       1.6.0
	 *
	 * @deprecated  2.1.0
	 *
	 * @throws Exception
	 */
	public function replaceAccessoryData($product_id = 0, $relproduct_id = 0, $accessory = array(), $data_add, $isChilds = false, $selectAcc = array())
	{
		return RedshopHelperProductAccessory::replaceAccessoryData($product_id, $relproduct_id, $accessory, $data_add, $isChilds, $selectAcc);
	}

	/**
	 * Method for replace attribute data with allow add to cart in template.
	 *
	 * @param   int    $productId         Product ID
	 * @param   int    $accessoryId       Accessory ID
	 * @param   int    $relatedProductId  Related product ID
	 * @param   array  $attributes        List of attribute data.
	 * @param   string $templateContent   HTML content of template.
	 * @param   object $attributeTemplate List of attribute templates.
	 * @param   bool   $isChild           Is child?
	 * @param   bool   $onlySelected      True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string                      HTML content with replaced data.
	 *
	 * @since        1.6.1
	 *
	 * @deprecated   2.0.3     Use RedshopHelperAttribute::replaceAttributeWithCartData() instead
	 */
	public function replaceAttributewithCartData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(),
	                                             $templateContent, $attributeTemplate = null, $isChild = false, $onlySelected = false)
	{
		return RedshopHelperAttribute::replaceAttributeWithCartData($productId, $accessoryId, $relatedProductId, $attributes, $templateContent,
			$attributeTemplate, $isChild, $onlySelected);
	}

	/**
	 * Method for get hidden attributes cart image
	 *
	 * @param   integer $product_id     Product Id
	 * @param   integer $property_id    Property Id
	 * @param   integer $subproperty_id Sub-property Id
	 *
	 * @return  string
	 *
	 * @deprecated 2.1.0 Use Redshop\Product\Image\Image::getHiddenAttributeCartImage
	 * @see        Redshop\Product\Image\Image::getHiddenAttributeCartImage
	 */
	public function get_hidden_attribute_cartimage($product_id, $property_id, $subproperty_id)
	{
		return Redshop\Product\Image\Image::getHiddenAttributeCartImage($product_id, $property_id, $subproperty_id);
	}

	/**
	 * Method for replace attribute data in template.
	 *
	 * @param   int    $productId          Product ID
	 * @param   int    $accessoryId        Accessory ID
	 * @param   int    $relatedProductId   Related product ID
	 * @param   array  $attributes         List of attribute data.
	 * @param   string $templateContent    HTML content of template.
	 * @param   object $attributeTemplate  List of attribute templates.
	 * @param   bool   $isChild            Is child?
	 * @param   array  $selectedAttributes Preselected attribute list.
	 * @param   int    $displayIndCart     Display in cart?
	 * @param   bool   $onlySelected       True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string
	 *
	 * @since       1.6.1
	 *
	 * @deprecated  2.0.3  Use RedshopHelperAttribute::replaceAttributeData() instead.
	 */
	public function replaceAttributeData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(), $templateContent,
	                                     $attributeTemplate = null, $isChild = false, $selectedAttributes = array(), $displayIndCart = 1, $onlySelected = false)
	{
		return RedshopHelperAttribute::replaceAttributeData($productId, $accessoryId, $relatedProductId, $attributes, $templateContent,
			$attributeTemplate, $isChild, $selectedAttributes, $displayIndCart, $onlySelected);
	}

	public function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $relatedprd_id = 0, $attribute_id = 0, $property_id = 0, $subatthtml = "", $layout = "", $selectSubproperty = array())
	{
		$attribute_table = "";
		$subproperty     = array();

		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/redshop.thumbscroller.min.js', false, true);
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
			$attributes      = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $attribute_id);
			$attributes      = $attributes[0];
			$subproperty_all = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property_id);
			// filter Out of stock data
			if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get('USE_STOCKROOM'))
			{
				$subproperty = \Redshop\Helper\Stockroom::getAttributeSubPropertyWithStock($subproperty_all);
			}
			else
			{
				$subproperty = $subproperty_all;
			}

			// Get stockroom and pre-order stockroom data.
			$subPropertyIds     = array_map(
				function ($item) {
					return $item->value;
				},
				$subproperty
			);
			$stockrooms         = RedshopHelperStockroom::getMultiSectionsStock($subPropertyIds, 'subproperty');
			$preOrderStockrooms = RedshopHelperStockroom::getMultiSectionsPreOrderStock($subPropertyIds, 'subproperty');

			foreach ($subproperty as $i => $item)
			{
				$subproperty[$i]->stock          = isset($stockrooms[$item->value]) ? (int) $stockrooms[$item->value] : 0;
				$subproperty[$i]->preorder_stock = isset($preOrderStockrooms[$item->value]) ? (int) $preOrderStockrooms[$item->value] : 0;
			}
		}

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

		$product         = RedshopHelperProduct::getProductById($product_id);
		$producttemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);

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
				$attribute_table     = $subatthtml;
				$attribute_table     .= '<span id="subprop_lbl" style="display:none;">'
					. JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . '</span>';
				$commonid            = $prefix . $product_id . '_' . $accessory_id . '_' . $attribute_id . '_'
					. $property_id;
				$subpropertyid       = 'subproperty_id_' . $commonid;
				$selectedsubproperty = 0;
				$imgAdded            = 0;

				$subproperty_woscrollerdiv = "";

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$attribute_table           = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$subproperty_woscrollerdiv .= "<div class='subproperty_main_outer' id='subproperty_main_outer'>";
				}

				$subprop_Arry    = array();
				$preselectSubPro = true;

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

					if (!empty($subproperty[$i]->subattribute_color_image))
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[$i]->subattribute_color_image))
						{
							$borderstyle    = ($selectedsubproperty == $subproperty[$i]->value) ? " 1px solid " : "";
							$thumbUrl       = RedshopHelperMedia::getImagePath(
								$subproperty[$i]->subattribute_color_image,
								'',
								'thumb',
								'subcolor',
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'),
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							$subprop_Arry[] = $thumbUrl;
							$style          = null;

							if ($subproperty[$i]->setdefault_selected && $preselectSubPro)
							{
								$style       = ' style="border: 1px solid;"';
								$preselectSubPro = false;
							}

							$subproperty_woscrollerdiv .= "<div id='" . $subpropertyid . "_subpropimg_"
								. $subproperty[$i]->value . "' class='subproperty_image_inner' ". $style ."><a onclick='setSubpropImage(\""
								. $product_id . "\",\"" . $subpropertyid . "\",\"" . $subproperty[$i]->value
								. "\");calculateTotalPrice(\"" . $product_id . "\",\"" . $relatedprd_id
								. "\");displayAdditionalImage(\"" . $product_id . "\",\"" . $accessory_id . "\",\""
								. $relatedprd_id . "\",\"" . $property_id . "\",\"" . $subproperty[$i]->value
								. "\");'><img class='redAttributeImage'  src='" . $thumbUrl . "' title='" . $subproperty[$i]->text . "'></a></div>";

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
							$attributes_subproperty_vat_show = RedshopHelperProduct::getProductTax($product_id, $subproperty [$i]->subattribute_color_price);

							$attributes_subproperty_oldprice_vat = RedshopHelperProduct::getProductTax($product_id, $attributes_subproperty_oldprice);
						}

						$attributes_subproperty_vat_show += $subproperty [$i]->subattribute_color_price;
						$attributes_subproperty_oldprice += $attributes_subproperty_oldprice_vat;

						if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) && (!$attributes->hide_attribute_price))
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name) . " (" . $subproperty [$i]->oprand . strip_tags(RedshopHelperProductPrice::formattedPrice($attributes_subproperty_vat_show)) . ")";
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

				$subproperties  = array_merge(
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
					$attributeListType             = 'select.genericlist';
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

				if ($imgAdded === 0 || $isAjax == 1)
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

	/**
	 * Method for display attribute price
	 *
	 * @param   integer $productId    Product IID
	 * @param   float   $showPrice    Show price
	 * @param   string  $templateHtml Template HTML
	 * @param   integer $userId       User ID
	 * @param   integer $applyTax     Is apply tax
	 * @param   array   $attributes   Attributes data.
	 *
	 * @return  float
	 *
	 * @deprecated    2.1.0
	 * @see           RedshopHelperProduct_Attribute::defaultAttributePrice
	 */
	public function defaultAttributeDataPrice($productId = 0, $showPrice = 0.0, $templateHtml = '', $userId = 0, $applyTax = 0, $attributes = array())
	{
		return RedshopHelperProduct_Attribute::defaultAttributePrice($productId, $showPrice, $templateHtml, $userId, $applyTax, $attributes);
	}

	/**
	 * Method for replace product properties add to cart
	 *
	 * @param   integer $product_id     Product ID
	 * @param   integer $property_id    Property ID
	 * @param   integer $category_id    Category ID
	 * @param   string  $commonid       DOM ID
	 * @param   integer $property_stock Property stock
	 * @param   string  $property_data  Property Data
	 * @param   array   $cart_template  Cart template
	 * @param   string  $data_add       Template content
	 *
	 * @return  mixed|string
	 * @throws  \Exception
	 *
	 * @deprecated 2.1.0 Redshop\Product\Property::replaceAddToCart
	 * @see        Redshop\Product\Property::replaceAddToCart
	 */
	public function replacePropertyAddtoCart($product_id = 0, $property_id = 0, $category_id = 0, $commonid = "", $property_stock = 0, $property_data = "", $cart_template = array(), $data_add = "")
	{
		return Redshop\Product\Property::replaceAddToCart($product_id, $property_id, $category_id, $commonid, $property_stock, $property_data, $cart_template, $data_add);
	}

	/**
	 * Method for render cart, replace tag in template
	 *
	 * @param   integer $product_id          Product Id
	 * @param   integer $category_id         Category Id
	 * @param   integer $accessory_id        Accessory Id
	 * @param   integer $relproduct_id       Related product Id
	 * @param   string  $data_add            Template content
	 * @param   boolean $isChilds            Is child product?
	 * @param   array   $userfieldArr        User fields
	 * @param   integer $totalatt            Total attributes
	 * @param   integer $totalAccessory      Total accessories
	 * @param   integer $count_no_user_field Total user fields
	 * @param   integer $module_id           Module Id
	 * @param   integer $giftcard_id         Giftcard Id
	 *
	 * @return  mixed|string
	 * @throws  \Exception
	 *
	 * @deprecated 2.1.0 Use Redshop\Cart\Render::render
	 * @see        Redshop\Cart\Render::render
	 */
	public function replaceCartTemplate($product_id = 0, $category_id = 0, $accessory_id = 0, $relproduct_id = 0, $data_add = "", $isChilds = false, $userfieldArr = array(), $totalatt = 0, $totalAccessory = 0, $count_no_user_field = 0, $module_id = 0, $giftcard_id = 0)
	{
		return Redshop\Cart\Render::replace($product_id, $category_id, $accessory_id, $relproduct_id, $data_add, $isChilds, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id, $giftcard_id);
	}

	/**
	 * Method for replace wishlist tag in template.
	 *
	 * @param   int    $productId       Product ID
	 * @param   string $templateContent HTML data of template content
	 *
	 * @return  string                    HTML data of replaced content.
	 *
	 * @since       1.5
	 *
	 * @deprecated  2.0.3    Use RedshopHelperWishlist::replaceWishlistTag() instead
	 */
	public function replaceWishlistButton($productId = 0, $templateContent = '', $formId = '')
	{
		return RedshopHelperWishlist::replaceWishlistTag($productId, $templateContent, $formId);
	}

	/**
	 * @param   integer $productId        Product ID
	 * @param   integer $categoryId       Category ID
	 * @param   string  $html             Template HTML
	 * @param   integer $isRelatedProduct Is related product.
	 *
	 * @return  string
	 *
	 * @deprecated    2.1.0
	 * @see           Redshop\Product\Compare::replaceCompareProductsButton
	 */
	public function replaceCompareProductsButton($productId = 0, $categoryId = 0, $html = "", $isRelatedProduct = 0)
	{
		return Redshop\Product\Compare::replaceCompareProductsButton($productId, $categoryId, $html, $isRelatedProduct);
	}

	public function makeAccessoryCart($attArr = array(), $product_id = 0, $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$data                  = Cart::getCartTemplate();
		$chktag                = \Redshop\Template\Helper::isApplyAttributeVat($data[0]->template_desc, $user_id);
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
					$acc_vat = RedshopHelperProduct::getProductTax($product_id, $attArr[$i]['accessory_price'], $user_id);
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
							$acc_propvat = RedshopHelperProduct::getProductTax($product_id, $propArr[$k]['property_price'], $user_id);
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
								$acc_subpropvat = RedshopHelperProduct::getProductTax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
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
						if ($setPropEqual && $setSubpropEqual && isset($subprovatprice[$t]))
						{
							$accessory_priceArr  = $this->makeTotalPriceByOprand(
								$accessory_price,
								$subprooprand[$t],
								$subprovatprice[$t]
							);
							$accessory_vatArr    = $this->makeTotalPriceByOprand(
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

	/**
	 * Method for get cart template
	 *
	 * @return  array
	 * @throws  \Exception
	 *
	 * @deprecated    2.1.0
	 * @see           \Redshop\Template\Helper::getCart
	 */
	public function getCartTemplate()
	{
		return Cart::getCartTemplate();
	}

	public function makeAttributeCart($attributes = array(), $productId = 0, $userId = 0, $newProductPrice = 0, $quantity = 1, $data = '')
	{
		$user = JFactory::getUser();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$sel               = 0;
		$selP              = 0;
		$applyVat          = \Redshop\Template\Helper::isApplyAttributeVat($data, $userId);
		$setPropEqual      = true;
		$setSubpropEqual   = true;
		$selectedAttributs = array();
		$selectedProperty  = array();
		$productOldprice   = 0;
		$productVatPrice   = 0;

		if ($newProductPrice != 0)
		{
			$productPrice = $newProductPrice;

			if ($productPrice > 0)
			{
				$productVatPrice = RedshopHelperProduct::getProductTax($productId, $productPrice, $userId);
			}
		}
		else
		{
			$productPrices = RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity, $data);

			// Using price without vat to proceed with calcualtion - we will apply vat in the end.
			$productPrice    = $productPrices['product_price_novat'];
			$productVatPrice = $productPrices['productVat'];
			$productOldprice = $productPrices['product_old_price_excl_vat'];
		}

		$isStock          = RedshopHelperStockroom::isStockExists($productId);
		$isPreorderStock  = RedshopHelperStockroom::isPreorderStockExists($productId);
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

			$properties = !empty($attributes[$i]['attribute_childs']) ? $attributes[$i]['attribute_childs'] : array();

			if (count($properties) > 0)
			{
				$displayAttribute++;
			}

			for ($k = 0, $kn = count($properties); $k < $kn; $k++)
			{
				$propertyVat             = 0;
				$propertyOperator        = $properties[$k]['property_oprand'];
				$propertyPriceWithoutVat = (isset($properties[$k]['property_price'])) ? $properties[$k]['property_price'] : 0;
				$property                = RedshopHelperProduct_Attribute::getAttributeProperties($properties[$k]['property_id']);
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
						$propertyVat = RedshopHelperProduct::getProductTax($productId, $propertyPriceWithoutVat, $userId);
					}
				}

				$isStock         = RedshopHelperStockroom::isStockExists($properties[$k]['property_id'], "property");
				$isPreorderStock = RedshopHelperStockroom::isPreorderStockExists($properties[$k]['property_id'], "property");

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

						if ($subPropertyOperator != '*' && $subPropertyOperator != '/')
						{
							$subPropertyVat = RedshopHelperProduct::getProductTax($productId, $subPropertyPriceWithoutVat, $userId);
						}
					}

					$isStock         = RedshopHelperStockroom::isStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);
					$isPreorderStock = RedshopHelperStockroom::isPreorderStockExists(
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
					$subPropertyPrices = $this->makeTotalPriceByOprand($productPrice, $subPropertiesOperator[$t], $subPropertiesPriceWithVat[$t]);

					$productPrice = $subPropertyPrices[1];

					$subPropertyOldPriceVats = $this->makeTotalPriceByOprand($productOldprice, $subPropertiesOperator[$t], $subPropertiesPrice[$t]);
					$productOldprice         = $subPropertyOldPriceVats[1];
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
			$productVatOldPrice = RedshopHelperProduct::getProductTax($productId, $productOldprice, $userId);
		}

		// Recalculate VAT if set to apply vat for attribute
		if ($applyVat)
		{
			$productVatPrice = RedshopHelperProduct::getProductTax($productId, $productPrice, $userId);
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

	/**
	 * Method for generate accessory of order.
	 *
	 * @param   integer $orderItemId Order item ID.
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.7  Use Redshop\Order\Helper::generateAccessories()
	 */
	public function makeAccessoryOrder($orderItemId = 0)
	{
		return Redshop\Order\Helper::generateAccessories($orderItemId);
	}

	public function makeAttributeOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $stock = 0, $export = 0, $data = '')
	{
		$chktag            = \Redshop\Template\Helper::isApplyAttributeVat($data);
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$orderItemdata     = RedshopHelperOrder::getOrderItemDetail(0, 0, $order_item_id);
		$cartAttributes    = array();

		$products = RedshopHelperProduct::getProductById($orderItemdata[0]->product_id);

		if (count($orderItemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $orderItemdata[0]->product_attribute;
			$quantity          = $orderItemdata[0]->product_quantity;
		}

		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		// Get Attribute middle template
		$attribute_middle_template = \Redshop\Template\Helper::getAttributeTemplateLoop($data);
		$attribute_final_template  = '';

		if (count($orderItemAttdata) > 0)
		{
			for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
			{
				$attribute = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);

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
					$property_price                  = $orderPropdata[$p]->section_price;
					$productAttributeCalculatedPrice = 0;

					if ($stock == 1)
					{
						RedshopHelperStockroom::manageStockAmount($orderPropdata[$p]->section_id, $quantity, $orderPropdata[$p]->stockroom_id, "property");
					}

					$property = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id);

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

					$disPrice           = '';
					$hideAttributePrice = count($attribute) > 0 ? $attribute[0]->hide_attribute_price : 0;

					if (strpos($data, '{product_attribute_price}') !== false)
					{
						if ($export == 1)
						{
							$disPrice = ' (' . $orderPropdata[$p]->section_oprand . Redshop::getConfig()->get('CURRENCY_SYMBOL') . $property_price . ')';
						}
						elseif (!$hideAttributePrice)
						{
							$disPrice = " (" . $orderPropdata[$p]->section_oprand . RedshopHelperProductPrice::formattedPrice($property_price) . ")";
						}
					}

					// Replace attribute property price and value
					$tmp_attribute_middle_template = str_replace("{product_attribute_value}", urldecode($orderPropdata[$p]->section_name), $tmp_attribute_middle_template);
					$tmp_attribute_middle_template = str_replace("{product_attribute_value_price}", $disPrice, $tmp_attribute_middle_template);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template .= $tmp_attribute_middle_template;

					// Initialize attribute child array
					$attributeChilds = array(
						'property_id'     => $orderPropdata[$p]->section_id,
						'property_name'   => $orderPropdata[$p]->section_name,
						'property_oprand' => $orderPropdata[$p]->section_oprand,
						'property_price'  => $property_price,
						'property_childs' => array()
					);

					$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

					for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
					{
						$subproperty_price = $orderSubpropdata[$sp]->section_price;

						if ($stock == 1)
						{
							RedshopHelperStockroom::manageStockAmount($orderSubpropdata[$sp]->section_id, $quantity, $orderSubpropdata[$sp]->stockroom_id, "subproperty");
						}

						$subproperty = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id);

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
					$productAttributeCalculatedPrice = RedshopHelperProductPrice::formattedPrice($productAttributeCalculatedPrice);
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
	 * @param   string $start  Starting string where you need to start search
	 * @param   string $end    Ending string where you need to end search
	 * @param   string $string Target string from where need to search
	 *
	 * @return  array            Matched string array
	 *
	 * @deprecated 2.1.0
	 */
	public function findStringBetween($start, $end, $string)
	{
		return \Redshop\Helper\Utility::findStringBetween($start, $end, $string);
	}

	/**
	 * Method to get attribute template loop
	 *
	 * @param   string $template Attribute Template data
	 *
	 * @return  string             Template middle data
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Template\Helper::getAttributeTemplateLoop
	 */
	public function getAttributeTemplateLoop($template)
	{
		return \Redshop\Template\Helper::getAttributeTemplateLoop($template);
	}

	public function makeAccessoryQuotation($quotation_item_id = 0, $quotation_status = 2)
	{
		$displayaccessory = "";
		$Itemdata         = RedshopHelperQuotation::getQuotationItemAccessoryDetail($quotation_item_id);

		if (count($Itemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>" . JText::_("COM_REDSHOP_ACCESSORY") . ":</div>";

			for ($i = 0, $in = count($Itemdata); $i < $in; $i++)
			{
				$displayaccessory .= "<div class='checkout_accessory_title'>" . urldecode($Itemdata[$i]->accessory_item_name) . " ";

				if ($quotation_status != 1 || ($quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == 1))
				{
					$displayaccessory .= "(" . RedshopHelperProductPrice::formattedPrice($Itemdata[$i]->accessory_price + $Itemdata[$i]->accessory_vat) . ")";
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
			$Itemdata         = RedshopHelperQuotation::getQuotationProduct(0, $quotation_item_id);
			$displayaccessory = $Itemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public function makeAttributeQuotation($quotation_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quotation_status = 2, $stock = 0)
	{
		$displayattribute  = "";
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$Itemdata          = RedshopHelperQuotation::getQuotationProduct(0, $quotation_item_id);

		if (count($Itemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $Itemdata[0]->product_attribute;
			$quantity          = $Itemdata[0]->product_quantity;
		}

		$ItemAttdata = RedshopHelperQuotation::getQuotationItemAttributeDetail(
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
			$userArr = RedshopHelperUser::createUserSession($user->id);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];
		//$shopperGroupId = $this->_userhelper->getShopperGroup($user->id);

		if ($user->id > 0)
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = '" . $user->id . "' AND sg.shopper_group_portal=1 ";
		else
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = " . (int) $shopperGroupId . " AND sg.shopper_group_portal=1";

		$this->_db->setQuery($catquery);
		$category_ids_obj = $this->_db->loadObjectList();
		if (empty($category_ids_obj))
		{
			return "";
		}
		else
		{
			$category_ids = $category_ids_obj[0]->shopper_group_categories;
		}

		// Sanitize ids
		$catIds = explode(',', $category_ids);
		$catIds = Joomla\Utilities\ArrayHelper::toInteger($catIds);

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

	/**
	 * Method for convert Unit
	 *
	 * @param   string $globalUnit Base conversation unit
	 * @param   string $calcUnit   Unit ratio which to convert
	 *
	 * @return  float                Unit ratio
	 *
	 * @deprecated 2.1.0
	 * @see        \Redshop\Helper\Utility::getUnitConversation
	 */
	public function getUnitConversation($globalUnit, $calcUnit)
	{
		return \Redshop\Helper\Utility::getUnitConversation($globalUnit, $calcUnit);
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

		$endtime = mktime(
			$product_download_clock,
			$product_download_clock_min,
			0,
			(int) date("m", $product_download_days_time),
			(int) date("d", $product_download_days_time),
			(int) date("Y", $product_download_days_time)
		);

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
	 * @param   int $questionId default 0
	 * @param   int $productId  default 0
	 * @param   int $faq        is FAQ
	 * @param   int $front      show in Front or Not
	 *
	 * @return  array
	 */
	public function getQuestionAnswer($questionId = 0, $productId = 0, $faq = 0, $front = 0)
	{
		$db    = JFactory::getDbo();
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
	 * @param   int $productId Product id
	 *
	 * @return  string
	 * @deprecated 2.1.0
	 */
	public function getProductRating($productId)
	{
		return Redshop\Product\Rating::getRating($productId);
	}

	/**
	 * Get Product Review List
	 *
	 * @param   int $productId Product id
	 *
	 * @return mixed
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
	 * @param   float  $firstPrice  First price
	 * @param   string $oprand      Operation symbol
	 * @param   float  $secondPrice Second price
	 *
	 * @return  float
	 *
	 * @deprecated  2.0.7
	 */
	public function calOprandPrice($firstPrice, $oprand, $secondPrice)
	{
		return RedshopHelperUtility::setOperandForValues($firstPrice, $oprand, $secondPrice);
	}

	/**
	 * Method for generate compare product
	 *
	 * @return  string  HTML layout of compare div
	 *
	 * @deprecated   2.1.0
	 * @see          Redshop\Product\Compare::generateCompareProduct
	 *
	 * @throws  \Exception
	 */
	public function makeCompareProductDiv()
	{
		return Redshop\Product\Compare::generateCompareProduct();
	}

	/**
	 * Function which will return product tag array form  given template
	 *
	 * @param   integer $section Section field
	 * @param   string  $html    Template HTML
	 *
	 * @return  array
	 *
	 * @deprecated   2.1.0
	 * @see          Redshop\Helper\Utility::getProductTags()
	 */
	public function product_tag($section, $html)
	{
		return \Redshop\Helper\Utility::getProductTags($section, $html);
	}

	public function getJcommentEditor($product = array(), $data_add = "")
	{
		$app             = JFactory::getApplication();
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
			$accessoryData   = explode("@@", $data['accessory_data']);
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
					$accessoryPropertyData2  = explode(',,', $accessoryPropertyData1[$ia]);
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
				$countAccessorySubroperty  = count($accessorySubpropertyData1);

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
				$countProperty1     = count($acc_property_data1);

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
			$countSubproperty     = count($acc_subproperty_data);

			for ($ia = 0; $ia < $countSubproperty; $ia++)
			{
				$acc_subproperty_data1 = @explode('::', $acc_subproperty_data[$ia]);
				$countSubproperty1     = count($acc_subproperty_data1);

				for ($ip = 0; $ip < $countSubproperty1; $ip++)
				{
					$acc_subproperty_data2 = explode(',,', $acc_subproperty_data1[$ip]);
					$countSubproperty2     = count($acc_subproperty_data2);

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

	/**
	 * Method for replace instock tag
	 *
	 * @param   integer $product_id         Product id
	 * @param   string  $data_add           Template content
	 * @param   array   $attributes         Attributes
	 * @param   array   $attribute_template Attribute template
	 *
	 * @return  mixed
	 *
	 * @deprecated 2.1.0 Redshop\Product\Stock::replaceInStock
	 * @see        Redshop\Product\Stock::replaceInStock
	 */
	public function replaceProductInStock($product_id = 0, $data_add, $attributes = array(), $attribute_template = array())
	{
		return Redshop\Product\Stock::replaceInStock($product_id, $data_add, $attributes, $attribute_template);
	}

	/**
	 * Method for get child products of specific product
	 *
	 * @param   integer $productId Product ID
	 *
	 * @return  array
	 * @deprecated 2.1.0
	 * @see        RedshopHelperProduct::getChildProduct
	 */
	public function getChildProduct($productId = 0)
	{
		return RedshopHelperProduct::getChildProduct($productId);
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

		if ($product_parent_id !== 0)
		{
			$parent_id = $this->getMainParentProduct($product_parent_id);
		}

		return $parent_id;
	}

	/**
	 * Get formatted number
	 *
	 * @param   float   $price         Price amount
	 * @param   boolean $convertSigned True for convert negative price to absolution price.
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

					$dateStart = mktime(
						0,
						0,
						0,
						(int) date('m', $mainsplit_date[0]),
						(int) date('d', $mainsplit_date[0]),
						(int) date('Y', $mainsplit_date[0])
					);

					$dateEnd = mktime(
						23,
						59,
						59,
						(int) date('m', $mainsplit_date[1]),
						(int) date('d', $mainsplit_date[1]),
						(int) date('Y', $mainsplit_date[1])
					);

					$todayStart = mktime(
						0,
						0,
						0,
						(int) date('m'),
						(int) date('d'),
						(int) date('Y')
					);

					$todayEnd = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

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

	/**
	 * Method for get category compare product template
	 *
	 * @param   integer $cid Category ID
	 *
	 * @return  integer
	 *
	 * @deprecated    2.1.0
	 * @see           Redshop\Product\Compare::getCategoryCompareTemplate
	 */
	public function getCategoryCompareTemplate($cid)
	{
		return Redshop\Product\Compare::getCategoryCompareTemplate($cid);
	}

	public function getProductCaterories($productId, $displayLink = 0)
	{
		$prodCatsObjectArray = array();
		$db                  = JFactory::getDbo();
		$query               = $db->getQuery(true)
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

			if (!empty($parentCat) && $parentCat->parent_id)
			{
				$pCat = $parentCat->name;

				$query = $db->getQuery(true)
					->select($db->qn('parent_id'))
					->select($db->qn('name'))
					->from($db->qn('#__redshop_category'))
					->where($db->qn('id') . ' = ' . $db->q((int) $parentCat->parent_id));

				$pparentCat = $db->setQuery($query)->loadObject();

				if (!empty($pparentCat) && $pparentCat->parent_id)
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
				$catItem   = RedshopHelperRouter::getCategoryItemid($row->id);

				if (!(boolean) $catItem)
				{
					$catItem = JFactory::getApplication()->input->getInt('Itemid');
				}

				$catlink = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
					. $row->id . '&Itemid=' . $catItem);
			}

			$prodCatsObject        = new stdClass;
			$prodCatsObject->name  = $ppCat . $pspacediv . $pCat . $spacediv . $row->name;
			$prodCatsObject->link  = $catlink;
			$prodCatsObjectArray[] = $prodCatsObject;
		}

		return $prodCatsObjectArray;
	}

	/**
	 * Method for get display main image
	 *
	 * @param   integer $product_id     Product Id
	 * @param   integer $property_id    Property Id
	 * @param   integer $subproperty_id Sub-property Id
	 * @param   integer $pw_thumb       Width of thumb
	 * @param   integer $ph_thumb       Height of thumb
	 * @param   string  $redview        Red view
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @deprecated    2.1.0 Redshop\Product\Image\Image::getDisplayMain()
	 * @see           Redshop\Product\Image\Image::getDisplayMain
	 */
	public function getdisplaymainImage($product_id = 0, $property_id = 0, $subproperty_id = 0, $pw_thumb = 0, $ph_thumb = 0, $redview = "")
	{
		return Redshop\Product\Image\Image::getDisplayMain($product_id, $property_id, $subproperty_id, $pw_thumb, $ph_thumb, $redview);
	}

	/**
	 * Method for get additional images of product.
	 *
	 * @param   integer $productId        Id of product
	 * @param   integer $accessoryId      Accessory Id
	 * @param   integer $relatedProductId Related product ID
	 * @param   integer $propertyId       Property ID
	 * @param   integer $subPropertyId    Sub-property ID
	 * @param   integer $mainImgWidth     Main image width
	 * @param   integer $mainImgHeight    Main image height
	 * @param   string  $redView          redshop View
	 * @param   string  $redLayout        redshop layout
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @deprecated    2.0.7
	 *
	 * @see           RedshopHelperProductTag::displayAdditionalImage
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

	public function getProductFinderDatepickerValue($templatedata = "", $productid = 0, $fieldsArray = array(), $giftcard = 0)
	{
		if (empty($fieldsArray))
		{
			return $templatedata;
		}

		foreach ($fieldsArray as $fieldArray)
		{
			$fieldValueArray = RedshopHelperExtrafields::getData($fieldArray->id, 17, $productid);

			if ($fieldValueArray->data_txt != ""
				&& $fieldArray->show_in_front == 1
				&& $fieldArray->published == 1
				&& $giftcard == 0)
			{
				$templatedata = str_replace('{' . $fieldArray->name . '}', $fieldValueArray->data_txt, $templatedata);
				$templatedata = str_replace('{' . $fieldArray->name . '_lbl}', $fieldArray->title, $templatedata);
			}
			else
			{
				$templatedata = str_replace('{' . $fieldArray->name . '}', "", $templatedata);
				$templatedata = str_replace('{' . $fieldArray->name . '_lbl}', "", $templatedata);
			}
		}

		return $templatedata;
	}

	/**
	 * Parse related product template
	 *
	 * @param   string  $templateDesc Template Contents
	 * @param   integer $product_id   Product Id
	 *
	 * @todo    Move this functionality to library helper and convert this code into JLayout
	 *
	 * @return  string   Parsed Template HTML
	 */
	public function getRelatedtemplateView($templateDesc, $product_id)
	{
		$relatedProduct  = $this->getRelatedProduct($product_id);
		$relatedTemplate = \Redshop\Template\Helper::getRelatedProduct($templateDesc);
		$fieldArray      = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		if (null === $relatedTemplate)
		{
			$templateDesc = RedshopHelperText::replaceTexts($templateDesc);

			return $templateDesc;
		}

		if (!empty($relatedProduct)
			&& strpos($relatedTemplate->template_desc, "{related_product_start}") !== false
			&& strpos($relatedTemplate->template_desc, "{related_product_end}") !== false)
		{
			$related_template_data = '';
			$product_start         = explode("{related_product_start}", $relatedTemplate->template_desc);
			$product_end           = explode("{related_product_end}", $product_start [1]);

			$tempdata_div_start  = $product_start [0];
			$tempdata_div_middle = $product_end [0];
			$tempdata_div_end    = $product_end [1];

			$attribute_template = \Redshop\Template\Helper::getAttribute($tempdata_div_middle);

			// Extra field display
			$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);

			for ($r = 0, $rn = count($relatedProduct); $r < $rn; $r++)
			{
				$related_template_data .= $tempdata_div_middle;

				$dispatcher->trigger('onPrepareRelatedProduct', array(&$related_template_data, $relatedProduct[$r]));

				$ItemData = $this->getMenuInformation(0, 0, '', 'product&pid=' . $relatedProduct[$r]->product_id);

				if (count($ItemData) > 0)
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$catidmain = $relatedProduct[$r]->cat_in_sefurl;
					$pItemid   = RedshopHelperRouter::getItemId($relatedProduct[$r]->product_id, $catidmain);
				}

				$rlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $relatedProduct[$r]->product_id . '&cid=' . $relatedProduct[$r]->cat_in_sefurl . '&Itemid=' . $pItemid);

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
				$relimage              = Redshop\Product\Image\Image::getImage($relatedProduct [$r]->product_id, $rlink, $rpw_thumb, $rph_thumb);
				$related_template_data = str_replace($rpimg_tag, $relimage . $hidden_thumb_image, $related_template_data);

				if (strpos($related_template_data, "{relproduct_link}") !== false)
				{
					$rpname = "<a href='" . $rlink . "' title='" . $relatedProduct [$r]->product_name . "'>"
						. RedshopHelperUtility::maxChars($relatedProduct [$r]->product_name, Redshop::getConfig()->getInt('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_TITLE_END_SUFFIX'))
						. "</a>";
				}
				else
				{
					$rpname = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_name, Redshop::getConfig()->getInt('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_TITLE_END_SUFFIX'));
				}

				$rpdesc       = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_desc, Redshop::getConfig()->getInt('RELATED_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_DESC_END_SUFFIX'));
				$rp_shortdesc = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_s_desc, Redshop::getConfig()->getInt('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'));

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
				$related_template_data = str_replace("{relproduct_number}", $relatedProduct [$r]->product_number, $related_template_data);
				$related_template_data = str_replace("{relproduct_s_desc}", $rp_shortdesc, $related_template_data);
				$related_template_data = str_replace("{relproduct_desc}", $rpdesc, $related_template_data);

				// ProductFinderDatepicker Extra Field Start
				$related_template_data = $this->getProductFinderDatepickerValue($related_template_data, $relatedProduct[$r]->product_id, $fieldArray);

				if (strpos($related_template_data, "{manufacturer_name}") !== false || strpos($related_template_data, "{manufacturer_link}") !== false)
				{
					$manufacturer = RedshopEntityManufacturer::getInstance($relatedProduct[$r]->manufacturer_id)->getItem();

					if (!empty($manufacturer))
					{
						$man_url               = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $relatedProduct[$r]->manufacturer_id . '&Itemid=' . $pItemid);
						$manufacturerLink      = "<a class='btn btn-primary' href='" . $man_url . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
						$related_template_data = str_replace("{manufacturer_name}", $manufacturer->name, $related_template_data);
						$related_template_data = str_replace("{manufacturer_link}", $manufacturerLink, $related_template_data);
					}
					else
					{
						$related_template_data = str_replace("{manufacturer_name}", '', $related_template_data);
						$related_template_data = str_replace("{manufacturer_link}", '', $related_template_data);
					}
				}

				$rmore                 = '<a href="' . $rlink . '" title="' . $relatedProduct [$r]->product_name . '">'
					. JText::_('COM_REDSHOP_READ_MORE')
					. '</a>';
				$related_template_data = str_replace("{read_more}", $rmore, $related_template_data);
				$related_template_data = str_replace("{read_more_link}", $rlink, $related_template_data);

				/*
				 *  related product Required Attribute start
				 * 	this will parse only Required Attributes
				 */
				$relid          = $relatedProduct [$r]->product_id;
				$attributes_set = array();

				if ($relatedProduct [$r]->attribute_set_id > 0)
				{
					$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $relatedProduct [$r]->attribute_set_id);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($relid);
				$attributes = array_merge($attributes, $attributes_set);

				$related_template_data = $this->replaceAttributeData($relatedProduct[$r]->mainproduct_id, 0, $relatedProduct[$r]->product_id, $attributes, $related_template_data, $attribute_template);

				// Check product for not for sale
				$related_template_data = $this->getProductNotForSaleComment($relatedProduct[$r], $related_template_data, $attributes, 1);

				$related_template_data = Redshop\Cart\Render::replace($relatedProduct[$r]->mainproduct_id, 0, 0, $relatedProduct[$r]->product_id, $related_template_data, false, array(), count($attributes), 0, 0);
				$related_template_data = Redshop\Product\Compare::replaceCompareProductsButton($relatedProduct[$r]->product_id, 0, $related_template_data, 1);
				$related_template_data = Redshop\Product\Stock::replaceInStock($relatedProduct[$r]->product_id, $related_template_data);

				$related_template_data = $this->getProductOnSaleComment($relatedProduct[$r], $related_template_data);
				$related_template_data = $this->getSpecialProductComment($relatedProduct[$r], $related_template_data);

				$isCategorypage = (JFactory::getApplication()->input->getCmd('view') == "category") ? 1 : 0;

				//  Extra field display
				$related_template_data = $this->getExtraSectionTag($extraFieldName, $relatedProduct[$r]->product_id, "1", $related_template_data, $isCategorypage);

				// Related product attribute price list
				$related_template_data = $this->replaceAttributePriceList($relatedProduct[$r]->product_id, $related_template_data);

				if (strpos($related_template_data, "{wishlist_link}") !== false)
				{
					$wishlistLink          = "<div class=\"wishlist\">" . $this->replaceWishlistButton($relatedProduct[$r]->product_id, '{wishlist_link}') . "</div>";
					$related_template_data = str_replace("{wishlist_link}", $wishlistLink, $related_template_data);
				}

				$childproduct = RedshopHelperProduct::getChildProduct($relatedProduct[$r]->product_id);

				if (count($childproduct) > 0)
				{
					$attributes = array();
				}
				else
				{
					// Get attributes
					$attributes_set = array();

					if ($relatedProduct[$r]->attribute_set_id > 0)
					{
						$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $relatedProduct[$r]->attribute_set_id, 0, 1);
					}

					$attributes = RedshopHelperProduct_Attribute::getProductAttribute($relatedProduct[$r]->product_id);
					$attributes = array_merge($attributes, $attributes_set);
				}

				$totalatt = count($attributes);

				$attributeproductStockStatus = array();

				$productAvailabilityDate = strstr($related_template_data, "{product_availability_date}");
				$stockNotifyFlag         = strstr($related_template_data, "{stock_notify_flag}");
				$stockStatus             = strstr($related_template_data, "{stock_status");

				if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
				{
					$attributeproductStockStatus = $this->getproductStockStatus($relatedProduct[$r]->product_id, $totalatt);
				}

				$related_template_data = \Redshop\Helper\Stockroom::replaceProductStockData(
					$relatedProduct[$r]->product_id,
					0,
					0,
					$related_template_data,
					$attributeproductStockStatus
				);

				$dispatcher->trigger('onAfterDisplayRelatedProduct', array(&$related_template_data, $relatedProduct[$r]));
			}

			$related_template_data = $tempdata_div_start . $related_template_data . $tempdata_div_end;

			$templateDesc = str_replace("{related_product:$relatedTemplate->name}", $related_template_data, $templateDesc);
			$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
		}
		else
		{
			$templateDesc = str_replace("{related_product:$relatedTemplate->name}", "", $templateDesc);
		}

		$templateDesc = RedshopHelperText::replaceTexts($templateDesc);

		return $templateDesc;
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
		$attributes = RedshopHelperProduct_Attribute::getProductAttribute($id, 0, 0, 1);

		$k = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$attribute      = $attributes[$i];
			$attribute_name = $attribute->text;
			$attribute_id   = $attribute->value;
			$propertys      = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute_id);

			for ($p = 0, $pn = count($propertys); $p < $pn; $p++)
			{
				$property = $propertys[$p];

				$property_id             = $property->value;
				$property_name           = $property->text;
				$proprty_price           = $property->property_price;
				$property_formated_price = RedshopHelperProductPrice::formattedPrice($proprty_price);
				$proprty_oprand          = $property->oprand;

				$output .= '<div class="related_plist_property_name' . $k . '">' . $property_formated_price . '</div>';

				$subpropertys = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property_id);

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
		$db    = JFactory::getDbo();
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
		$filter_products = array();

		for ($s = 0, $sn = count($products); $s < $sn; $s++)
		{
			$product = $products[$s];
			$pid     = $product->product_id;

			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes   = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes   = array_merge($attributes, $attributes_set);
			$totalatt     = count($attributes);
			$stock_amount = RedshopHelperStockroom::getFinalStockofProduct($pid, $totalatt);

			if ($stock_amount)
			{
				$filter_products[] = $products[$s];
			}
		}

		return $filter_products;
	}

	/**
	 * @param   integer $productId             Product id
	 * @param   integer $totalAttribute        Total attribute
	 * @param   integer $selectedPropertyId    Selected property id
	 * @param   integer $selectedsubpropertyId Selected sub property id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 * @throws  \Exception
	 */
	public function getproductStockStatus($productId = 0, $totalAttribute = 0, $selectedPropertyId = 0, $selectedsubpropertyId = 0)
	{
		return RedshopEntityProduct::getInstance($productId)->getStockstatus($totalAttribute, $selectedPropertyId, $selectedsubpropertyId);
	}

	public function replaceProductStockdata($product_id, $property_id, $subproperty_id, $data_add, $stockStatusArray)
	{
		return \Redshop\Helper\Stockroom::replaceProductStockData($product_id, $property_id, $subproperty_id, $data_add, $stockStatusArray);
	}

	/**
	 * Check already notified user
	 *
	 * @param   int $user_id        User id
	 * @param   int $product_id     Product id
	 * @param   int $property_id    Property id
	 * @param   int $subproperty_id Sub property id
	 *
	 * @deprecated  1.5 Use RedshopHelperStockroom::isAlreadyNotifiedUser instead
	 *
	 * @return mixed
	 */
	public function isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id)
	{
		return RedshopHelperStockroom::isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id);
	}

	/**
	 * @param   array   $cart
	 * @param   integer $orderId
	 * @param   integer $sectionId
	 *
	 * @return  false|mixed|void
	 */
	public function insertPaymentShippingField($cart = array(), $orderId = 0, $sectionId = 18)
	{
		$fieldsList = RedshopHelperExtrafields::getSectionFieldList($sectionId, 1);

		if (empty($fieldsList))
		{
			return;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->quoteName('#__redshop_fields_data'))
			->columns($db->quoteName(array('fieldid', 'data_txt', 'itemid', 'section')));

		foreach ($fieldsList as $fieldList)
		{
			$userFields = '';

			if (isset($cart['extrafields_values']))
			{
				$userFields = $cart['extrafields_values'][$fieldList->name];
			}

			if (!empty(trim($userFields)))
			{
				$values = array(
					(int) $fieldList->id,
					$db->quote(addslashes($userFields)),
					(int) $orderId,
					$db->quote($sectionId)
				);
				$query->values(implode(',', $values));
			}
		}

		return $db->setQuery($query)->execute();
	}

	/**
	 * @param   object  $order     Order object
	 * @param   integer $sectionId Section Id
	 *
	 * @return  string
	 */
	public function getPaymentandShippingExtrafields($order, $sectionId)
	{
		$fieldsList = RedshopHelperExtrafields::getSectionFieldList($sectionId, 1);
		$resultArr  = array();

		foreach ($fieldsList as $field)
		{
			$result = RedshopHelperExtrafields::getData($field->id, $sectionId, $order->order_id);

			if (!is_null($result) && $result->data_txt != "" && $field->show_in_front == 1)
			{
				$resultArr[] = $result->title . " : " . $result->data_txt;
			}
		}

		$return = "";

		if (!empty($resultArr))
		{
			$return = implode("<br/>", $resultArr);
		}

		return $return;
	}

	/**
	 * Return checked if product is in session of compare product cart else blank
	 *
	 * @param   integer $productId Id of product
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.7
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

		foreach ($compareProducts[$idx] as $compareProduct)
		{
			if ($compareProduct["product_id"] == $productId)
			{
				return 'checked';
			}
		}

		return '';
	}

	/**
	 * Get Max and Min of Product Price
	 *
	 * @param   int $productId Product Id
	 *
	 * @return  array
	 */
	public function getProductMinMaxPrice($productId)
	{
		$attributes           = RedshopHelperProduct_Attribute::getProductAttribute($productId);
		$propertyIds          = array();
		$subPropertyIds       = array();
		$propertyPriceList    = array();
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
			$productPriceList = \Redshop\Repositories\Product::getPrices((int) $productId);
		}

		if (!empty($propertyIds))
		{
			$query             = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('property'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $propertyIds) . ')');
			$propertyPriceList = $db->setQuery($query)->loadColumn();
		}

		if (!empty($subPropertyIds))
		{
			$query                = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('subproperty'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $subPropertyIds) . ')');
			$subPropertyPriceList = $db->setQuery($query)->loadColumn();
		}

		$productPriceList    = array_unique(array_merge($productPriceList, $propertyPriceList, $subPropertyPriceList));
		$productPrice['min'] = min($productPriceList);
		$productPrice['max'] = max($productPriceList);

		return $productPrice;
	}
}
