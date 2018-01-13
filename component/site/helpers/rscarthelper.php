<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

class rsCarthelper
{
	public $_table_prefix = null;

	public $_db = null;

	public $_session = null;

	public $_order_functions = null;

	public $_extra_field = null;

	public $_redhelper = null;

	public $_producthelper = null;

	public $_show_with_vat = 0;

	public $_shippinghelper = null;

	public $_globalvoucher = 0;

	protected static $instance = null;

	protected $input;

	/**
	 * Returns the rsCarthelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsCarthelper  The rsCarthelper object
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
		$this->_table_prefix    = '#__redshop_';
		$this->_db              = JFactory::getDBO();
		$this->_session         = JFactory::getSession();
		$this->_order_functions = order_functions::getInstance();
		$this->_extra_field     = extra_field::getInstance();
		$this->_extraFieldFront = extraField::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_producthelper   = productHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
		$this->input            = JFactory::getApplication()->input;
	}

	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $discount
	 * @param int    $check
	 * @param int    $quotationMode
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7  Use RedshopHelperCartTag::replaceTax() instead.
	 */
	public function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{
		return RedshopHelperCartTag::replaceTax($data, $amount, $discount, $check, $quotationMode);
	}

	/**
	 * Calculate tax after Discount is apply
	 *
	 * @param   float  $tax       Tax amount
	 * @param   float  $discount  Discount amount.
	 *
	 * @return  float             Tax after apply discount.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::calculateTaxAfterDiscount() instead.
	 **/
	public function calculateTaxafterDiscount($tax = 0.0, $discount = 0.0)
	{
		return RedshopHelperCart::calculateTaxAfterDiscount($tax, $discount);
	}

	/**
	 * replace Conditional tag from Redshop Discount
	 *
	 * @param   string  $template       Template
	 * @param   int     $discount       Discount
	 * @param   int     $subTotal       Subtotal
	 * @param   int     $quotationMode  Quotation mode
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7  Use RedshopHelperCartTag::replaceDiscount() instead.
	 */
	public function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
	{
		return RedshopHelperCartTag::replaceDiscount($template, $discount, $subTotal, $quotationMode);
	}

	/**
	 * replace Conditional tag from Redshop payment Discount/charges
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $cart
	 * @param string $payment_oprand
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.7
	 */
	public function replacePayment($data = '', $amount = 0, $cart = 0, $payment_oprand = '-')
	{
		return RedshopHelperPayment::replaceConditionTag($data, $amount, $cart, $payment_oprand);
	}

	/**
	 * Calculate payment Discount/charges
	 *
	 * @param   float   $total        Total
	 * @param   object  $paymentinfo  Payment information
	 * @param   float   $finalAmount  Final amount
	 *
	 * @return  array
	 *
	 * @deprecated  2.1.0
	 *
	 * @see RedshopHelperPayment::calculate()
	 */
	public function calculatePayment($total, $paymentinfo, $finalAmount)
	{
		return RedshopHelperPayment::calculate($total, $paymentinfo, $finalAmount);
	}

	/**
	 * Method for replace Billing Address
	 *
	 * @param   string   $content         Template content
	 * @param   object   $billingAddress  Billing data
	 * @param   boolean  $sendMail        Is in send mail?
	 *
	 * @return  mixed
	 * @deprecated    2.0.7
	 *
	 * @see RedshopHelperBillingTag::replaceBillingAddress()
	 */
	public function replaceBillingAddress($content, $billingAddress, $sendMail = false)
	{
		return RedshopHelperBillingTag::replaceBillingAddress($content, $billingAddress, $sendMail);
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param $data
	 * @param $shippingaddresses
	 *
	 * @return mixed
	 */
	public function replaceShippingAddress($data, $shippingaddresses, $sendmail = false)
	{
		if (strpos($data, '{shipping_address_start}') !== false && strpos($data, '{shipping_address_end}') !== false)
		{
			$template_sdata = explode('{shipping_address_start}', $data);
			$template_edata = explode('{shipping_address_end}', $template_sdata[1]);
			$shippingdata   = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? $template_edata[0] : '';

			$shipping_extrafield = '';

			if (isset($shippingaddresses) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
			{
				$extra_section = ($shippingaddresses->is_company == 1) ? 15 : 14;

				if ($shippingaddresses->is_company == 1 && $shippingaddresses->company_name != "")
				{
					$shippingdata = str_replace("{companyname}", $shippingaddresses->company_name, $shippingdata);
					$shippingdata = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $shippingdata);
				}

				if ($shippingaddresses->firstname != "")
				{
					$shippingdata = str_replace("{firstname}", $shippingaddresses->firstname, $shippingdata);
					$shippingdata = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $shippingdata);
				}

				if ($shippingaddresses->lastname != "")
				{
					$shippingdata = str_replace("{lastname}", $shippingaddresses->lastname, $shippingdata);
					$shippingdata = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $shippingdata);
				}

				if ($shippingaddresses->address != "")
				{
					$shippingdata = str_replace("{address}", $shippingaddresses->address, $shippingdata);
					$shippingdata = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $shippingdata);
				}

				if ($shippingaddresses->zipcode != "")
				{
					$shippingdata = str_replace("{zip}", $shippingaddresses->zipcode, $shippingdata);
					$shippingdata = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $shippingdata);
				}

				if ($shippingaddresses->city != "")
				{
					$shippingdata = str_replace("{city}", $shippingaddresses->city, $shippingdata);
					$shippingdata = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $shippingdata);
				}

				$cname = $this->_order_functions->getCountryName($shippingaddresses->country_code);

				if ($cname != "")
				{
					$shippingdata = str_replace("{country}", JText::_($cname), $shippingdata);
					$shippingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $shippingdata);
				}

				$sname = $this->_order_functions->getStateName($shippingaddresses->state_code, $shippingaddresses->country_code);

				if ($sname != "")
				{
					$shippingdata = str_replace("{state}", $sname, $shippingdata);
					$shippingdata = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $shippingdata);
				}

				if ($shippingaddresses->phone != "")
				{
					$shippingdata = str_replace("{phone}", $shippingaddresses->phone, $shippingdata);
					$shippingdata = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $shippingdata);
				}

				// Additional functionality - more flexible way
				$shippingdata = Redshop\Helper\ExtraFields::displayExtraFields($extra_section, $shippingaddresses->users_info_id, "", $shippingdata);

				$shipping_extrafield = $this->_extra_field->list_all_field_display($extra_section, $shippingaddresses->users_info_id, 1);
			}

			$shippingdata = str_replace("{companyname}", "", $shippingdata);
			$shippingdata = str_replace("{companyname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{firstname}", "", $shippingdata);
			$shippingdata = str_replace("{firstname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{lastname}", "", $shippingdata);
			$shippingdata = str_replace("{lastname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{address}", "", $shippingdata);
			$shippingdata = str_replace("{address_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{zip}", "", $shippingdata);
			$shippingdata = str_replace("{zip_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{city}", "", $shippingdata);
			$shippingdata = str_replace("{city_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{country}", "", $shippingdata);
			$shippingdata = str_replace("{country_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{state}", "", $shippingdata);
			$shippingdata = str_replace("{state_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{phone}", "", $shippingdata);
			$shippingdata = str_replace("{phone_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{shipping_extrafield}", $shipping_extrafield, $shippingdata);

			$data = $template_sdata[0] . $shippingdata . $template_edata[1];
		}
		elseif (strpos($data, '{shipping_address}') !== false)
		{
			$shipadd = '';

			if (isset($shippingaddresses) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
			{
				$shippingLayout = 'cart.shipping';

				if ($sendmail)
				{
					$shippingLayout = 'mail.shipping';
				}

				JPluginHelper::importPlugin('redshop_shipping');
				$dispatcher = RedshopHelperUtility::getDispatcher();
				$dispatcher->trigger('onBeforeRenderShippingAddress', array(&$shippingaddresses));

				$shipadd = RedshopLayoutHelper::render(
					$shippingLayout,
					array('shippingaddresses' => $shippingaddresses),
					null,
					array('client' => 0)
				);

				if ($shippingaddresses->is_company == 1)
				{
					// Additional functionality - more flexible way
					$data = Redshop\Helper\ExtraFields::displayExtraFields(15, $shippingaddresses->users_info_id, "", $data);
				}
				else
				{
					// Additional functionality - more flexible way
					$data = Redshop\Helper\ExtraFields::displayExtraFields(14, $shippingaddresses->users_info_id, "", $data);
				}
			}

			$data = str_replace("{shipping_address}", $shipadd, $data);
		}

		$shippingtext = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$data         = str_replace("{shipping_address}", "", $data);
		$data         = str_replace("{shipping_address_information_lbl}", $shippingtext, $data);

		return $data;
	}

	/**
	 * Replace shipping method
	 *
	 * @param   stdClass  $shipping  Shipping data
	 * @param   string    $content   Template content
	 *
	 * @return  string
	 *
	 * @deprecated   2.0.7
	 *
	 * @see RedshopHelperShippingTag::replaceShippingMethod()
	 */
	public function replaceShippingMethod($shipping, $content = '')
	{
		return RedshopHelperShippingTag::replaceShippingMethod($shipping, $content);
	}

	public function replaceCartItem($data, $cart = array(), $replace_button, $quotation_mode = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$prdItemid  = $this->input->getInt('Itemid');
		$Itemid     = RedshopHelperRouter::getCheckoutItemId();
		$url        = JURI::base(true);
		$mainview   = $this->input->getCmd('view');

		if ($Itemid == 0)
		{
			$Itemid = $this->input->getInt('Itemid');
		}

		$cart_tr = '';

		$idx        = $cart['idx'];
		$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
		{
			$delete_img = Redshop::getConfig()->get('ADDTOCART_DELETE');
		}
		else
		{
			$delete_img = "defaultcross.png";
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$cart_mdata = $data;

			// Plugin support:  Process the product plugin for cart item
			$dispatcher->trigger('onCartItemDisplay', array(&$cart_mdata, $cart, $i));

			$quantity = $cart[$i]['quantity'];

			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				$giftcard_id  = $cart[$i]['giftcard_id'];
				$giftcardData = $this->_producthelper->getGiftcardData($giftcard_id);
				$link         = JRoute::_('index.php?option=com_redshop&view=giftcard&gid=' . $giftcard_id . '&Itemid=' . $Itemid);
				$reciverInfo = '<div class="reciverInfo">' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL') . ': ' . $cart[$i]['reciver_name']
					. '<br />' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL') . ': ' . $cart[$i]['reciver_email'] . '</div>';

				$product_name = "<div  class='product_name'><a href='" . $link . "'>" . $giftcardData->giftcard_name . "</a></div>" . $reciverInfo;

				if (strpos($cart_mdata, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "<div  class=\"product_name\">" . $giftcardData->giftcard_name . "</div><" . $reciverInfo;
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $cart_mdata);

					if (strpos($cart_mdata, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
				}

				$cart_mdata = str_replace("{product_attribute}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_accessory}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_wrapper}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_old_price}", '', $cart_mdata);
				$cart_mdata = str_replace("{vat_info}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_number_lbl}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_number}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$cart_mdata = str_replace("{product_total_price}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_price}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price}", RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price']), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price}", RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity'], true), $cart_mdata);
				}

				$cart_mdata     = str_replace("{if product_on_sale}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_on_sale end if}", '', $cart_mdata);

				$thumbUrl = RedShopHelperImages::getImagePath(
					$giftcardData->giftcard_image,
					'',
					'thumb',
					'giftcard',
					Redshop::getConfig()->get('CART_THUMB_WIDTH'),
					Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$giftcard_image = "&nbsp;";

				if ($thumbUrl)
				{
					$giftcard_image = "<div  class='giftcard_image'><img src='" . $thumbUrl . "'></div>";
				}

				$cart_mdata     = str_replace("{product_thumb_image}", $giftcard_image, $cart_mdata);
				$user_fields    = $this->_producthelper->GetProdcutUserfield($i, 13);
				$cart_mdata     = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$cart_mdata     = str_replace("{product_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price']), $cart_mdata);
				$cart_mdata     = str_replace("{product_total_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity']), $cart_mdata);
				$cart_mdata     = str_replace("{attribute_change}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_price}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_number}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_tax}", "", $cart_mdata);

				// ProductFinderDatepicker Extra Field
				$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $giftcard_id, $fieldArray, $giftcard = 1);

				$remove_product = '<form style="" class="rs_hiddenupdatecart" name="delete_cart' . $i . '" method="POST" >
				<input type="hidden" name="giftcard_id" value="' . $cart[$i]['giftcard_id'] . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="Itemid" value="' . $Itemid . '">
				<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img
					. '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

				if (Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY'))
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}

				// Replace attribute tags to empty on giftcard
				if (strpos($cart_mdata, "{product_attribute_loop_start}") !== false && strpos($cart_mdata, "{product_attribute_loop_end}") !== false)
				{
					$templateattibute_sdata  = explode('{product_attribute_loop_start}', $cart_mdata);
					$templateattibute_edata  = explode('{product_attribute_loop_end}', $templateattibute_sdata[1]);
					$templateattibute_middle = $templateattibute_edata[0];

					$cart_mdata = str_replace($templateattibute_middle, "", $cart_mdata);
				}

				$cartItem = 'giftcard_id';
			}
			else
			{
				$product_id     = $cart[$i]['product_id'];
				$product        = RedshopHelperProduct::getProductById($product_id);
				$retAttArr      = $this->_producthelper->makeAttributeCart($cart [$i] ['cart_attribute'], $product_id, 0, 0, $quantity, $cart_mdata);
				$cart_attribute = $retAttArr[0];

				$retAccArr      = $this->_producthelper->makeAccessoryCart($cart [$i] ['cart_accessory'], $product_id, $cart_mdata);
				$cart_accessory = $retAccArr[0];

				$ItemData = $this->_producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product_id);

				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = RedshopHelperRouter::getItemId($product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&Itemid=' . $Itemid);

				// Trigger to change product link.
				$dispatcher->trigger('onSetCartOrderItemProductLink', array(&$cart, &$link, $product, $i));

				$pname         = $product->product_name;
				$product_name  = "<div  class='product_name'><a href='" . $link . "'>" . $pname . "</a></div>";
				$product_image = "";
				$prd_image     = '';
				$type          = 'product';

				if (Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART') && isset($cart[$i]['hidden_attribute_cartimage']))
				{
					$image_path    = REDSHOP_FRONT_IMAGES_ABSPATH;
					$product_image = str_replace($image_path, '', $cart[$i]['hidden_attribute_cartimage']);
				}

				if ($product_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $product_image))
				{
					$val        = explode("/", $product_image);
					$prd_image  = $val[1];
					$type       = $val[0];
				}
				elseif ($product->product_full_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$prd_image = $product->product_full_image;
					$type      = 'product';
				}
				elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$prd_image = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					$type      = 'product';
				}

				$isAttributeImage = false;

				if (isset($cart[$i]['attributeImage']))
				{
					$isAttributeImage = JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']);
				}

				if ($isAttributeImage)
				{
					$prd_image = $cart[$i]['attributeImage'];
					$type      = 'mergeImages';
				}

				if ($prd_image !== '')
				{
					if (Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE') && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('WATERMARK_IMAGE')))
					{
						$product_cart_img = RedshopHelperMedia::watermark(
							$type, $prd_image, Redshop::getConfig()->get('CART_THUMB_WIDTH'), Redshop::getConfig()->get('CART_THUMB_HEIGHT'), Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE')
						);

						$product_image = "<div  class='product_image'><a href='" . $link . "'><img src='" . $product_cart_img . "'></a></div>";
					}
					else
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
								$prd_image,
								'',
								'thumb',
								$type,
								Redshop::getConfig()->get('CART_THUMB_WIDTH'),
								Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

						$product_image = "<div  class='product_image'><a href='" . $link . "'><img src='" . $thumbUrl . "'></a></div>";
					}
				}
				else
				{
					$product_image = "<div  class='product_image'></div>";
				}

				// Trigger to change product image.
				$dispatcher->trigger('OnSetCartOrderItemImage', array(&$cart, &$product_image, $product, $i));

				$chktag              = $this->_producthelper->getApplyVatOrNot($data);
				$product_total_price = "<div class='product_price'>";

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_total_price .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price_excl_vat'] * $quantity);
					}
					else
					{
						$product_total_price .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'] * $quantity);
					}
				}

				$product_total_price .= "</div>";

				$product_old_price = "";
				$product_price     = "<div class='product_price'>";

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_price .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price_excl_vat'], true);
					}
					else
					{
						$product_price .= RedshopHelperProductPrice::formattedPrice($cart[$i]['product_price'], true);
					}

					if (isset($cart[$i]['product_old_price']))
					{
						$product_old_price = $cart[$i]['product_old_price'];

						if (!$chktag)
						{
							$product_old_price = $cart[$i]['product_old_price_excl_vat'];
						}

						// Set Product Old Price without format
						$productOldPriceNoFormat = $product_old_price;

						$product_old_price = RedshopHelperProductPrice::formattedPrice($product_old_price, true);
					}
				}

				$product_price .= "</div>";

				$wrapper_name = "";

				if ((array_key_exists('wrapper_id', $cart[$i])) && $cart[$i]['wrapper_id'])
				{
					$wrapper = $this->_producthelper->getWrapper($product_id, $cart[$i]['wrapper_id']);

					if (count($wrapper) > 0)
					{
						$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name;

						if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
						{
							$wrapper_name .= "(" . RedshopHelperProductPrice::formattedPrice($cart[$i]['wrapper_price'], true) . ")";
						}
					}
				}

				if (strpos($cart_mdata, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "";
					$product_name_nolink = "<div  class='product_name'>$product->product_name</a></div>";
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $cart_mdata);

					if (strpos($cart_mdata, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
				}

				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

				// Replace Attribute data
				if (strpos($cart_mdata, "{product_attribute_loop_start}") !== false && strpos($cart_mdata, "{product_attribute_loop_end}") !== false)
				{
					$templateattibute_sdata  = explode('{product_attribute_loop_start}', $cart_mdata);
					$templateattibute_start  = $templateattibute_sdata[0];
					$templateattibute_edata  = explode('{product_attribute_loop_end}', $templateattibute_sdata[1]);
					$templateattibute_end    = $templateattibute_edata[1];
					$templateattibute_middle = $templateattibute_edata[0];
					$pro_detail              = '';
					$sum_total               = count($cart[$i]['cart_attribute']);
					$temp_tpi                = $cart[$i]['cart_attribute'];

					if ($sum_total > 0)
					{
						$propertyCalculatedPriceSum = $productOldPriceNoFormat;

						for ($tpi = 0; $tpi < $sum_total; $tpi++)
						{
							$product_attribute_name        = "";
							$product_attribute_value       = "";
							$product_attribute_value_price = "";
							$product_attribute_name        = $temp_tpi[$tpi]['attribute_name'];

							$productAttributeCalculatedPrice = '';

							if (count($temp_tpi[$tpi]['attribute_childs']) > 0)
							{
								$product_attribute_value = ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_name'];

								if (count($temp_tpi[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$product_attribute_value .= ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subattribute_color_title'] . ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_name'];
								}

								$product_attribute_value_price = $temp_tpi[$tpi]['attribute_childs'][0]['property_price'];
								$propertyOperand               = $temp_tpi[$tpi]['attribute_childs'][0]['property_oprand'];

								if (count($temp_tpi[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$product_attribute_value_price = $product_attribute_value_price + $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_price'];
									$propertyOperand               = $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_oprand'];
								}

								// Show actual productive price
								if ($product_attribute_value_price > 0)
								{
									$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
										$propertyCalculatedPriceSum, $propertyOperand, $product_attribute_value_price
									);

									$productAttributeCalculatedPrice = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
									$propertyCalculatedPriceSum      = $productAttributeCalculatedPriceBase;
								}

								$product_attribute_value_price = RedshopHelperProductPrice::formattedPrice((double) $product_attribute_value_price);
							}

							$productAttributeCalculatedPrice = RedshopHelperProductPrice::formattedPrice((double) $productAttributeCalculatedPrice);
							$productAttributeCalculatedPrice = JText::sprintf('COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE', $productAttributeCalculatedPrice);

							$data_add_pro = $templateattibute_middle;
							$data_add_pro = str_replace("{product_attribute_name}", $product_attribute_name, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value}", $product_attribute_value, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value_price}", $product_attribute_value_price, $data_add_pro);
							$data_add_pro = str_replace(
								"{product_attribute_calculated_price}",
								$productAttributeCalculatedPrice,
								$data_add_pro
							);
							$pro_detail .= $data_add_pro;
						}
					}

					$cart_mdata = str_replace($templateattibute_middle, $pro_detail, $cart_mdata);
				}

				if (count($cart [$i] ['cart_attribute']) > 0)
				{
					$cart_mdata = str_replace("{attribute_label}", JText::_("COM_REDSHOP_ATTRIBUTE"), $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{attribute_label}", "", $cart_mdata);
				}

				$cart_mdata           = str_replace("{product_number}", $product->product_number, $cart_mdata);
				$cart_mdata           = str_replace("{product_vat}", $cart[$i]['product_vat'] * $cart[$i]['quantity'], $cart_mdata);
				$user_fields          = $this->_producthelper->GetProdcutUserfield($i);
				$cart_mdata           = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$user_custom_fields   = $this->_producthelper->GetProdcutfield($i);
				$cart_mdata           = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
				$cart_mdata           = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);
				$discount_calc_output = (isset($cart[$i]['discount_calc_output']) && $cart[$i]['discount_calc_output']) ? $cart[$i]['discount_calc_output'] . "<br />" : "";

				$cart_mdata           = RedshopTagsReplacer::_(
											'attribute',
											$cart_mdata,
											array(
												'product_attribute' => $discount_calc_output . $cart_attribute,
											)
										);

				$cart_mdata           = str_replace("{product_accessory}", $cart_accessory, $cart_mdata);
				$cart_mdata           = str_replace("{product_attribute_price}", "", $cart_mdata);
				$cart_mdata           = str_replace("{product_attribute_number}", "", $cart_mdata);
				$cart_mdata           = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata, $product_old_price);
				$cart_mdata           = str_replace("{product_old_price}", $product_old_price, $cart_mdata);
				$cart_mdata           = str_replace("{product_wrapper}", $wrapper_name, $cart_mdata);
				$cart_mdata           = str_replace("{product_thumb_image}", $product_image, $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				// ProductFinderDatepicker Extra Field Start
				$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

				$product_price_excl_vat = $cart[$i]['product_price_excl_vat'];

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($product_price_excl_vat), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($product_price_excl_vat * $quantity), $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", "", $cart_mdata);
				}

				if ($product->product_type == 'subscription')
				{
					$subscription_detail   = $this->_producthelper->getProductSubscriptionDetail($product->product_id, $cart[$i]['subscription_id']);
					$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;
					$cart_mdata            = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
					$cart_mdata            = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
				}

				if ($replace_button)
				{
					$update_attribute = '';

					if ($mainview == 'cart')
					{
						$attchange        = JURI::root() . 'index.php?option=com_redshop&view=cart&layout=change_attribute&tmpl=component&pid=' . $product_id . '&cart_index=' . $i;
						$update_attribute = '<a class="modal" rel="{handler: \'iframe\', size: {x: 550, y: 400}}" href="' . $attchange . '">' . JText::_('COM_REDSHOP_CHANGE_ATTRIBUTE') . '</a>';
					}

					if ($cart_attribute != "")
					{
						$cart_mdata = str_replace("{attribute_change}", $update_attribute, $cart_mdata);
					}
					else
					{
						$cart_mdata = str_replace("{attribute_change}", "", $cart_mdata);
					}
				}
				else
				{
					$cart_mdata = str_replace("{attribute_change}", '', $cart_mdata);
				}

				// Product extra fields.
				$cart_mdata  = RedshopHelperProductTag::getExtraSectionTag(
					Redshop\Helper\ExtraFields::getSectionFieldNames(RedshopHelperExtrafields::SECTION_PRODUCT), $product_id, "1", $cart_mdata
				);

				$cartItem = 'product_id';
				$cart_mdata = RedshopHelperTax::replaceVatInformation($cart_mdata);
				$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
				$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);
			}

			if ($replace_button)
			{
				$update_cart_none = '<label>' . $quantity . '</label>';

				$update_img = '';

				if ($mainview == 'checkout')
				{
					$update_cart = $quantity;
				}
				else
				{
					$update_cart = '<form style="padding:0px;margin:0px;" name="update_cart' . $i . '" method="POST" >';
					$update_cart .= '<input class="inputbox input-mini" type="text" value="' . $quantity . '" name="quantity" id="quantitybox' . $i . '" size="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '" maxlength="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '" onchange="validateInputNumber(this.id);">';
					$update_cart .= '<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
								<input type="hidden" name="cart_index" value="' . $i . '">
								<input type="hidden" name="Itemid" value="' . $Itemid . '">
								<input type="hidden" name="task" value="">';

					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_UPDATE')))
					{
						$update_img = Redshop::getConfig()->get('ADDTOCART_UPDATE');
					}
					else
					{
						$update_img = "defaultupdate.png";
					}

					$update_cart .= '<img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">';

					$update_cart .= '</form>';
				}

				$update_cart_minus_plus = '<form name="update_cart' . $i . '" method="POST">';

				$update_cart_minus_plus .= '<input type="text" id="quantitybox' . $i . '" name="quantity"  size="1"  value="' . $quantity . '" /><input type="button" id="minus" value="-"
						onClick="quantity.value = (quantity.value) ; var qty1 = quantity.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity.value--;return false;">';

				$update_cart_minus_plus .= '<input type="button" value="+"
						onClick="quantity.value = (+quantity.value+1)"><input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
						<input type="hidden" name="cart_index" value="' . $i . '">
						<input type="hidden" name="Itemid" value="' . $Itemid . '">
						<input type="hidden" name="task" value=""><img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">
						</form>';

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
				{
					$delete_img = Redshop::getConfig()->get('ADDTOCART_DELETE');
				}
				else
				{
					$delete_img = "defaultcross.png";
				}

				if ($mainview == 'checkout')
				{
					$remove_product = '';
				}
				else
				{
					$remove_product = '<form name="delete_cart' . $i . '" method="POST" >
							<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
							<input type="hidden" name="cart_index" value="' . $i . '">
							<input type="hidden" name="task" value="">
							<input type="hidden" name="Itemid" value="' . $Itemid . '">
							<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img . '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';
				}

				if (Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY'))
				{
					if (strstr($cart_mdata, "{quantity_increase_decrease}") && $mainview == 'cart')
					{
						$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart_minus_plus, $cart_mdata);
						$cart_mdata = str_replace("{update_cart}", '', $cart_mdata);
					}
					else
					{
						$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart, $cart_mdata);
						$cart_mdata = str_replace("{update_cart}", $update_cart, $cart_mdata);
					}

					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart_minus_plus, $cart_mdata);
					$cart_mdata = str_replace("{update_cart}", $update_cart_none, $cart_mdata);
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
			}
			else
			{
				$cart_mdata = str_replace("{update_cart}", $quantity, $cart_mdata);
				$cart_mdata = str_replace("{remove_product}", '', $cart_mdata);
			}

			$cart_tr .= $cart_mdata;
		}

		return $cart_tr;
	}

	/**
	 * replace Order Items
	 *
	 * @param   string   $data      template
	 * @param   array    $rowitem   Order item list
	 * @param   boolean  $sendMail  is send mail
	 *
	 * @return  string
	 *
	 */
	public function repalceOrderItems($data, $rowitem = array(), $sendMail = false)
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$mainview   = $this->input->getCmd('view');
		$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

		$subtotal_excl_vat = 0;
		$cart              = '';
		$url               = JURI::root();
		$returnArr         = array();

		$wrapper_name = "";

		$OrdersDetail = RedshopEntityOrder::getInstance((int) $rowitem[0]->order_id)->getItem();

		for ($i = 0, $in = count($rowitem); $i < $in; $i++)
		{
			$cart_mdata = $data;

			// Process the product plugin for cart item
			$dispatcher->trigger('onOrderItemDisplay', array(&$cart_mdata, &$rowitem, $i));

			$product_id = $rowitem[$i]->product_id;
			$quantity   = $rowitem[$i]->product_quantity;

			$itemData = $this->_producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product_id);

			$Itemid = !empty($itemData) ? $itemData->id : RedshopHelperRouter::getItemId($product_id) ;

			$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&Itemid=' . $Itemid);

			if ($rowitem[$i]->is_giftcard)
			{
				$giftcardData      = $this->_producthelper->getGiftcardData($product_id);
				$product_name      = $giftcardData->giftcard_name;
				$userfield_section = 13;
				$product = new stdClass;
			}
			else
			{
				$product           = RedshopHelperProduct::getProductById($product_id);
				$product_name      = $rowitem[$i]->order_item_name;
				$userfield_section = 12;
				$giftcardData = new stdClass;
			}

			$dirname = JPath::clean(JPATH_COMPONENT_SITE . "/assets/images/orderMergeImages/" . $rowitem[$i]->attribute_image);
			$attrib_img = "";

			if (JFile::exists($dirname))
			{
				$attribute_image_path = RedShopHelperImages::getImagePath(
											$rowitem[$i]->attribute_image,
											'',
											'thumb',
											'orderMergeImages',
											Redshop::getConfig()->get('CART_THUMB_WIDTH'),
											Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
				$attrib_img = '<img src="' . $attribute_image_path . '">';
			}
			else
			{
				if (JFile::exists(JPATH_COMPONENT_SITE . "/assets/images/product_attributes/" . $rowitem[$i]->attribute_image) && Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART'))
				{
					$attribute_image_path = RedShopHelperImages::getImagePath(
												$rowitem[$i]->attribute_image,
												'',
												'thumb',
												'product_attributes',
												Redshop::getConfig()->get('CART_THUMB_WIDTH'),
												Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
												Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
											);
					$attrib_img = '<img src="' . $attribute_image_path . '">';
				}
				else
				{
					if ($rowitem[$i]->is_giftcard)
					{
						$product_full_image = $giftcardData->giftcard_image;
						$product_type = 'giftcard';
					}
					else
					{
						$product_full_image = $product->product_full_image;
						$product_type = 'product';
					}

					if ($product_full_image)
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $product_type . "/" . $product_full_image))
						{
							$attribute_image_path = RedShopHelperImages::getImagePath(
														$product_full_image,
														'',
														'thumb',
														$product_type,
														Redshop::getConfig()->get('CART_THUMB_WIDTH'),
														Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
														Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
													);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						}
						else
						{
							if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
							{
								$attribute_image_path = RedShopHelperImages::getImagePath(
															Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
															'',
															'thumb',
															'product',
															Redshop::getConfig()->get('CART_THUMB_WIDTH'),
															Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
															Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
														);
								$attrib_img = '<img src="' . $attribute_image_path . '">';
							}
						}
					}
					else
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
						{
							$attribute_image_path = RedShopHelperImages::getImagePath(
														Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
														'',
														'thumb',
														'product',
														Redshop::getConfig()->get('CART_THUMB_WIDTH'),
														Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
														Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
													);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						}
					}
				}
			}

			if (!$sendMail)
			{
				$product_name = '<a href="' . $link . '">' . $product_name . '</a>';
				$attrib_img   = '<a href="' . $link . '">' . $attrib_img . '</a>';
			}

			$product_name        = "<div class='product_name'>" . $product_name . "</div>";
			$product_total_price = "<div class='product_price'>";

			if (!$this->_producthelper->getApplyVatOrNot($data))
			{
				$product_total_price .= RedshopHelperProductPrice::formattedPrice($rowitem[$i]->product_item_price_excl_vat * $quantity);
			}
			else
			{
				$product_total_price .= RedshopHelperProductPrice::formattedPrice($rowitem[$i]->product_item_price * $quantity);
			}

			$product_total_price .= "</div>";

			$product_price = "<div class='product_price'>";

			if (!$this->_producthelper->getApplyVatOrNot($data))
			{
				$product_price .= RedshopHelperProductPrice::formattedPrice($rowitem[$i]->product_item_price_excl_vat);
			}
			else
			{
				$product_price .= RedshopHelperProductPrice::formattedPrice($rowitem[$i]->product_item_price);
			}

			$product_price .= "</div>";

			$product_old_price = RedshopHelperProductPrice::formattedPrice($rowitem[$i]->product_item_old_price);

			$product_quantity = '<div class="update_cart">' . $quantity . '</div>';

			if ($rowitem[$i]->wrapper_id)
			{
				$wrapper = $this->_producthelper->getWrapper($product_id, $rowitem[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapper_name = $wrapper [0]->wrapper_name;
				}

				$wrapper_price = RedshopHelperProductPrice::formattedPrice($rowitem[$i]->wrapper_price);
				$wrapper_name  = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper_name . "(" . $wrapper_price . ")";
			}

			$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);

			$catId = $this->_producthelper->getCategoryProduct($product_id);
			$res   = $this->_producthelper->getSection("category", $catId);

			if (count($res) > 0)
			{
				$cname = $res->name;
				$clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);
				$category_path = "<a href='" . $clink . "'>" . $cname . "</a>";
			}
			else
			{
				$category_path = '';
			}

			if (strpos($cart_mdata, '{stock_status}') !== false)
			{
				$isStockExists = RedshopHelperStockroom::isStockExists($rowitem[$i]->product_id);

				if (!$isStockExists)
				{
					$isPreorderStockExists = RedshopHelperStockroom::isPreorderStockExists($rowitem[$i]->product_id);
				}

				if (!$isStockExists)
				{
					$productPreorder = $product->preorder;

					if (($productPreorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreorder == "yes") || ($productPreorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						if (!$isPreorderStockExists)
						{
							$stockStatus = JText::_('COM_REDSHOP_OUT_OF_STOCK');
						}
						else
						{
							$stockStatus = JText::_('COM_REDSHOP_PRE_ORDER');
						}
					}
					else
					{
						$stockStatus = JText::_('COM_REDSHOP_OUT_OF_STOCK');
					}
				}
				else
				{
					$stockStatus = JText::_('COM_REDSHOP_AVAILABLE_STOCK');
				}

				$cart_mdata = str_replace("{stock_status}", $stockStatus, $cart_mdata);
			}

			$cart_mdata    = str_replace("{category_name}", $category_path, $cart_mdata);

			$cart_mdata = RedshopHelperTax::replaceVatInformation($cart_mdata);

			$product_note = "<div class='product_note'>" . $wrapper_name . "</div>";

			$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);

			// Make attribute order template output
			$attribute_data = $this->_producthelper->makeAttributeOrder($rowitem[$i]->order_item_id, 0, $product_id, 0, 0, $data);

			// Assign template output into {product_attribute} tag
			$cart_mdata = RedshopTagsReplacer::_(
						'attribute',
						$cart_mdata,
						array(
							'product_attribute' => $attribute_data->product_attribute,
						)
					);

			// Assign template output into {attribute_middle_template} tag
			$cart_mdata = str_replace($attribute_data->attribute_middle_template_core, $attribute_data->attribute_middle_template, $cart_mdata);

			if (strpos($cart_mdata, '{remove_product_attribute_title}') !== false)
			{
				$cart_mdata = str_replace("{remove_product_attribute_title}", "", $cart_mdata);
			}

			if (strpos($cart_mdata, '{remove_product_subattribute_title}') !== false)
			{
				$cart_mdata = str_replace("{remove_product_subattribute_title}", "", $cart_mdata);
			}

			if (strpos($cart_mdata, '{product_attribute_number}') !== false)
			{
				$cart_mdata = str_replace("{product_attribute_number}", "", $cart_mdata);
			}

			$cart_mdata = str_replace("{product_accessory}", $this->_producthelper->makeAccessoryOrder($rowitem[$i]->order_item_id), $cart_mdata);

			$productUserFields = $this->_producthelper->getuserfield($rowitem[$i]->order_item_id, $userfield_section);

			$cart_mdata = str_replace("{product_userfields}", $productUserFields, $cart_mdata);

			$user_custom_fields = $this->_producthelper->GetProdcutfield_order($rowitem[$i]->order_item_id);
			$cart_mdata         = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
			$cart_mdata         = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);

			if ($rowitem[$i]->is_giftcard)
			{
				$cart_mdata = str_replace(
					array('{product_sku}', '{product_number}', '{product_s_desc}', '{product_subscription}', '{product_subscription_lbl}'),
					'', $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{product_sku}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_number}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

				if ($product->product_type == 'subscription')
				{
					$user_subscribe_detail = $this->_producthelper->getUserProductSubscriptionDetail($rowitem[$i]->order_item_id);
					$subscription_detail   = $this->_producthelper->getProductSubscriptionDetail($product->product_id, $user_subscribe_detail->subscription_id);
					$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;

					$cart_mdata = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
				}
			}

			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);

			$product_vat = ($rowitem[$i]->product_item_price - $rowitem[$i]->product_item_price_excl_vat) * $rowitem [$i]->product_quantity;

			$cart_mdata = str_replace("{product_vat}", $product_vat, $cart_mdata);

			$cart_mdata = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

			// ProductFinderDatepicker Extra Field Start
			$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

			// Change order item image based on plugin
			$prepareCartAttributes[$i]               = get_object_vars($attribute_data);
			$prepareCartAttributes[$i]['product_id'] = $rowitem[$i]->product_id;

			$dispatcher->trigger(
				'OnSetCartOrderItemImage',
				array(
					&$prepareCartAttributes,
					&$attrib_img,
					$rowitem[$i],
					$i
				)
			);

			$cart_mdata = str_replace(
				"{product_thumb_image}",
				"<div class='product_image'>" . $attrib_img . "</div>",
				$cart_mdata
			);

			$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);

			$cart_mdata = str_replace("{product_old_price}", $product_old_price, $cart_mdata);

			$cart_mdata = str_replace("{product_quantity}", $quantity, $cart_mdata);

			$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);

			$cart_mdata = str_replace("{product_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($rowitem [$i]->product_item_price_excl_vat), $cart_mdata);

			$cart_mdata = str_replace("{product_total_price_excl_vat}", RedshopHelperProductPrice::formattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity), $cart_mdata);

			$subtotal_excl_vat += $rowitem [$i]->product_item_price_excl_vat * $quantity;

			$dispatcher = RedshopHelperUtility::getDispatcher();
			JPluginHelper::importPlugin('redshop_stockroom');
			$dispatcher->trigger('onReplaceStockStatus', array($rowitem[$i], &$cart_mdata));

			if ($mainview == "order_detail")
			{
				$Itemid     = RedshopHelperRouter::getCartItemId();
				$copytocart = "<a href='" . JRoute::_('index.php?option=com_redshop&view=order_detail&task=copyorderitemtocart&order_item_id=' . $rowitem[$i]->order_item_id . '&Itemid=' . $Itemid, false) . "'>";
				$copytocart .= "<img src='" . REDSHOP_ADMIN_IMAGES_ABSPATH . "add.jpg' title='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' alt='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' /></a>";
				$cart_mdata = str_replace("{copy_orderitem}", $copytocart, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{copy_orderitem}", "", $cart_mdata);
			}

			// Get Downloadable Products
			$downloadProducts     = $this->_order_functions->getDownloadProduct($rowitem[$i]->order_id);
			$totalDownloadProduct = count($downloadProducts);

			$dproducts = array();

			for ($t = 0; $t < $totalDownloadProduct; $t++)
			{
				$downloadProduct                                                        = $downloadProducts[$t];
				$dproducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
			}

			// Get Downloadable Products Logs
			$downloadProductslog     = $this->_order_functions->getDownloadProductLog($rowitem[$i]->order_id);
			$totalDownloadProductlog = count($downloadProductslog);

			$dproductslog = array();

			for ($t = 0; $t < $totalDownloadProductlog; $t++)
			{
				$downloadProductlogs                              = $downloadProductslog[$t];
				$dproductslog[$downloadProductlogs->product_id][] = $downloadProductlogs;
			}

			// Download Product Tag Replace
			if (isset($dproducts[$product_id]) && count($dproducts[$product_id]) > 0 && $OrdersDetail->order_status == "C" && $OrdersDetail->order_payment_status == "Paid")
			{
				$downloadarray = $dproducts[$product_id];
				$dpData        = "<table class='download_token'>";
				$limit         = $dpData;
				$enddate       = $dpData;
				$g             = 1;

				foreach ($downloadarray as $downloads)
				{
					$file_name    = substr(basename($downloads->file_name), 11);
					$product_name = $downloadProduct->product_name;
					$download_id  = $downloads->download_id;
					$download_max = $downloads->download_max;
					$end_date     = $downloads->end_date;
					$mailtoken    = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "'>" . $file_name . "</a>";
					$dpData .= "</tr>";
					$dpData .= "<td>(" . $g . ") " . $product_name . ": " . $mailtoken . "</td>";
					$dpData .= "</tr>";
					$limit .= "</tr>";
					$limit .= "<td>(" . $g . ") " . $download_max . "</td>";
					$limit .= "</tr>";
					$enddate .= "</tr>";
					$enddate .= "<td>(" . $g . ") " . date("d-m-Y H:i", $end_date) . "</td>";
					$enddate .= "</tr>";
					$g++;
				}

				$dpData .= "</table>";
				$limit .= "</table>";
				$enddate .= "</table>";
				$cart_mdata = str_replace("{download_token_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_TOKEN'), $cart_mdata);
				$cart_mdata = str_replace("{download_token}", $dpData, $cart_mdata);
				$cart_mdata = str_replace("{download_counter_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_LEFT'), $cart_mdata);
				$cart_mdata = str_replace("{download_counter}", $limit, $cart_mdata);
				$cart_mdata = str_replace("{download_date_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_ENDDATE'), $cart_mdata);
				$cart_mdata = str_replace("{download_date}", $enddate, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{download_token_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_token}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date}", "", $cart_mdata);
			}

			// Download Product log Tags Replace
			if (isset($dproductslog[$product_id]) && count($dproductslog[$product_id]) > 0 && $OrdersDetail->order_status == "C")
			{
				$downloadarraylog = $dproductslog[$product_id];
				$dpData           = "<table class='download_token'>";
				$g                = 1;

				foreach ($downloadarraylog as $downloads)
				{
					$file_name = substr(basename($downloads->file_name), 11);

					$download_id   = $downloads->download_id;
					$download_time = $downloads->download_time;
					$download_date = date("d-m-Y H:i:s", $download_time);
					$ip            = $downloads->ip;

					$mailtoken = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
						. $download_id . "'>"
						. $file_name . "</a>";

					$dpData .= "</tr>";
					$dpData .= "<td>(" . $g . ") " . $mailtoken . " "
						. JText::_('COM_REDSHOP_ON') . " " . $download_date . " "
						. JText::_('COM_REDSHOP_FROM') . " " . $ip . "</td>";
					$dpData .= "</tr>";

					$g++;
				}

				$dpData .= "</table>";
				$cart_mdata = str_replace("{download_date_list_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_LOG'), $cart_mdata);
				$cart_mdata = str_replace("{download_date_list}", $dpData, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{download_date_list_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_list}", "", $cart_mdata);
			}

			$cart .= $cart_mdata;
		}

		$returnArr[0] = $cart;
		$returnArr[1] = $subtotal_excl_vat;

		return $returnArr;
	}

	public function replaceLabel($data)
	{
		$search  = array();
		$replace = array();

		if (strpos($data, '{cart_lbl}') !== false)
		{
			$search[]  = "{cart_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_LBL');
		}

		if (strpos($data, '{copy_orderitem_lbl}') !== false)
		{
			$search[]  = "{copy_orderitem_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COPY_ORDERITEM_LBL');
		}

		if (strpos($data, '{totalpurchase_lbl}') !== false)
		{
			$search[]  = "{totalpurchase_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_TOTAL_PURCHASE_TBL');
		}

		if (strpos($data, '{subtotal_excl_vat_lbl}') !== false)
		{
			$search[]  = "{subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SUBTOTAL_EXCL_VAT_LBL');
		}

		if (strpos($data, '{product_name_lbl}') !== false)
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strpos($data, '{price_lbl}') !== false)
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strpos($data, '{quantity_lbl}') !== false)
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strpos($data, '{total_price_lbl}') !== false)
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strpos($data, '{total_price_exe_lbl}') !== false)
		{
			$search[]  = "{total_price_exe_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_EXEL_LBL');
		}

		if (strpos($data, '{order_id_lbl}') !== false)
		{
			$search[]  = "{order_id_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		}

		if (strpos($data, '{order_number_lbl}') !== false)
		{
			$search[]  = "{order_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		}

		if (strpos($data, '{order_date_lbl}') !== false)
		{
			$search[]  = "{order_date_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DATE_LBL');
		}

		if (strpos($data, '{requisition_number_lbl}') !== false)
		{
			$search[]  = "{requisition_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');
		}

		if (strpos($data, '{order_status_lbl}') !== false)
		{
			$search[]  = "{order_status_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strpos($data, '{order_status_order_only_lbl}') !== false)
		{
			$search[]  = "{order_status_order_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strpos($data, '{order_status_payment_only_lbl}') !== false)
		{
			$search[]  = "{order_status_payment_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL');
		}

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (strpos($data, '{shipping_lbl}') !== false)
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}

			if (strpos($data, '{tax_with_shipping_lbl}') !== false)
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}
		}
		else
		{
			if (strpos($data, '{shipping_lbl}') !== false)
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = "";
			}

			if (strpos($data, '{tax_with_shipping_lbl}') !== false)
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = "";
			}
		}

		if (strpos($data, '{order_information_lbl}') !== false)
		{
			$search[]  = "{order_information_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_INFORMATION_LBL');
		}

		if (strpos($data, '{order_detail_lbl}') !== false)
		{
			$search[]  = "{order_detail_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');
		}

		if (strpos($data, '{product_name_lbl}') !== false)
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strpos($data, '{note_lbl}') !== false)
		{
			$search[]  = "{note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_NOTE_LBL');
		}

		if (strpos($data, '{price_lbl}') !== false)
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strpos($data, '{quantity_lbl}') !== false)
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strpos($data, '{total_price_lbl}') !== false)
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strpos($data, '{order_subtotal_lbl}') !== false)
		{
			$search[]  = "{order_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_SUBTOTAL_LBL');
		}

		if (strpos($data, '{total_lbl}') !== false)
		{
			$search[]  = "{total_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_LBL');
		}

		if (strpos($data, '{discount_type_lbl}') !== false)
		{
			$search[]  = "{discount_type_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');
		}

		if (strpos($data, '{payment_lbl}') !== false)
		{
			$search[]  = "{payment_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		}

		if (strpos($data, '{customer_note_lbl}') !== false)
		{
			$search [] = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL');
		}

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (strpos($data, '{shipping_method_lbl}') !== false)
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');
			}
		}
		else
		{
			if (strpos($data, '{shipping_method_lbl}') !== false)
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = '';
			}
		}

		if (strpos($data, '{product_number_lbl}') !== false)
		{
			$search[]  = "{product_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NUMBER');
		}

		if (strpos($data, '{shopname}') !== false)
		{
			$search []  = "{shopname}";
			$replace [] = Redshop::getConfig()->get('SHOP_NAME');
		}

		if (strpos($data, '{quotation_id_lbl}') !== false)
		{
			$search []  = "{quotation_id_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_ID');
		}

		if (strpos($data, '{quotation_number_lbl}') !== false)
		{
			$search []  = "{quotation_number_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NUMBER');
		}

		if (strpos($data, '{quotation_date_lbl}') !== false)
		{
			$search []  = "{quotation_date_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DATE');
		}

		if (strpos($data, '{quotation_status_lbl}') !== false)
		{
			$search []  = "{quotation_status_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_STATUS');
		}

		if (strpos($data, '{quotation_note_lbl}') !== false)
		{
			$search []  = "{quotation_note_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NOTE');
		}

		if (strpos($data, '{quotation_information_lbl}') !== false)
		{
			$search []  = "{quotation_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');
		}

		if (strpos($data, '{account_information_lbl}') !== false)
		{
			$search []  = "{account_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');
		}

		if (strpos($data, '{quotation_detail_lbl}') !== false)
		{
			$search []  = "{quotation_detail_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DETAILS');
		}

		if (strpos($data, '{quotation_subtotal_lbl}') !== false)
		{
			$search []  = "{quotation_subtotal_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
		}

		if (strpos($data, '{quotation_discount_lbl}') !== false)
		{
			$search []  = "{quotation_discount_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');
		}

		if (strpos($data, '{thirdparty_email_lbl}') !== false)
		{
			$search [] = "{thirdparty_email_lbl}";
			$replace[] = JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL');
		}

		$data = str_replace($search, $replace, $data);

		return $data;
	}

   /**
   * APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the
   * final price may vary, depending on if the discount affects "the price+VAT"
   * or just "the price". This CONSTANT will define if the discounts needs to
   * be applied BEFORE or AFTER the VAT is applied to the product price.
   */
	public function calculation($cart, $shipping = 0, $user_id = 0)
	{
		$Idx               = $cart['idx'];
		$total             = 0;
		$vat               = 0;
		$subtotal          = 0;
		$subtotal_excl_vat = 0;
		$shipping          = 0;
		$discount          = 0;
		$user_info_id      = 0;
		$total_discount    = 0;
		$discountVAT       = 0;
		$redArray          = array();

		for ($i = 0; $i < $Idx; $i++)
		{
			$quantity          = $cart[$i]['quantity'];
			$subtotal          += $quantity * $cart[$i]['product_price'];
			$subtotal_excl_vat += $quantity * $cart[$i]['product_price_excl_vat'];
			$vat               += $quantity * $cart[$i]['product_vat'];
		}

		$tmparr             = array();
		$tmparr['subtotal'] = $subtotal;

		$tmparr['tax'] = $vat;
		$shippingVat   = 0;

		// If SHOW_SHIPPING_IN_CART set to no, make shipping Zero
		if (Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (!$user_id)
			{
				$user          = JFactory::getUser();
				$user_id       = $user->id;
				$shippingArray = $this->_order_functions->getShippingAddress($user_id);

				if (!empty($shippingArray[0]))
				{
					$user_info_id = $shippingArray[0]->users_info_id;
				}
			}

			$noOFGIFTCARD = 0;

			for ($i = 0; $i < $Idx; $i++)
			{
				if (isset($cart [$i] ['giftcard_id']) === true)
				{
					if (!is_null($cart [$i] ['giftcard_id']) && $cart [$i] ['giftcard_id'] != 0)
					{
						$noOFGIFTCARD++;
					}
				}
			}

			if ($noOFGIFTCARD == $Idx)
			{
				$cart['free_shipping'] = 1;
			}
			elseif (!isset($cart['free_shipping']) || $cart['free_shipping'] != 1)
			{
				$cart['free_shipping'] = 0;
			}

			if (isset($cart ['free_shipping']) && $cart ['free_shipping'] > 0)
			{
				$shipping = 0;
			}
			else
			{
				if (!isset($cart['voucher_discount']))
				{
					$cart['coupon_discount'] = 0;
				}

				$total_discount      = $cart['cart_discount'] + (isset($cart['voucher_discount']) ? $cart['voucher_discount'] : 0) + $cart['coupon_discount'];
				$d['order_subtotal'] = (Redshop::getConfig()->get('SHIPPING_AFTER') == 'total') ? $subtotal - $total_discount : $subtotal;
				$d['users_info_id']  = $user_info_id;
				$shippingArr         = RedshopHelperCartShipping::getDefault($d);
				$shipping            = $shippingArr['shipping_rate'];
				$shippingVat         = $shippingArr['shipping_vat'];
			}
		}

		$view = $this->input->getCmd('view');

		if (key_exists('shipping', $cart) && $view != 'cart')
		{
			$shipping = $cart['shipping'];

			if (!isset($cart['shipping_vat']))
			{
				$cart['shipping_vat'] = 0;
			}

			$shippingVat = $cart['shipping_vat'];
		}

		$chktag = RedshopHelperCart::taxExemptAddToCart();

		if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && !empty($chktag))
		{
			if (isset($cart['discount_tax']) && !empty($cart['discount_tax']))
			{
				$discountVAT = $cart['discount_tax'];
				$subtotal    = $subtotal - $cart['discount_tax'];
			}
			else
			{
				$vatData = $this->_producthelper->getVatRates();

				if (isset($vatData->tax_rate) && !empty($vatData->tax_rate))
				{
					$discountVAT = 0;

					if ((int) $subtotal_excl_vat > 0)
					{
						$avgVAT      = (($subtotal_excl_vat + $vat) / $subtotal_excl_vat) - 1;
						$discountVAT = ($avgVAT * $total_discount) / (1 + $avgVAT);
					}
				}
			}

			$vat = $vat - $discountVAT;
		}

		$total      = $subtotal + $shipping;
		$redArray[] = $total;
		$redArray[] = $subtotal;
		$redArray[] = $subtotal_excl_vat;
		$redArray[] = $shipping;

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$redArray[] = $cart['discount'];

		$redArray[] = $vat;
		$redArray[] = $shippingVat;

		return $redArray;
	}

	public function GetCartModuleCalc($redArray)
	{
		$cartParamArr       = array();
		$cartParamArr       = $this->GetCartParameters();
		$cart_output        = 0;
		$show_with_shipping = 1;
		$show_with_discount = 1;
		$show_with_vat      = 1;

		if (array_key_exists('cart_output', $cartParamArr))
		{
			$cart_output = $cartParamArr['cart_output'];
		}

		if (array_key_exists('show_with_shipping', $cartParamArr))
		{
			$show_with_shipping = $cartParamArr['show_with_shipping'];
		}

		if (array_key_exists('show_with_discount', $cartParamArr))
		{
			$show_with_discount = $cartParamArr['show_with_discount'];
		}

		if (array_key_exists('show_with_vat', $cartParamArr))
		{
			$show_with_vat = $cartParamArr['show_with_vat'];
		}

		if (!$show_with_vat)
		{
			$total = $redArray['product_subtotal_excl_vat'];
		}
		else
		{
			$total = $redArray['product_subtotal'];
		}

		$shipping       = $redArray['shipping'];
		$discount_total = $redArray['coupon_discount'] + $redArray['voucher_discount'] + $redArray['cart_discount'];

		if ($show_with_shipping == 1 && $show_with_discount == 1)
		{
			$mod_cart_total = $total + $shipping - $discount_total;
		}
		elseif ($show_with_shipping == 0 && $show_with_discount == 1)
		{
			$mod_cart_total = $total - $discount_total;
		}
		elseif ($show_with_shipping == 1 && $show_with_discount == 0)
		{
			$mod_cart_total = $total + $shipping;
		}
		else
		{
			$mod_cart_total = $total;
		}

		$this->_show_with_vat = $show_with_vat;
		$layout               = $this->input->getCmd('layout');
		$view                 = $this->input->getCmd('view');

		if (array_key_exists('payment_amount', $redArray) && $view == 'checkout' && $layout != 'default')
		{
			if ($redArray['payment_oprand'] == '+')
			{
				$mod_cart_total += $redArray['payment_amount'];
			}
			else
			{
				$mod_cart_total -= $redArray['payment_amount'];
			}
		}

		return $mod_cart_total;
	}

	public function replaceTemplate($cart, $cart_data, $checkout = 1)
	{
		if (strpos($cart_data, "{product_loop_start}") !== false && strpos($cart_data, "{product_loop_end}") !== false)
		{
			$template_sdata  = explode('{product_loop_start}', $cart_data);
			$template_start  = $template_sdata[0];
			$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
			$template_end    = $template_edata[1];
			$template_middle = $template_edata[0];
			$template_middle = $this->replaceCartItem($template_middle, $cart, 1, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));
			$cart_data       = $template_start . $template_middle . $template_end;
		}

		$cart_data = $this->replaceLabel($cart_data);

		$total                     = $cart ['total'];
		$subtotal_excl_vat         = $cart ['subtotal_excl_vat'];
		$product_subtotal          = $cart ['product_subtotal'];
		$product_subtotal_excl_vat = $cart ['product_subtotal_excl_vat'];
		$subtotal                  = $cart ['subtotal'];
		$discount_ex_vat           = $cart['discount_ex_vat'];
		$dis_tax                   = 0;
		$discount_total            = $cart['voucher_discount'] + $cart['coupon_discount'];
		$discount_amount           = $cart ["cart_discount"];
		$tax                       = $cart ['tax'];
		$sub_total_vat             = $cart ['sub_total_vat'];
		$shipping                  = $cart ['shipping'];
		$shippingVat               = $cart ['shipping_tax'];

		if (isset($cart ['discount_type']) === false)
		{
			$cart ['discount_type'] = 0;
		}

		$check_type                = $cart ['discount_type'];
		$chktotal                  = 0;
		$tmp_discount              = $discount_total;
		$discount_total            = RedshopHelperProductPrice::formattedPrice($discount_total + $discount_amount, true);

		if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			if (strpos($cart_data, '{product_subtotal_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_subtotal_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_excl_vat_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_with_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_with_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_excl_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_price_excl_lbl}') !== false)
			{
				$cart_data = str_replace("{product_price_excl_lbl}", JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL'), $cart_data);
			}

			$cart_data = str_replace("{total}", "<span id='spnTotal'>" . RedshopHelperProductPrice::formattedPrice($total, true) . "</span>", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "<span id='spnTotal'>" . RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat) . "</span>", $cart_data);

			$chktag = $this->_producthelper->getApplyVatOrNot($cart_data);

			if (!empty($chktag))
			{
				$cart_data = str_replace("{subtotal}", RedshopHelperProductPrice::formattedPrice($subtotal), $cart_data);
				$cart_data = str_replace("{product_subtotal}", RedshopHelperProductPrice::formattedPrice($product_subtotal), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{subtotal}", RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat), $cart_data);
				$cart_data = str_replace("{product_subtotal}", RedshopHelperProductPrice::formattedPrice($product_subtotal_excl_vat), $cart_data);
			}

			if ((strpos($cart_data, "{discount_denotation}") !== false || strpos($cart_data, "{shipping_denotation}") !== false) && ($discount_total != 0 || $shipping != 0))
			{
				$cart_data = str_replace("{denotation_label}", JText::_('COM_REDSHOP_DENOTATION_TXT'), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{denotation_label}", "", $cart_data);
			}

			if (strpos($cart_data, "{discount_excl_vat}") !== false)
			{
				$cart_data = str_replace("{discount_denotation}", "*", $cart_data);
			}
			else
			{
				$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			}

			$cart_data = str_replace("{subtotal_excl_vat}", RedshopHelperProductPrice::formattedPrice($subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", RedshopHelperProductPrice::formattedPrice($product_subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{sub_total_vat}", RedshopHelperProductPrice::formattedPrice($sub_total_vat), $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", RedshopHelperProductPrice::formattedPrice($discount_ex_vat), $cart_data);

			$rep = true;

			if (!$checkout)
			{
				if (!Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') || !Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}
			else
			{
				if (!Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}

			if (!empty($rep))
			{
				if (strpos($cart_data, "{shipping_excl_vat}") !== false)
				{
					$cart_data = str_replace("{shipping_denotation}", "*", $cart_data);
				}
				else
				{
					$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
				}

				$cart_data = str_replace("{order_shipping}", RedshopHelperProductPrice::formattedPrice($shipping, true), $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'>" . RedshopHelperProductPrice::formattedPrice($shipping - $cart['shipping_tax'], true) . "</span>", $cart_data);
				$cart_data = str_replace("{shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{shipping}", "<span id='spnShippingrate'>" . RedshopHelperProductPrice::formattedPrice($shipping, true) . "</span>", $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{vat_shipping}", RedshopHelperProductPrice::formattedPrice($shippingVat), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{order_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", '', $cart_data);
				$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{shipping}", '', $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{vat_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			}
		}
		else
		{
			$cart_data = str_replace("{total}", "<span id='spnTotal'></span>", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'></span>", $cart_data);
			$cart_data = str_replace("{order_shipping}", "", $cart_data);
			$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{shipping}", "<span id='spnShippingrate'></span>", $cart_data);
			$cart_data = str_replace("{subtotal}", "", $cart_data);
			$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{vat_shipping}", '', $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal}", "", $cart_data);
			$cart_data = str_replace("{sub_total_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			$cart_data = str_replace("{denotation_label}", "", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "", $cart_data);
		}

		if (!Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$total_for_discount = $subtotal_excl_vat;
		}
		else
		{
			$total_for_discount = $subtotal;
		}

		$cart_data = RedshopHelperCartTag::replaceDiscount($cart_data, $discount_amount + $tmp_discount, $total_for_discount, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));

		if ($checkout)
		{
			$cart_data = RedshopHelperPayment::replaceConditionTag($cart_data, $cart['payment_amount'], 0, $cart['payment_oprand']);
		}
		else
		{
			$paymentOprand = (isset($cart['payment_oprand'])) ? $cart['payment_oprand'] : '-';
			$cart_data     = RedshopHelperPayment::replaceConditionTag($cart_data, 0, 1, $paymentOprand);
		}

		$cart_data = RedshopHelperCartTag::replaceTax(
			$cart_data, $tax + $shippingVat, $discount_amount + $tmp_discount, 0, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
		);

		return $cart_data;
	}

	/**
	 * Method for replace template order
	 *
	 * @param   object   $row              Order data.
	 * @param   string   $ReceiptTemplate  Template content.
	 * @param   boolean  $sendMail         In send mail
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see  Redshop\Order\Template::replaceTemplate
	 */
	public function replaceOrderTemplate($row, $ReceiptTemplate, $sendMail = false)
	{
		return Redshop\Order\Template::replaceTemplate($row, $ReceiptTemplate, $sendMail);
	}

	/**
	 * Method for render cart.
	 *
	 * @param   array  $cart  Cart data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::generateCartOutput() instead.
	 */
	public function makeCart_output($cart)
	{
		return RedshopHelperCart::generateCartOutput($cart);
	}

	public function GetCartParameters()
	{
		$sel = 'SELECT params  from #__modules where module = "mod_redshop_cart" and published =1';
		$this->_db->setQuery($sel);
		$params = $this->_db->loadResult();

		$cartparamArr = array();
		$params       = substr($params, 1);
		$params       = substr_replace($params, " ", -1);
		$params       = str_replace('"', ' ', $params);
		$allparams    = explode(",", $params);

		for ($i = 0, $in = count($allparams); $i < $in; $i++)
		{
			$cart_param = explode(':', $allparams[$i]);

			if (!empty($cart_param))
			{
				if (strpos($cart_param[0], 'cart_output') !== false
					|| strpos($cart_param[0], 'show_with_shipping') !== false
					|| strpos($cart_param[0], 'show_with_discount') !== false
					|| strpos($cart_param[0], 'show_with_vat') !== false
					|| strpos($cart_param[0], 'show_shipping_line') !== false)
				{
					$cartparamArr[trim($cart_param[0])] = trim($cart_param[1]);
				}
			}
		}

		return $cartparamArr;
	}

	public function modifyCart($cartArr, $user_id)
	{
		$cartArr['user_id'] = $user_id;
		$idx = 0;

		if (isset($cartArr['idx']))
		{
			$idx = (int) $cartArr['idx'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			if (!isset($cartArr[$i]['giftcard_id'])
				|| (isset($cartArr[$i]['giftcard_id']) && $cartArr[$i]['giftcard_id'] <= 0))
			{
				$product_id   = $cartArr[$i]['product_id'];
				$quantity     = $cartArr[$i]['quantity'];
				$product      = RedshopHelperProduct::getProductById($product_id);
				$hasAttribute = isset($cartArr[$i]['cart_attribute']) ? true : false;

				// Attribute price
				$price = 0;

				if (!isset($cartArr['quotation']))
				{
					$cartArr['quotation'] = 0;
				}

				if ((Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || $cartArr['quotation'] == 1) && !$hasAttribute)
				{
					$price = $cartArr[$i]['product_price_excl_vat'];
				}

				if ($product->use_discount_calc)
				{
					$price = $cartArr[$i]['discount_calc_price'];
				}

				// Only set price without vat for accessories as product
				$accessoryAsProdutWithoutVat = false;

				if (isset($cartArr['AccessoryAsProduct']))
				{
					// Accessory price fix during update
					$accessoryAsProdut = RedshopHelperAccessory::getAccessoryAsProduct($cartArr['AccessoryAsProduct']);

					if (isset($accessoryAsProdut->accessory)
						&& isset($accessoryAsProdut->accessory[$cartArr[$i]['product_id']])
						&& isset($cartArr[$i]['accessoryAsProductEligible']))
					{
						$accessoryAsProdutWithoutVat = '{without_vat}';

						$accessoryPrice                        = (float) $accessoryAsProdut->accessory[$cartArr[$i]['product_id']]->newaccessory_price;
						$price                                 = $this->_producthelper->productPriceRound($accessoryPrice);
						$cartArr[$i]['product_price_excl_vat'] = $this->_producthelper->productPriceRound($accessoryPrice);
					}
				}

				$retAttArr = $this->_producthelper->makeAttributeCart(
					isset($cartArr[$i]['cart_attribute']) ? $cartArr[$i]['cart_attribute'] : array(),
					(int) $product->product_id,
					$user_id,
					$price,
					$quantity,
					$accessoryAsProdutWithoutVat
				);

				$accessoryAsProductZero = (count($retAttArr[8]) == 0 && $price == 0 && $accessoryAsProdutWithoutVat);

				// Product + attribute (price)
				$getproprice = ($accessoryAsProductZero) ? 0 : $retAttArr[1];

				// Product + attribute (VAT)
				$getprotax                  = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
				$product_old_price_excl_vat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

				// Accessory calculation
				$retAccArr = $this->_producthelper->makeAccessoryCart(
					isset($cartArr[$i]['cart_accessory']) ? $cartArr[$i]['cart_accessory'] : array(),
					$product->product_id,
					$user_id
				);

				// Accessory + attribute (price)
				$getaccprice = $retAccArr[1];

				// Accessory + attribute (VAT)
				$getacctax = $retAccArr[2];
				$product_old_price_excl_vat += $retAccArr[1];

				// ADD WRAPPER PRICE
				$wrapper_vat   = 0;
				$wrapper_price = 0;

				if (array_key_exists('wrapper_id', $cartArr[$i]))
				{
					if ($cartArr[$i]['wrapper_id'])
					{
						$wrapperArr                 = $this->getWrapperPriceArr(array('product_id' => $cartArr[$i]['product_id'], 'wrapper_id' => $cartArr[$i]['wrapper_id']));
						$wrapper_vat                = $wrapperArr['wrapper_vat'];
						$wrapper_price              = $wrapperArr['wrapper_price'];
						$product_old_price_excl_vat += $wrapper_price;
					}
				}

				$product_price          = $getaccprice + $getproprice + $getprotax + $getacctax + $wrapper_price + $wrapper_vat;
				$product_vat            = ($getprotax + $getacctax + $wrapper_vat);
				$product_price_excl_vat = ($getproprice + $getaccprice + $wrapper_price);

				if ($product->product_type == 'subscription')
				{
					if (isset($cartArr[$i]['subscription_id']) && $cartArr[$i]['subscription_id'] != "")
					{
						$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product_id, $cartArr[$i]['subscription_id']);
						$subscription_vat    = 0;
						$subscription_price  = $subscription_detail->subscription_price;

						if ($subscription_price)
						{
							$subscription_vat = RedshopHelperProduct::getProductTax($this->data->product_id, $subscription_price);
						}

						$product_vat += $subscription_vat;
						$product_price = $product_price + $subscription_price + $subscription_vat;
						$product_price_excl_vat += $subscription_price;
						$product_old_price_excl_vat += $subscription_price + $subscription_vat;
					}
					else
					{
						return;
					}
				}

				// Set product price
				if ($product_price < 0)
				{
					$product_price = 0;
				}

				$cartArr[$i]['product_old_price_excl_vat'] = $product_old_price_excl_vat;
				$cartArr[$i]['product_price_excl_vat']     = $product_price_excl_vat;
				$cartArr[$i]['product_vat']                = $product_vat;
				$cartArr[$i]['product_price']              = $product_price;

				JPluginHelper::importPlugin('redshop_product');
				$dispatcher = RedshopHelperUtility::getDispatcher();
				$dispatcher->trigger('onBeforeLoginCartSession', array(&$cartArr, $i));
			}
		}

		unset($cartArr[$idx]);

		return $cartArr;
	}

	public function replaceShippingBoxTemplate($box_template_desc = "", $shipping_box_post_id = 0)
	{
		// Get shipping boxes HTML
		$shippingBoxes = $this->_shippinghelper->getShippingBox();

		$box_template_desc = str_replace("{shipping_box_heading}", JText::_('COM_REDSHOP_SHIPPING_BOXES'), $box_template_desc);

		if (count($shippingBoxes) == 1 || (count($shippingBoxes) > 0 && $shipping_box_post_id == 0))
		{
			$shipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
		}

		$shipping_box_list = JText::_('COM_REDSHOP_NO_SHIPPING_BOX');

		if (count($shippingBoxes) > 0)
		{
			$shipping_box_list = "";

			for ($i = 0, $in = count($shippingBoxes); $i < $in; $i++)
			{
				$shipping_box_id = $shippingBoxes[$i]->shipping_box_id;

				// Previous priority
				if ($i > 0)
				{
					$shipping_box_priority_pre = $shippingBoxes[$i - 1]->shipping_box_priority;
				}

				// Current priority
				$shipping_box_priority = $shippingBoxes[$i]->shipping_box_priority;
				$checked               = ($shipping_box_post_id == $shipping_box_id) ? "checked='checked'" : "";

				if ($i == 0 || ($shipping_box_priority == $shipping_box_priority_pre))
				{
					$shipping_box_list .= "<div class='radio'><label class=\"radio\" for='shipping_box_id" . $shipping_box_id . "'><input " . $checked . " type='radio' id='shipping_box_id" . $shipping_box_id . "' name='shipping_box_id'  onclick='javascript:onestepCheckoutProcess(this.name,\'\');' value='" . $shipping_box_id . "' />";
					$shipping_box_list .= "" . $shippingBoxes[$i]->shipping_box_name . "</label></div>";
				}
			}
		}

		$box_template_desc = str_replace("{shipping_box_list}", $shipping_box_list, $box_template_desc);
		$style             = 'none';

		$shippingmethod = $this->_order_functions->getShippingMethodInfo();

		for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
		{
			if ($shippingmethod[$s]->element == 'bring' || $shippingmethod[$s]->element == 'ups' || $shippingmethod[$s]->element == 'uspsv4')
			{
				$style = 'block';
			}
		}

		if (count($shippingBoxes) <= 1 || count($shippingmethod) <= 1)
		{
			$style = 'none';
		}

		$box_template_desc = "<div style='display:$style;'>" . $box_template_desc . "</div>";

		return $box_template_desc;
	}

	public function replaceShippingTemplate($template_desc = "", $shipping_rate_id = 0, $shipping_box_post_id = 0, $user_id = 0, $users_info_id = 0, $ordertotal = 0, $order_subtotal = 0, $post = array())
	{
		$shippingmethod       = $this->_order_functions->getShippingMethodInfo();
		$adminpath            = JPATH_ADMINISTRATOR . '/components/com_redshop';
		$rateExist            = 0;
		$d                    = array();
		$d['user_id']         = $user_id;
		$d['users_info_id']   = $users_info_id;
		$d['shipping_box_id'] = $shipping_box_post_id;
		$d['ordertotal']      = $ordertotal;
		$d['order_subtotal']  = $order_subtotal;
		$d['post']            = $post;
		$template_desc        = str_replace("{shipping_heading}", JText::_('COM_REDSHOP_SHIPPING_METHOD'), $template_desc);
		$extrafield_total     = "";

		if (strpos($template_desc, "{shipping_method_loop_start}") !== false && strpos($template_desc, "{shipping_method_loop_end}") !== false)
		{
			$template1       = explode("{shipping_method_loop_start}", $template_desc);
			$template1       = explode("{shipping_method_loop_end}", $template1[1]);
			$template_middle = $template1[0];

			$template_rate_middle = "";

			if (strpos($template_middle, "{shipping_rate_loop_start}") !== false && strpos($template_middle, "{shipping_rate_loop_end}") !== false)
			{
				$template1            = explode("{shipping_rate_loop_start}", $template_middle);
				$template1            = explode("{shipping_rate_loop_end}", $template1[1]);
				$template_rate_middle = $template1[0];
			}

			$oneShipping = false;

			if (count($shippingmethod) == 1)
			{
				$oneShipping = true;
			}

			$rate_data = "";

			if ($template_middle != "" && count($shippingmethod) > 0)
			{
				JPluginHelper::importPlugin('redshop_shipping');
				$dispatcher   = RedshopHelperUtility::getDispatcher();
				$shippingrate = $dispatcher->trigger('onListRates', array(&$d));

				for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
				{
					if (isset($shippingrate[$s]) === false)
					{
						continue;
					}

					$rate = $shippingrate[$s];

					if (count($rate) > 0)
					{
						if (empty($shipping_rate_id))
						{
							$shipping_rate_id = $rate[0]->value;
						}

						$rs        = $shippingmethod[$s];
						$classname = $rs->element;
						$rate_data .= $template_middle;
						$rate_data = str_replace("{shipping_method_title}", JText::_($rs->name), $rate_data);

						if ($template_rate_middle != "")
						{
							$data         = "";
							$mainlocation = "";

							for ($i = 0, $in = count($rate); $i < $in; $i++)
							{
								$glsLocation = '';
								$checked      = '';
								$data        .= $template_rate_middle;

								$displayrate = (trim($rate[$i]->rate) > 0) ? " (" . RedshopHelperProductPrice::formattedPrice((double) trim($rate[$i]->rate)) . " )" : "";

								if ((isset($rate[$i]->checked) && $rate[$i]->checked) || $rateExist == 0)
								{
									$checked = "checked";
								}

								if ($checked == "checked")
								{
									$shipping_rate_id = $rate[$i]->value;
								}

								$shipping_rate_name = '<label class="radio inline" for="shipping_rate_id_' . $shippingmethod[$s]->extension_id . '_' . $i . '"><input type="radio" id="shipping_rate_id_'
									. $shippingmethod[$s]->extension_id . '_' . $i . '" name="shipping_rate_id" value="'
									. $rate[$i]->value . '" '
									. $checked
									. ' onclick="javascript:onestepCheckoutProcess(this.name,\'' . $classname . '\');"><span>'
									. '' . html_entity_decode($rate[$i]->text) . '</span></label>';

								$shipping_rate_short_desc = '';

								if (isset($rate[$i]->shortdesc) === true)
								{
									$shipping_rate_short_desc = html_entity_decode($rate[$i]->shortdesc);
								}

								$shipping_rate_desc = '';

								if (isset($rate[$i]->longdesc) === true)
								{
									$shipping_rate_desc = html_entity_decode($rate[$i]->longdesc);
								}

								$rateExist++;
								$data = str_replace("{shipping_rate_name}", $shipping_rate_name, $data);
								$data = str_replace("{shipping_rate_short_desc}", $shipping_rate_short_desc, $data);
								$data = str_replace("{shipping_rate_desc}", $shipping_rate_desc, $data);
								$data = str_replace("{shipping_rate}", $displayrate, $data);

								if (strpos($data, "{shipping_location}") !== false)
								{
									$shippinglocation = $this->_order_functions->getshippinglocationinfo($rate[$i]->text);

									for ($k = 0, $kn = count($shippinglocation); $k < $kn; $k++)
									{
										if ($shippinglocation[$k] != '')
										{
											$mainlocation = $shippinglocation[$k]->shipping_location_info;
										}
									}

									$data = str_replace("{shipping_location}", $mainlocation, $data);
								}

								$dispatcher->trigger('onReplaceShippingTemplate', array($d, &$data, $classname, $checked));

								$data = str_replace("{gls_shipping_location}", "", $data);
							}

							$rate_data = str_replace("{shipping_rate_loop_start}", "", $rate_data);
							$rate_data = str_replace("{shipping_rate_loop_end}", "", $rate_data);
							$rate_data = str_replace($template_rate_middle, $data, $rate_data);
						}
					}

					if (strpos($rate_data, "{shipping_extrafields}") !== false)
					{
						$extraField         = extraField::getInstance();
						$paymentparams_new  = new JRegistry($shippingmethod[$s]->params);
						$extrafield_payment = $paymentparams_new->get('extrafield_shipping');

						$extrafield_hidden  = "";

						if (count($extrafield_payment) > 0)
						{
							$countExtrafield = count($extrafield_payment);

							for ($ui = 0; $ui < $countExtrafield; $ui++)
							{
								$productUserFields = $extraField->list_all_user_fields($extrafield_payment[$ui], 19, '', 0, 0, 0);
								$extrafield_total .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";
								$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
							}

							$rate_data = str_replace("{shipping_extrafields}", "<div id='extrafield_shipping'>" . $extrafield_total . "</div>", $rate_data);
						}
						else
						{
							$rate_data = str_replace("{shipping_extrafields}", "", $rate_data);
						}
					}
				}
			}

			$template_desc = str_replace("{shipping_method_loop_start}", "", $template_desc);
			$template_desc = str_replace("{shipping_method_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $rate_data, $template_desc);
		}

		if ($rateExist == 0)
		{
			$errorMSG = '';

			if (count($shippingmethod) > 0)
			{
				$errorMSG = RedshopHelperShipping::getShippingRateError($d);
			}

			$template_desc = "<div></div>";
		}
		elseif ($rateExist == 1 && empty($extrafield_total) && $classname != "default_shipping_gls")
		{
			$template_desc = "<div style='display:none;'>" . $template_desc . "</div>";
		}

		JPluginHelper::importPlugin('redshop_checkout');
		JDispatcher::getInstance()->trigger('onRenderShippingMethod', array(&$template_desc));

		$returnarr = array("template_desc" => $template_desc, "shipping_rate_id" => $shipping_rate_id);

		return $returnarr;
	}

	/**
	 * Display credit card form based on payment method
	 *
	 * @param   integer  $payment_method_id  Payment Method ID for which form needs to be prepare
	 *
	 * @return  string     Credit Card form display data in HTML
	 */
	public function replaceCreditCardInformation($payment_method_id = 0)
	{
		if (empty($payment_method_id))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
				'error'
			);

			return '';
		}

		$paymentmethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentmethod = $paymentmethod[0];

		$cardinfo = "";

		if (file_exists(JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '/' . $paymentmethod->element . '.php'))
		{
			$paymentparams = new Registry($paymentmethod->params);
			$acceptedCredictCard = $paymentparams->get("accepted_credict_card", array());

			if ($paymentparams->get('is_creditcard', 0)
				&& !empty($acceptedCredictCard))
			{
				$cardinfo = RedshopLayoutHelper::render(
						'order.payment.creditcard',
						array(
							'pluginParams' => $paymentparams,
						)
					);
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_PAYMENT_CREDIT_CARDS_NOT_FOUND'), 'error');
			}
		}

		return $cardinfo;
	}

	/**
	 * Replace Payment Methods
	 *
	 * @param   string   $templateDesc     Template Content
	 * @param   integer  $paymentMethodId  Payment Method Id
	 * @param   integer  $isCompany        Is Company?
	 * @param   integer  $eanNumber        Ean Number
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function replacePaymentTemplate($templateDesc = "", $paymentMethodId = 0, $isCompany = 0, $eanNumber = 0)
	{
		$userId = JFactory::getUser()->id;

		$paymentMethods = RedshopHelperPayment::info();

		// Get common payment methods of product in this cart
		$commonPaymentMethods = RedshopHelperPayment::getPaymentMethodInCheckOut($paymentMethods);

		if (!empty($commonPaymentMethods))
		{
			$templateDesc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD'), $templateDesc);

			if (strpos($templateDesc, "{split_payment}") !== false)
			{
				$templateDesc = str_replace("{split_payment}", "", $templateDesc);
			}

			if (strpos($templateDesc, "{payment_loop_start}") !== false && strpos($templateDesc, "{payment_loop_end}") !== false)
			{
				$template1      = explode("{payment_loop_start}", $templateDesc);
				$template1      = explode("{payment_loop_end}", $template1[1]);
				$templateMiddle = $template1[0];
				$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);
				$paymentDisplay = "";
				$hasCreditCard  = false;

				// Filter payment gateways array for shopperGroups
				$paymentMethods = array_filter(
					$paymentMethods,
					function ($paymentMethod) use ($shopperGroupId)
					{
						$paymentFilePath = JPATH_SITE
							. '/plugins/redshop_payment/'
							. $paymentMethod->name . '/' . $paymentMethod->name . '.php';

						if (!JFile::exists($paymentFilePath))
						{
							return false;
						}

						$shopperGroups  = $paymentMethod->params->get('shopper_group_id', array());

						if (!is_array($shopperGroups))
						{
							$shopperGroups = array($shopperGroups);
						}

						$shopperGroups = ArrayHelper::toInteger($shopperGroups);

						if (in_array((int) $shopperGroupId, $shopperGroups) || (!isset($shopperGroups[0]) || 0 == $shopperGroups[0]))
						{
							return true;
						}

						return false;
					}
				);

				$totalPaymentMethod = count($paymentMethods);

				if ($totalPaymentMethod > 0)
				{
					foreach ($paymentMethods as $index => $oneMethod)
					{
						if (in_array($oneMethod->name, $commonPaymentMethods))
						{
							$cardInformation        = "";
							$displayPayment = "";
							include_once JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

							$privatePerson = $oneMethod->params->get('private_person', '');
							$business      = $oneMethod->params->get('business', '');
							$isCreditCard  = (boolean) $oneMethod->params->get('is_creditcard', 0);
							$checked       = $paymentMethodId === $oneMethod->name || $totalPaymentMethod <= 1;

							$paymentRadioOutput = RedshopLayoutHelper::render(
								'checkout.payment_radio',
								array(
										'oneMethod'          => $oneMethod,
										'paymentMethodId'    => $paymentMethodId,
										'index'              => $index,
										'totalPaymentMethod' => $totalPaymentMethod,
										'checked'            => $checked
									),
								'',
								array(
									'component' => 'com_redshop'
								)
							);

							$isSubscription = false;

							// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
							$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($oneMethod->name);

							if ($oneMethod->name == 'rs_payment_eantransfer' || $isBankTransferPaymentType)
							{
								if ($isCompany == 0 && $privatePerson == 1)
								{
									$displayPayment = $paymentRadioOutput;
								}
								else
								{
									if ($isCompany == 1 && $business == 1 &&
										($oneMethod->name != 'rs_payment_eantransfer'
											|| ($oneMethod->name == 'rs_payment_eantransfer' && $eanNumber != 0)))
									{
										$displayPayment = $paymentRadioOutput;
									}
								}
							}
							elseif ($isSubscription)
							{
								$displayPayment = '<label class="radio" for="' . $oneMethod->name . $index . '">'
									. '<input id="' . $oneMethod->name . $index . '" type="radio" name="payment_method_id" value="'
									. $oneMethod->name . '" '
									. ($checked ? 'checked="checked"' :  '')
									. ' onclick="javascript:onestepCheckoutProcess(this.name);" />'
									. '' . JText::_($oneMethod->name) . '</label><br>';

								$displayPayment .= '<table><tr><td>'
									. JText::_('COM_REDSHOP_SUBSCRIPTION_PLAN')
									. '</td><td>' . $this->getSubscriptionPlans()
									. '<td></tr><table>';
							}
							else
							{
								$displayPayment = $paymentRadioOutput;
							}

							if ($isCreditCard)
							{
								$cardInformation = '<div id="divcardinfo_' . $oneMethod->name . '">';

								$cart = JFactory::getSession()->get('cart');

								if ($checked && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')  && $cart['total'] > 0)
								{
									$cardInformation .= $this->replaceCreditCardInformation($oneMethod->name);
								}

								$cardInformation .= '</div>';

								$hasCreditCard = true;
							}

							$paymentDisplay .= $templateMiddle;
							$paymentDisplay = str_replace("{payment_method_name}", $displayPayment, $paymentDisplay);
							$paymentDisplay = str_replace("{creditcard_information}", $cardInformation, $paymentDisplay);

							if (strpos($paymentDisplay, "{payment_extrafields}") !== false)
							{
								$paymentExtraFieldsHtml = '';

								if ($checked)
								{
									$layoutFile = new JLayoutFile('order.payment.extrafields');

									// Append plugin JLayout path to improve view based on plugin if needed.
									$layoutFile->addIncludePath(JPATH_SITE . '/plugins/' . $oneMethod->type . '/' . $oneMethod->name . '/layouts');
									$paymentExtraFieldsHtml =  $layoutFile->render(array('plugin' => $oneMethod));
								}

								$paymentDisplay = str_replace(
									'{payment_extrafields}',
									'<div class="extrafield_payment">' . $paymentExtraFieldsHtml . '</div>',
									$paymentDisplay
								);
							}
						}
					}
				}

				$templateDesc = str_replace("{payment_loop_start}", "", $templateDesc);
				$templateDesc = str_replace("{payment_loop_end}", "", $templateDesc);
				$templateDesc = str_replace($templateMiddle, $paymentDisplay, $templateDesc);

				if (count($paymentMethods) == 1 && !$hasCreditCard)
				{
					$templateDesc = "<div style='display:none;'>" . $templateDesc . "</div>";
				}
			}
		}
		else
		{
			//clear
			$templateDesc = str_replace("{creditcard_information}", "", $templateDesc);
			$templateDesc = str_replace("{payment_loop_start}", "", $templateDesc);
			$templateDesc = str_replace("{payment_loop_end}", "", $templateDesc);
			//new template
			$templateDesc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD_CONFLICT'), $templateDesc);
			$templateDesc = str_replace(
				"{payment_method_name}",
				RedshopHelperPayment::displayPaymentMethodInCheckOut($paymentMethods),
				$templateDesc
			);
		}

		return $templateDesc;
	}

	public function replaceTermsConditions($template_desc = "", $Itemid = 1)
	{
		if (strpos($template_desc, "{terms_and_conditions") !== false)
		{
			$user    = JFactory::getUser();
			$session = JFactory::getSession();
			$auth    = $session->get('auth');
			$list    = array();

			if ($user->id)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.user_id='" . $user->id . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}
			elseif (isset($auth['users_info_id']) && $auth['users_info_id'] > 0)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.users_info_id='" . $auth['users_info_id'] . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}

			$terms_left_final = "";

			if (strpos($template_desc, "{terms_and_conditions:") !== false && strpos($template_desc, "}") !== false)
			{
				$terms_left_one   = explode("{terms_and_conditions:", $template_desc);
				$terms_left_two   = explode("}", $terms_left_one[1]);
				$terms_left_three = explode(":", $terms_left_two[0]);
				$terms_left_final = $terms_left_three[0];
			}

			$finaltag       = ($terms_left_final != "") ? "{terms_and_conditions:$terms_left_final}" : "{terms_and_conditions}";
			$termscondition = '';

			if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 0 || (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && ((count($list) > 0 && $list->accept_terms_conditions == 0) || count($list) == 0)))
			{
				$finalwidth  = "500";
				$finalheight = "450";

				if ($terms_left_final != "")
				{
					$dimension = explode(" ", $terms_left_final);

					if (count($dimension) > 0)
					{
						if (strpos($dimension[0], "width") !== false)
						{
							$width      = explode("width=", $dimension[0]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
						else
						{
							$height      = explode("height=", $dimension[0]);
							$finalheight = (isset($height[1])) ? $height[0] : "450";
						}

						if (strpos($dimension[1], "height") !== false)
						{
							$height      = explode("height=", $dimension[1]);
							$finalheight = (isset($height[1])) ? $height[1] : "450";
						}
						else
						{
							$width      = explode("width=", $dimension[1]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
					}
				}

				$url            = JURI::base();
				$article_link   = $url . "index.php?option=com_content&amp;view=article&amp;id=" . Redshop::getConfig()->get('TERMS_ARTICLE_ID') . "&Itemid=" . $Itemid . "&tmpl=component";
				$termscondition = '<label class="checkbox"><input type="checkbox" id="termscondition" name="termscondition" value="1" /> ';
				$termscondition .= JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL');
				$termscondition .= ' <a class="modal" href="' . $article_link . '" rel="{handler: \'iframe\', size: {x: ' . $finalwidth . ', y: ' . $finalheight . '}}">' . JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_FOR_LBL') . '</a></label>';
			}

			$template_desc = str_replace($finaltag, $termscondition, $template_desc);
		}

		return $template_desc;
	}

	public function replaceNewsletterSubscription($template_desc = "", $onchange = 0)
	{
		$db = JFactory::getDbo();

		if (strpos($template_desc, "{newsletter_signup_chk}") !== false)
		{
			$Itemid               = $this->input->get('Itemid');
			$newslettersignup     = "";
			$newslettersignup_lbl = "";
			$link                 = "";

			if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != 0)
			{
				$user  = JFactory::getUser();
				$query = "SELECT subscription_id FROM " . $this->_table_prefix . "newsletter_subscription"
					. " WHERE user_id=" . (int) $user->id . " AND email=" . $db->quote($user->email);
				$this->_db->setQuery($query);
				$subscribe = $this->_db->loadResult();

				if ($subscribe == 0)
				{
					if ($onchange)
					{
						$link = " onchange='window.location.href=\"" . JURI::root() . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid=" . $Itemid . "\"";

					}

					$newslettersignup     = "<input type='checkbox' name='newsletter_signup' value='1' '$link'>";
					$newslettersignup_lbl = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
				}
			}

			$template_desc = str_replace("{newsletter_signup_chk}", $newslettersignup, $template_desc);
			$template_desc = str_replace("{newsletter_signup_lbl}", $newslettersignup_lbl, $template_desc);
			$template_desc = str_replace("{newsletter_unsubscribe}", "", $template_desc);
		}

		return $template_desc;
	}

	public function getCartProductPrice($product_id, $cart, $voucher_left)
	{
		$productArr             = array();
		$affected_product_idArr = array();
		$idx                    = $cart['idx'];
		$product_price          = 0;
		$product_price_excl_vat = 0;
		$quantity               = 0;
		$flag                   = false;
		$product_idArr          = explode(',', $product_id);
		$product_idArr          = Joomla\Utilities\ArrayHelper::toInteger($product_idArr);

		for ($v = 0; $v < $idx; $v++)
		{
			if (in_array($cart[$v]['product_id'], $product_idArr) || $this->_globalvoucher)
			{
				// Set Quantity based on discount type - i.e Multiple or Single.
				$p_quantity = (Redshop::getConfig()->get('DISCOUNT_TYPE') == 4) ? $cart[$v]['quantity'] : 1;

				$product_price            += ($cart[$v]['product_price'] * $p_quantity);
				$product_price_excl_vat   += $cart[$v]['product_price_excl_vat'] * $p_quantity;
				$affected_product_idArr[] = $cart[$v]['product_id'];

				$quantity += $p_quantity;
			}
		}

		$productArr['product_ids']            = implode(',', $affected_product_idArr);
		$productArr['product_price']          = $product_price;
		$productArr['product_price_excl_vat'] = $product_price_excl_vat;
		$productArr['product_quantity']       = $p_quantity;

		return $productArr;
	}

	/**
	 * Method for apply coupon to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool
	 *
	 * @deprecated   2.0.7
	 *
	 * @see  RedshopHelperCartDiscount::applyCoupon()
	 *
	 * @throws  Exception
	 */
	public function coupon($cartData = array())
	{
		return RedshopHelperCartDiscount::applyCoupon($cartData);
	}

	/**
	 * Method for apply voucher to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool
	 *
	 * @deprecated   2.0.7
	 *
	 * @throws  Exception
	 *
	 * @see  RedshopHelperCartDiscount::applyVoucher()
	 */
	public function voucher($cartData = array())
	{
		return RedshopHelperCartDiscount::applyVoucher($cartData);
	}

	/**
	 * Re-calculate the Voucher/Coupon value when the product is already discount
	 *
	 * @param   float  $value  Voucher/Coupon value
	 * @param   array  $cart   Cart array
	 *
	 * @return  float          Voucher/Coupon value
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  RedshopHelperDiscount::calculateAlreadyDiscount()
	 */
	public function calcAlreadyDiscount($value, $cart)
	{
		return RedshopHelperDiscount::calculateAlreadyDiscount($value, $cart);
	}

	public function rs_multi_array_key_exists($needle, $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if ($needle === $key)
			{
				return true;
			}

			if (is_array($value))
			{
				if ($this->rs_multi_array_key_exists($needle, $value))
				{
					return true;
				}
			}
		}

		return false;
	}

	public function rs_recursiveArraySearch($haystack, $needle, $index = null)
	{
		$aIt = new RecursiveArrayIterator($haystack);
		$it  = new RecursiveIteratorIterator($aIt);

		while ($it->valid())
		{
			if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle))
			{
				return true;
			}

			$it->next();
		}

		return false;
	}

	/**
	 * Method for calculate discount.
	 *
	 * @param   string  $type   Type of discount
	 * @param   array   $types  List of type
	 *
	 * @return  float
	 *
	 * @deprecated  2.1.0
	 *
	 * @see RedshopHelperDiscount::calculate()
	 */
	public function calculateDiscount($type, $types)
	{
		return RedshopHelperDiscount::calculate($type, $types);
	}

	public function getVoucherData($voucher_code, $product_id = 0)
	{
		$db = JFactory::getDbo();

		$user         = JFactory::getUser();
		$voucher      = array();
		$current_time = JFactory::getDate()->toSql();
		$globalVouchers = $this->globalvoucher($voucher_code);

		if ($this->_globalvoucher != 1)
		{
			if ($user->id)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where('v.id = pv.voucher_id');

				$query = $db->getQuery(true)
					->select(
						array('vt.transaction_voucher_id', 'vt.amount AS total', 'vt.product_id', 'v.*', '(' . $subQuery . ') AS nproduct')
					)
					->from($db->qn('#__redshop_voucher', 'v'))
					->leftJoin($db->qn('#__redshop_product_voucher_transaction', 'vt') . ' ON vt.voucher_id = v.id')
					->where('vt.voucher_code = ' . $db->quote($voucher_code))
					->where('vt.amount > 0')
					->where('v.type = ' . $db->quote('Total'))
					->where('v.published = 1')
					->where(
						'('
						. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($current_time) . ')'
						. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($current_time) . ')'
						. ')'
					)
					->where('vt.user_id = ' . (int) $user->id)
					->order('vt.transaction_voucher_id DESC');

				$voucher = $db->setQuery($query)->loadObject();

				if (count($voucher) > 0)
				{
					$this->_r_voucher = 1;
				}
			}

			if (count($voucher) <= 0)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where($db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'));

				$query = $db->getQuery(true)
					->select(
						array(
							'(' . $subQuery . ') AS nproduct', 'v.amount AS total', 'v.type',
							'v.free_ship', 'v.id', 'v.code', 'v.voucher_left')
					)
					->from($db->qn('#__redshop_voucher', 'v'))
					->where($db->qn('v.published') . ' = 1')
					->where($db->qn('v.code') . ' = ' . $db->quote($voucher_code))
					->where('('
						. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($current_time) . ')'
						. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
						. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($current_time) . ')'
						. ')')
					->where($db->qn('v.voucher_left') . ' > 0');

				return $db->setQuery($query)->loadObject();
			}
		}

		return $globalVouchers;
	}

	public function globalvoucher($voucherCode)
	{
		$db = JFactory::getDbo();

		$currentTime = JFactory::getDate()->toSql();

		$query = $db->getQuery(true)
			->select($db->qn('pv.product_id'))
			->select('v.*')
			->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
			->leftJoin($db->qn('#__redshop_voucher', 'v') . ' ON ' . $db->qn('v.id') . ' = ' . $db->qn('pv.voucher_id'))
			->where($db->qn('v.published') . ' = 1')
			->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
			->where('('
				. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
				. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
				. ')')
			->where($db->qn('v.voucher_left') . ' > 0');

		$voucher = $this->_db->setQuery($query)->loadObject();

		if ($voucher)
		{
			return $voucher;
		}

		$this->_globalvoucher = 1;

		$query->clear()
			->select('v.*')
			->select($db->qn('v.amount', 'total'))
			->from($db->qn('#__redshop_voucher', 'v'))
			->where($db->qn('v.published') . ' = 1')
			->where($db->qn('v.code') . ' = ' . $db->quote($voucherCode))
			->where('('
				. '(' . $db->qn('v.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.start_date') . ' <= ' . $db->quote($currentTime) . ')'
				. ' AND (' . $db->qn('v.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('v.end_date') . ' >= ' . $db->quote($currentTime) . ')'
				. ')')
			->where($db->qn('v.voucher_left') . ' > 0');

		return $this->_db->setQuery($query)->loadObject();
	}

	/**
	 * @param   string   $couponCode  Coupon code
	 * @param   integer  $subtotal    Subtotal
	 *
	 * @return   array|mixed
	 */
	public function getCouponData($couponCode, $subtotal = 0)
	{
		$db = JFactory::getDbo();

		$today  = JFactory::getDate()->toSql();
		$user   = JFactory::getUser();
		$coupon = array();

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_coupons', 'c'))
			->where($db->qn('c.published') . ' = 1')
			->where(
			'('
				. '(' . $db->qn('c.start_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('c.start_date') . ' <= ' . $db->quote($today) . ')'
				. ' AND (' . $db->qn('c.end_date') . ' = ' . $db->quote($db->getNullDate())
				. ' OR ' . $db->qn('c.end_date') . ' >= ' . $db->quote($today) . ')'
				. ')'
			);

		if ($user->id)
		{
			$userQuery = clone($query);
			$userQuery->select(
					array(
						$db->qn('ct.coupon_value', 'coupon_value'),
						$db->qn('ct.userid'),
						$db->qn('ct.transaction_coupon_id')
					)
				)
				->leftjoin(
					$db->qn('#__redshop_coupons_transaction', 'ct')
					. ' ON ' . $db->qn('ct.coupon_id') . ' = ' . $db->qn('c.id')
				)
				->where($db->qn('ct.coupon_value') . ' > 0')
				->where($db->qn('ct.coupon_code') . ' = ' . $db->quote($couponCode))
				->where($db->qn('ct.userid') . ' = ' . (int) $user->id)
				->order($db->qn('ct.transaction_coupon_id') . ' DESC');

			$db->setQuery($userQuery, 0, 1);
			$coupon = $db->loadObject();

			if (count($coupon) > 0)
			{
				$this->_c_remain = 1;
			}
		}

		if (count($coupon) <= 0)
		{
			$query->where($db->qn('c.code') . ' = ' . $db->quote($couponCode))

				->where($db->qn('c.amount_left') . ' > 0')
				->where(
					'('
						. $db->quote($subtotal) . ' >= ' . $db->qn('c.subtotal')
						. ' OR ' . $db->qn('c.subtotal') . ' = 0'
					. ')'
				);

			$db->setQuery($query, 0, 1);
			$coupon = $db->loadObject();
		}

		return $coupon;
	}

	/**
	 * Method for modify discount
	 *
	 * @param   array  $cart  Cart data.
	 *
	 * @return  mixed
	 *
	 * @throws  Exception
	 */
	public function modifyDiscount($cart)
	{
		$calArr                            = $this->calculation($cart);
		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];

		$couponIndex  = !empty($cart['coupon']) && is_array($cart['coupon']) ? count($cart['coupon']) : 0;
		$voucherIndex = !empty($cart['voucher']) && is_array($cart['voucher']) ? count($cart['voucher']) : 0;

		$discountAmount = 0;

		if (Redshop::getConfig()->getBool('DISCOUNT_ENABLE'))
		{
			$discountAmount = $this->_producthelper->getDiscountAmount($cart);

			if ($discountAmount > 0)
			{
				$cart = RedshopHelperCartSession::getCart();
			}
		}

		if (!isset($cart['quotation_id']) || (isset($cart['quotation_id']) && !$cart['quotation_id']))
		{
			$cart['cart_discount'] = $discountAmount;
		}

		// Calculate voucher discount
		$voucherDiscount = 0;

		if (array_key_exists('voucher', $cart))
		{
			for ($v = 0; $v < $voucherIndex; $v++)
			{
				$voucherCode = $cart['voucher'][$v]['voucher_code'];

				unset($cart['voucher'][$v]);

				$cart = RedshopHelperCartDiscount::applyVoucher($cart, $voucherCode);
			}

			$voucherDiscount = RedshopHelperDiscount::calculate('voucher', $cart['voucher']);
		}

		$cart['voucher_discount'] = $voucherDiscount;

		// Calculate coupon discount
		$couponDiscount = 0;

		if (array_key_exists('coupon', $cart))
		{
			for ($c = 0; $c < $couponIndex; $c++)
			{
				$couponCode = $cart['coupon'][$c]['coupon_code'];

				unset($cart['coupon'][$c]);

				$cart = RedshopHelperCartDiscount::applyCoupon($cart, $couponCode);
			}

			$couponDiscount = RedshopHelperDiscount::calculate('coupon', $cart['coupon']);
		}

		$cart['coupon_discount'] = $couponDiscount;

		$codeDiscount  = $voucherDiscount + $couponDiscount;
		$totalDiscount = $cart['cart_discount'] + $codeDiscount;

		$calArr      = $this->calculation($cart);
		$tax         = $calArr[5];
		$discountVAT = 0;
		$chktag      = RedshopHelperCart::taxExemptAddToCart();

		if (Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !empty($chktag)
			&& !Redshop::getConfig()->getBool('APPLY_VAT_ON_DISCOUNT'))
		{
			$vatData = RedshopHelperUser::getVatUserInformation();

			if (!empty($vatData->tax_rate))
			{
				$productPriceExclVAT = (float) $cart['product_subtotal_excl_vat'];
				$productVAT          = (float) $cart['product_subtotal'] - $cart['product_subtotal_excl_vat'];

				if ($productPriceExclVAT > 0)
				{
					$avgVAT      = (($productPriceExclVAT + $productVAT) / $productPriceExclVAT) - 1;
					$discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
				}
			}
		}

		$cart['total'] = $calArr[0] - $totalDiscount;
		$cart['total'] = $cart['total'] < 0 ? 0 : $cart['total'];

		$cart['subtotal'] = $calArr[1] + $calArr[3] - $totalDiscount;
		$cart['subtotal'] = $cart['subtotal'] < 0 ? 0 : $cart['subtotal'];

		$cart['subtotal_excl_vat'] = $calArr[2] + ($calArr[3] - $calArr[6]) - ($totalDiscount - $discountVAT);
		$cart['subtotal_excl_vat'] = $cart['total'] <= 0 ? 0 : $cart['subtotal_excl_vat'];

		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$cart['shipping']                  = $calArr[3];
		$cart['tax']                       = $tax;
		$cart['sub_total_vat']             = $tax + $calArr[6];
		$cart['discount_vat']              = $discountVAT;
		$cart['shipping_tax']              = $calArr[6];
		$cart['discount_ex_vat']           = $totalDiscount - $discountVAT;
		$cart['mod_cart_total']            = $this->GetCartModuleCalc($cart);

		$this->_session->set('cart', $cart);

		return $cart;
	}

	public function getWrapperPriceArr($cartArr = array())
	{
		$wrapper     = $this->_producthelper->getWrapper($cartArr['product_id'], $cartArr['wrapper_id']);
		$wrapper_vat = 0;
		$wrapperArr  = array();

		if (count($wrapper) > 0)
		{
			if ($wrapper[0]->wrapper_price > 0)
			{
				$wrapper_vat = RedshopHelperProduct::getProductTax($cartArr['product_id'], $wrapper[0]->wrapper_price);
			}

			$wrapper_price = $wrapper[0]->wrapper_price;
		}

		$wrapperArr['wrapper_vat']   = $wrapper_vat;
		$wrapperArr['wrapper_price'] = $wrapper_price;

		return $wrapperArr;
	}

	public function checkQuantityInStock($data = array(), $newquantity = 1, $minQuantity = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onCheckQuantityInStock', array(&$data, &$newquantity, &$minQuantity));

		if (in_array(true, $result, true))
		{
			return $newquantity;
		}

		$productData      = RedshopHelperProduct::getProductById($data['product_id']);
		$product_preorder = $productData->preorder;

		if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $newquantity)
		{
			$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MINIMUM_QUANTITY');
			$msg = sprintf($msg, $productData->min_order_product_quantity);
			JError::raiseWarning('', $msg);
			$newquantity = $productData->min_order_product_quantity;
		}

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$productStock = 0;

			if (($product_preorder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "no") || ($product_preorder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				$productStock = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
			}

			if (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes") || ($product_preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				$productStock  = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
				$productStock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($data['product_id']);
			}

			$ownProductReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($data['product_id']);
			$attArr = $data['cart_attribute'];

			if (count($attArr) <= 0)
			{
				if ($productStock >= 0)
				{
					if ($newquantity > $ownProductReserveStock && $productStock < ($newquantity - $ownProductReserveStock))
					{
						$newquantity = $productStock + $ownProductReserveStock;
					}
				}
				else
				{
					$newquantity = $productStock + $ownProductReserveStock;
				}

				if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
				{
					$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MAXIMUM_QUANTITY');
					$msg = sprintf($msg, $productData->max_order_product_quantity);
					JError::raiseWarning('', $msg);
					$newquantity = $productData->max_order_product_quantity;
				}

				if (array_key_exists('quantity', $data))
				{
					$productReservedQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
				}
				else
				{
					$productReservedQuantity = $newquantity;
				}

				RedshopHelperStockroom::addReservedStock($data['product_id'], $productReservedQuantity, 'product');
			}
			else
			{
				for ($i = 0, $in = count($attArr); $i < $in; $i++)
				{
					$propArr = $attArr[$i]['attribute_childs'];

					for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
					{
						// Get subproperties from add to cart tray.
						$subpropArr = $propArr[$k]['property_childs'];
						$totalSubProperty = count($subpropArr);
						$ownReservePropertyStock = RedshopHelperStockroom::getCurrentUserReservedStock($propArr[$k]['property_id'], 'property');
						$property_stock = 0;

						if (($product_preorder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "no") || ($product_preorder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
						{
							$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
						}

						if (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes") || ($product_preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
						{
							$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
							$property_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($propArr[$k]['property_id'], "property");
						}

						// Get Property stock only when SubProperty is not in cart
						if ($totalSubProperty <= 0)
						{
							if ($property_stock >= 0)
							{
								if ($newquantity > $ownReservePropertyStock && $property_stock < ($newquantity - $ownReservePropertyStock))
								{
									$newquantity = $property_stock + $ownReservePropertyStock;
								}
							}
							else
							{
								$newquantity = $property_stock + $ownReservePropertyStock;
							}

							if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
							{
								$newquantity = $productData->max_order_product_quantity;
							}

							if (array_key_exists('quantity', $data))
							{
								$propertyReservedQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
								$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
							}
							else
							{
								$propertyReservedQuantity = $newquantity;
								$newProductQuantity = $ownProductReserveStock + $newquantity;
							}

							RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $propertyReservedQuantity, "property");
							RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
						}
						else
						{
							// Get SubProperty Stock here.
							for ($l = 0; $l < $totalSubProperty; $l++)
							{
								$subproperty_stock = 0;

								if (($product_preorder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "no") || ($product_preorder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
								{
									$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
								}

								if (($product_preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "yes") || ($product_preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
								{
									$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
									$subproperty_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
								}

								$ownSubPropReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($subpropArr[$l]['subproperty_id'], "subproperty");

								if ($subproperty_stock >= 0)
								{
									if ($newquantity > $ownSubPropReserveStock && $subproperty_stock < ($newquantity - $ownSubPropReserveStock))
									{
										$newquantity = $subproperty_stock + $ownSubPropReserveStock;
									}
								}
								else
								{
									$newquantity = $subproperty_stock + $ownSubPropReserveStock;
								}

								if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
								{
									$newquantity = $productData->max_order_product_quantity;
								}

								if (array_key_exists('quantity', $data))
								{
									$subPropertyReservedQuantity = $ownSubPropReserveStock + $newquantity - $data['quantity'];
									$newPropertyQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
									$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
								}
								else
								{
									$subPropertyReservedQuantity = $newquantity;
									$newPropertyQuantity = $ownReservePropertyStock + $newquantity;
									$newProductQuantity = $ownProductReserveStock + $newquantity;
								}

								RedshopHelperStockroom::addReservedStock($subpropArr[$l]['subproperty_id'], $subPropertyReservedQuantity, 'subproperty');
								RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $newPropertyQuantity, 'property');
								RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
							}
						}
					}
				}
			}
		}

		return $newquantity;
	}

	/**
	 * Method for calculate final price of cart.
	 *
	 * @param   bool  $callmodify  Is modify cart?
	 *
	 * @return  array
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::cartFinalCalculation() instead.
	 *
	 * @throws  Exception
	 */
	public function cartFinalCalculation($callmodify = true)
	{
		return RedshopHelperCart::cartFinalCalculation($callmodify);
	}

	/**
	 * Store Cart to Database
	 *
	 * @param   array  $cart   Cart
	 *
	 * @return  null
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function carttodb($cart = array())
	{
		return RedshopHelperCart::addCartToDatabase($cart);
	}

	/**
	 * Store Cart Attribute to Database
	 *
	 * @param   array    $attribute      Cart attribute data.
	 * @param   int      $cart_item_id   Cart item ID
	 * @param   int      $product_id     Cart product ID.
	 * @param   boolean  $isAccessary    Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function attributetodb($attribute = array(), $cart_item_id = 0, $product_id = 0, $isAccessary = false)
	{
		return RedshopHelperCart::addCartAttributeToDatabase($attribute, $cart_item_id, $product_id, $isAccessary);
	}

	/**
	 * Remove cart entry from table
	 *
	 * @param   int  $cart_id   #__redshop_usercart table key id
	 * @param   int  $userid    user information id - joomla #__users table key id
	 * @param   bool $delCart   remove cart from #__redshop_usercart table
	 *
	 * @return bool
	 *
	 * @deprecated  2.0.3  Use edshopHelperCart::removeCartFromDatabase() instead.
	 */
	public function removecartfromdb($cart_id = 0, $userid = 0, $delCart = false)
	{
		return RedshopHelperCart::removeCartFromDatabase($cart_id, $userid, $delCart);
	}

	/**
	 * Method for convert data from database to cart.
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::databaseToCart() instead.
	 */
	public function dbtocart($userId = 0)
	{
		RedshopHelperCart::databaseToCart($userId);
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   int  $cart_item_id       ID of cart item.
	 * @param   int  $is_accessory       Is accessory?
	 * @param   int  $parent_section_id  ID of parent section
	 * @param   int  $quantity           Quantity of product.
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3
	 */
	public function generateAttributeFromCart($cart_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		return RedshopHelperCart::generateAttributeFromCart($cart_item_id, $is_accessory, $parent_section_id, $quantity);
	}

	public function generateAccessoryFromCart($cart_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$cartItemdata = $this->getCartItemAccessoryDetail($cart_item_id);

		for ($i = 0, $in = count($cartItemdata); $i < $in; $i++)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($cartItemdata[$i]->product_id);
			$accessorypricelist = $this->_producthelper->getAccessoryPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$i]['accessory_id']     = $cartItemdata[$i]->product_id;
			$generateAccessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
			$generateAccessoryCart[$i]['accessory_price']  = $accessory_price;
			$generateAccessoryCart[$i]['accessory_childs'] = RedshopHelperCart::generateAttributeFromCart($cart_item_id, 1, $cartItemdata[$i]->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function getCartItemAccessoryDetail($cart_item_id = 0)
	{
		$list = null;

		if ($cart_item_id != 0)
		{
			$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_accessory_item "
				. "WHERE cart_item_id=" . (int) $cart_item_id;
			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectlist();
		}

		return $list;
	}

	public function getCartItemAttributeDetail($cart_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$db = JFactory::getDbo();

		$and = "";

		if ($cart_item_id != 0)
		{
			$and .= " AND cart_item_id=" . (int) $cart_item_id . " ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id=" . (int) $parent_section_id . " ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_attribute_item "
			. "WHERE is_accessory_att=" . (int) $is_accessory . " "
			. "AND section=" . $db->quote($section) . " "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array  $cartItem  Cart item
	 * @param   array  $data      User cart data
	 *
	 * @return  void
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  RedshopHelperDiscount::addGiftCardToCart()
	 */
	public function addGiftCardToCart(&$cartItem, $data)
	{
		RedshopHelperDiscount::addGiftCardToCart($cartItem, $data);
	}

	public function addProductToCart($data = array())
	{
		JPluginHelper::importPlugin('redshop_product');

		$dispatcher       = RedshopHelperUtility::getDispatcher();
		$redTemplate      = Redtemplate::getInstance();
		$user             = JFactory::getUser();
		$cart             = $this->_session->get('cart');
		$data['quantity'] = round($data['quantity']);

		if (!$cart || !array_key_exists("idx", $cart) || array_key_exists("quotation_id", $cart))
		{
			$cart        = array();
			$cart['idx'] = 0;
		}

		$idx = (int) ($cart['idx']);

		// Set session for giftcard
		if (isset($data['giftcard_id']) && $data['giftcard_id'])
		{
			$sameGiftCard = false;
			$section = 13;
			$row_data = RedshopHelperExtrafields::getSectionFieldList($section);

			for ($g = 0; $g < $idx; $g++)
			{
				if ($cart[$g]['giftcard_id'] == $data['giftcard_id'] && $cart[$g]['reciver_email'] == $data['reciver_email'] && $cart[$g]['reciver_name'] == $data['reciver_name'])
				{
					$sameGiftCard = true;

					// Product userfield
					if (!empty($row_data))
					{
						for ($r = 0, $countRowData = count($row_data); $r < $countRowData; $r++)
						{
							$produser_field = $row_data[$r]->name;

							if (isset($cart[$g][$produser_field]) && $data[$produser_field] != $cart[$g][$produser_field])
							{
								$sameGiftCard = false;
								break;
							}
						}
					}

					if (!$sameGiftCard)
					{
						continue;
					}

					$cart[$g]['quantity'] += $data['quantity'];
					RedshopHelperDiscount::addGiftCardToCart($cart[$g], $data);
				}
			}

			if (!$sameGiftCard)
			{
				$cart[$idx] = array();
				$cart[$idx]['quantity'] = $data['quantity'];
				RedshopHelperDiscount::addGiftCardToCart($cart[$idx], $data);
				$cart['idx'] = $idx + 1;
			}
		}

		// Set session for product
		else
		{
			$section = 12;
			$row_data = RedshopHelperExtrafields::getSectionFieldList($section);

			if (isset($data['hidden_attribute_cartimage']))
			{
				$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
			}

			$product_id = $data['product_id'];
			$quantity = $data['quantity'];
			$product_data = RedshopHelperProduct::getProductById($product_id);

			// Handle individual accessory add to cart price
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')
				&& isset($data['parent_accessory_product_id'])
				&& $data['parent_accessory_product_id'] != 0
				&& isset($data['accessory_id']))
			{
				$cart[$idx]['accessoryAsProductEligible'] = $data['accessory_id'];
				$accessoryInfo = RedshopHelperAccessory::getProductAccessories($data['accessory_id']);
				$product_data->product_price = $accessoryInfo[0]->newaccessory_price;

				$tempdata           = RedshopHelperProduct::getProductById($data['parent_accessory_product_id']);
				$producttemplate    = RedshopHelperTemplate::getTemplate("product", $tempdata->product_template);
				$accessory_template = $this->_producthelper->getAccessoryTemplate($producttemplate[0]->template_desc);
				$data_add           = $accessory_template->template_desc;
			}
			else
			{
				$producttemplate = $redTemplate->getTemplate("product", $product_data->product_template);
				$data_add = $producttemplate[0]->template_desc;
			}

			/*
			 * Check if required userfield are filled or not if not than redirect to product detail page...
			 * get product userfield from selected product template...
			 */
			if (!Redshop::getConfig()->get('AJAX_CART_BOX'))
			{
				$fieldreq = $this->userfieldValidation($data, $data_add, $section);

				if ($fieldreq != "")
				{
					return $fieldreq;
				}
			}

			// Get product price
			$data['product_price'] = 0;

			// Discount calculator procedure start
			$discountArr = $this->discountCalculatorData($product_data, $data);

			$calc_output = "";
			$calc_output_array = array();
			$product_price_tax = 0;
			$product_vat_price = 0;

			if (!empty($discountArr))
			{
				$calc_output = $discountArr[0];
				$calc_output_array = $discountArr[1];

				// Calculate price without VAT
				$data['product_price'] = $discountArr[2];

				$cart[$idx]['product_price_excl_vat'] = $discountArr[2];
				$product_vat_price += $discountArr[3];
				$cart[$idx]['discount_calc_price'] = $discountArr[2];
			}

			// Attribute price added
			$generateAttributeCart = isset($data['cart_attribute']) ? $data['cart_attribute'] : $this->generateAttributeArray($data);

			$retAttArr = $this->_producthelper->makeAttributeCart($generateAttributeCart, $product_data->product_id, 0, $data['product_price'], $quantity);
			$selectProp = $this->_producthelper->getSelectedAttributeArray($data);
			$data['product_old_price'] = $retAttArr[5] + $retAttArr[6];
			$data['product_old_price_excl_vat'] = $retAttArr[5];

			$data['product_price'] = $retAttArr[1];

			$product_vat_price = $retAttArr[2];
			$cart[$idx]['product_price_excl_vat'] = $retAttArr[1];
			$data['product_price'] += $product_vat_price;

			if (!empty($selectProp[0]))
			{
				$attributeImage = $product_id;

				if (count($selectProp[0]) == 1)
				{
					$attributeImage .= '_p' . $selectProp[0][0];
				}
				else
				{
					$pattributeImage = implode('_p', $selectProp[0]);
					$attributeImage .= '_p' . $pattributeImage;
				}

				if (count($selectProp[1]) == 1)
				{
					$attributeImage .= '_sp' . $selectProp[1][0];
				}
				else
				{
					$sattributeImage = implode('_sp', $selectProp[1]);

					if ($sattributeImage)
					{
						$attributeImage .= '_sp' . $sattributeImage;
					}
				}

				$cart[$idx]['attributeImage'] = $attributeImage . '.png';
			}

			if (!empty($data['reorder']) && !empty($data['attributeImage']))
			{
				$cart[$idx]['attributeImage'] = $data['attributeImage'];
			}

			$selectedAttrId = $retAttArr[3];
			$isStock = $retAttArr[4];
			$selectedPropId = $selectProp[0];
			$notselectedSubpropId = $retAttArr[8];
			$product_preorder = $product_data->preorder;
			$isPreorderStock = $retAttArr[7];

			// Check for the required attributes if selected
			if ($handleMessage = $this->handleRequiredSelectedAttributeCartMessage(
				$data,
				$data_add,
				$selectedAttrId,
				$selectedPropId,
				$notselectedSubpropId
			)
			)
			{
				return $handleMessage;
			}

			// Check for product or attribute in stock
			if (!$isStock)
			{
				if (($product_preorder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($product_preorder == "no") || ($product_preorder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));

					return $msg;
				}
				elseif (!$isPreorderStock)
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE'));

					return $msg;
				}
			}

			$cart[$idx]['subscription_id'] = 0;

			if ($product_data->product_type == 'subscription')
			{
				if (isset($data['subscription_id']) && $data['subscription_id'] != "")
				{
					$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($data['product_id'], $data['subscription_id']);
					$subscription_price = $subscription_detail->subscription_price;
					$subscription_vat = 0;

					if ($subscription_price)
					{
						$subscription_vat = RedshopHelperProduct::getProductTax($data['product_id'], $subscription_price);
					}

					$product_vat_price += $subscription_vat;
					$data['product_price'] = $data['product_price'] + $subscription_price + $subscription_vat;
					$data['product_old_price'] = $data['product_old_price'] + $subscription_price + $subscription_vat;
					$data['product_old_price_excl_vat'] += $subscription_price;
					$cart[$idx]['product_price_excl_vat'] += $subscription_price;
					$cart[$idx]['subscription_id'] = $data['subscription_id'];
				}
				else
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR_SUBSCRIPTION_PLAN'));

					return $msg;
				}
			}

			// Accessory price
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
			{
				if (isset($data['accessory_data']))
				{
					// Append previously added accessories as products
					if ($cart['AccessoryAsProduct'][0] != '')
					{
						$data['accessory_data']       = $cart['AccessoryAsProduct'][0] . '@@' . $data['accessory_data'];
						$data['acc_quantity_data']    = $cart['AccessoryAsProduct'][1] . '@@' . $data['acc_quantity_data'];
						$data['acc_attribute_data']   = $cart['AccessoryAsProduct'][2] . '@@' . $data['acc_attribute_data'];
						$data['acc_property_data']    = $cart['AccessoryAsProduct'][3] . '@@' . $data['acc_property_data'];
						$data['acc_subproperty_data'] = $cart['AccessoryAsProduct'][4] . '@@' . $data['acc_subproperty_data'];
					}

					$cart['AccessoryAsProduct'] = array(
						$data['accessory_data'],
						$data['acc_quantity_data'],
						$data['acc_attribute_data'],
						$data['acc_property_data'],
						$data['acc_subproperty_data']
					);
				}

				$generateAccessoryCart        = array();
				$data['accessory_data']       = "";
				$data['acc_quantity_data']    = "";
				$data['acc_attribute_data']   = "";
				$data['acc_property_data']    = "";
				$data['acc_subproperty_data'] = "";
			}
			else
			{
				$generateAccessoryCart = isset($data['cart_accessory']) ? $data['cart_accessory'] : $this->generateAccessoryArray($data);

				if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
				{
					if (is_bool($generateAccessoryCart))
					{
						return JText::_('COM_REDSHOP_ACCESSORY_HAS_REQUIRED_ATTRIBUTES');
					}
					elseif (!$generateAccessoryCart)
					{
						return false;
					}
				}
			}

			$retAccArr = $this->_producthelper->makeAccessoryCart($generateAccessoryCart, $product_data->product_id);
			$accessory_total_price = $retAccArr[1];
			$accessory_vat_price = $retAccArr[2];

			$cart[$idx]['product_price_excl_vat'] += $accessory_total_price;
			$data['product_price'] += $accessory_total_price + $accessory_vat_price;
			$data['product_old_price'] += $accessory_total_price + $accessory_vat_price;
			$data['product_old_price_excl_vat'] += $accessory_total_price;
			$cart[$idx]['product_vat'] = $product_vat_price + $accessory_vat_price;

			// ADD WRAPPER PRICE
			$wrapper_price = 0;
			$wrapper_vat = 0;

			if (isset($data['sel_wrapper_id']) && $data['sel_wrapper_id'])
			{
				$wrapperArr = $this->getWrapperPriceArr(array('product_id' => $data['product_id'], 'wrapper_id' => $data['sel_wrapper_id']));
				$wrapper_vat = $wrapperArr['wrapper_vat'];
				$wrapper_price = $wrapperArr['wrapper_price'];
			}

			$cart[$idx]['product_vat'] += $wrapper_vat;
			$data['product_price'] += $wrapper_price + $wrapper_vat;
			$data['product_old_price'] += $wrapper_price + $wrapper_vat;
			$data['product_old_price_excl_vat'] += $wrapper_price;
			$cart[$idx]['product_price_excl_vat'] += $wrapper_price;

			// Checking For same Product and update Quantity
			$selectAcc = $this->_producthelper->getSelectedAccessoryArray($data);
			$selectAtt = $this->_producthelper->getSelectedAttributeArray($data);

			$sameProduct = false;

			for ($i = 0; $i < $idx; $i++)
			{
				if ($cart[$i]['product_id'] == $data['product_id'])
				{
					$sameProduct = true;

					if (isset($data['subscription_id']) && $cart[$i]['subscription_id'] != $data['subscription_id'])
					{
						$sameProduct = false;
					}

					if (isset($data['sel_wrapper_id']) && $cart[$i]['wrapper_id'] != $data['sel_wrapper_id'])
					{
						$sameProduct = false;
					}

					$prevSelectAtt = $this->getSelectedCartAttributeArray($cart[$i]['cart_attribute']);

					$newdiff1 = array_diff($prevSelectAtt[0], $selectAtt[0]);
					$newdiff2 = array_diff($selectAtt[0], $prevSelectAtt[0]);

					if (count($newdiff1) > 0 || count($newdiff2) > 0)
					{
						$sameProduct = false;
					}

					if (!empty($discountArr)
						&& ($cart[$i]["discount_calc"]["calcWidth"] != $data["calcWidth"]
							|| $cart[$i]["discount_calc"]["calcDepth"] != $data["calcDepth"])
					)
					{
						$sameProduct = false;
					}

					$newdiff1 = array_diff($prevSelectAtt[1], $selectAtt[1]);
					$newdiff2 = array_diff($selectAtt[1], $prevSelectAtt[1]);

					if (count($newdiff1) > 0 || count($newdiff2) > 0)
					{
						$sameProduct = false;
					}

					$prevSelectAcc = $this->getSelectedCartAccessoryArray($cart[$i]['cart_accessory']);

					$newdiff1 = array_diff($prevSelectAcc[0], $selectAcc[0]);
					$newdiff2 = array_diff($selectAcc[0], $prevSelectAcc[0]);

					if (count($newdiff1) > 0 || count($newdiff2) > 0)
					{
						$sameProduct = false;
					}

					$newdiff1 = array_diff($prevSelectAcc[1], $selectAcc[1]);
					$newdiff2 = array_diff($selectAcc[1], $prevSelectAcc[1]);

					if (count($newdiff1) > 0 || count($newdiff2) > 0)
					{
						$sameProduct = false;
					}

					$newdiff1 = array_diff($prevSelectAcc[2], $selectAcc[2]);
					$newdiff2 = array_diff($selectAcc[2], $prevSelectAcc[2]);

					if (count($newdiff1) > 0 || count($newdiff2) > 0)
					{
						$sameProduct = false;
					}

					// Discount calculator
					$array_diff_calc = array_diff_assoc($cart[$i]['discount_calc'], $calc_output_array);

					if (count($array_diff_calc) > 0)
					{
						$sameProduct = false;
					}

					/**
					 * Previous comment stated it is not used anymore.
					 * Changing it for another purpose. It can intercept and decide whether added product should be added as same or new product.
					 */
					$dispatcher->trigger('checkSameCartProduct', array(&$cart, $data, &$sameProduct, $i));

					// Product userfield
					if (!empty($row_data))
					{
						$puf = 1;

						for ($r = 0, $rn = count($row_data); $r < $rn; $r++)
						{
							$produser_field = $row_data[$r]->name;
							$added_userfield = $data[$produser_field];

							if (isset($cart[$i][$produser_field]) && $added_userfield != $cart[$i][$produser_field])
							{
								$puf = 0;
							}
						}

						if ($puf != 1)
						{
							$sameProduct = false;
						}
					}

					if ($sameProduct)
					{
						$newQuantity = $cart[$i]['quantity'] + $data['quantity'];
						$newcartquantity = $this->checkQuantityInStock($cart[$i], $newQuantity);

						if ($newQuantity > $newcartquantity)
						{
							$cart['notice_message'] = $newcartquantity . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
						}
						else
						{
							$cart['notice_message'] = "";
						}

						if ($newcartquantity != $cart[$i]['quantity'])
						{
							$cart[$i]['quantity'] = $quantity;

							/*
							 * trigger the event of redSHOP product plugin support on Same product is going to add into cart
							 *
							 * Usually redSHOP update quantity
							 */
							$dispatcher->trigger('onSameCartProduct', array(& $cart, $data, $i));

							$this->_session->set('cart', $cart);
							$data['cart_index'] = $i;
							$data['quantity'] = $newcartquantity;
							$data['checkQuantity'] = $newcartquantity;

							/** @var RedshopModelCart $cartModel */
							$cartModel = RedshopModel::getInstance('cart', 'RedshopModel');
							$cartModel->update($data);

							return true;
						}
						else
						{
							$msg = (Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && Redshop::getConfig()->get('IS_PRODUCT_RESERVE')) ? Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));

							return $msg;
						}
					}
				}
			}

			// Set product price
			if ($data['product_price'] < 0)
			{
				$data['product_price'] = 0;
			}

			$per_product_total = $product_data->minimum_per_product_total;

			if ($data['product_price'] < $per_product_total)
			{
				$msg = JText::_('COM_REDSHOP_PER_PRODUCT_TOTAL') . " " . $per_product_total;

				return $msg;
			}

			if (!$sameProduct)
			{
				// SET VALVUES INTO SESSION CART
				$cart[$idx]['giftcard_id'] = '';
				$cart[$idx]['product_id'] = $data['product_id'];
				$cart[$idx]['discount_calc_output'] = $calc_output;
				$cart[$idx]['discount_calc'] = $calc_output_array;
				$cart[$idx]['product_price'] = $data['product_price'];
				$cart[$idx]['product_old_price'] = $data['product_old_price'];
				$cart[$idx]['product_old_price_excl_vat'] = $data['product_old_price_excl_vat'];
				$cart[$idx]['cart_attribute'] = $generateAttributeCart;

				$cart[$idx]['cart_accessory'] = $generateAccessoryCart;

				if (isset($data['hidden_attribute_cartimage']))
				{
					$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
				}

				$cart[$idx]['quantity'] = 0;

				$newQuantity = $data['quantity'];
				$cart[$idx]['quantity'] = $this->checkQuantityInStock($cart[$idx], $newQuantity);

				if ($newQuantity > $cart[$idx]['quantity'])
				{
					$cart['notice_message'] = $cart[$idx]['quantity'] . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
				}
				else
				{
					$cart['notice_message'] = "";
				}

				if ($cart[$idx]['quantity'] <= 0)
				{
					$msg = (Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && Redshop::getConfig()->get('IS_PRODUCT_RESERVE')) ? Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

					return $msg;
				}

				$cart[$idx]['category_id'] = $data['category_id'];
				$cart[$idx]['wrapper_id'] = $data['sel_wrapper_id'];
				$cart[$idx]['wrapper_price'] = $wrapper_price + $wrapper_vat;

				/**
				 * Implement new plugin support before session update
				 * trigger the event of redSHOP product plugin support on Before cart session is set - on prepare cart session
				 */
				$dispatcher->trigger('onBeforeSetCartSession', array(&$cart, $data, $idx));

				$cart['idx'] = $idx + 1;

				for ($i = 0, $in = count($row_data); $i < $in; $i++)
				{
					$field_name = $row_data[$i]->name;
					$data_txt = (isset($data[$field_name])) ? $data[$field_name] : '';
					$tmpstr = strpbrk($data_txt, '`');

					if ($tmpstr)
					{
						$data_txt = str_replace('`', ',', $data_txt);
					}

					$cart[$idx][$field_name] = $data_txt;
				}
			}
		}

		if (!isset($cart['discount_type']) || !$cart['discount_type'])
		{
			$cart['discount_type'] = 0;
		}

		if (!isset($cart['discount']) || !$cart['discount'])
		{
			$cart['discount'] = 0;
		}

		if (!isset($cart['cart_discount']) || !$cart['cart_discount'])
		{
			$cart['cart_discount'] = 0;
		}

		if (!isset($cart['user_shopper_group_id']) || (isset($cart['user_shopper_group_id']) && $cart['user_shopper_group_id'] == 0))
		{
			$cart['user_shopper_group_id'] = RedshopHelperUser::getShopperGroup($user->id);
		}

		$cart['free_shipping'] = 0;

		$this->_session->set('cart', $cart);

		return true;
	}

	public function userfieldValidation($data, $data_add, $section = 12)
	{
		$returnArr    = $this->_producthelper->getProductUserfieldFromTemplate($data_add);
		$userfieldArr = $returnArr[1];

		$msg = "";

		if (count($userfieldArr) > 0)
		{
			$req_fields = RedshopHelperExtrafields::getSectionFieldList($section, 1, 1, 1);

			for ($i = 0, $in = count($req_fields); $i < $in; $i++)
			{
				if (in_array($req_fields[$i]->name, $userfieldArr))
				{
					if (!isset($data[$req_fields[$i]->name]) || (isset($data[$req_fields[$i]->name]) && $data[$req_fields[$i]->name] == ""))
					{
						$msg .= $req_fields[$i]->title . " " . JText::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
					}
				}
			}
		}

		return $msg;
	}

	/**
	 * @param   array  $data
	 * @param   int    $user_id
	 *
	 * @return  array|bool
	 *
	 * @throws  Exception
	 */
	public function generateAccessoryArray($data, $user_id = 0)
	{
		$generateAccessoryCart = array();

		if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
		{
			$accessoryData    = explode("@@", $data['accessory_data']);
			$accQuantityData = array();

			if (isset($data['acc_quantity_data']))
			{
				$accQuantityData = explode("@@", $data['acc_quantity_data']);
			}

			for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
			{
				$accessory          = RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
				$accessoryPriceList = $this->_producthelper->getAccessoryPrice(
					$data['product_id'], $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1, $user_id
				);
				$accessory_price    = $accessoryPriceList[0];
				$acc_quantity       = (isset($accQuantityData[$i]) && $accQuantityData[$i]) ? $accQuantityData[$i] : $data['quantity'];

				$generateAccessoryCart[$i]['accessory_id']       = $accessoryData[$i];
				$generateAccessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
				$generateAccessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
				$generateAccessoryCart[$i]['accessory_price']    = $accessory_price * $acc_quantity;
				$generateAccessoryCart[$i]['accessory_quantity'] = $acc_quantity;

				$accAttributeCart = array();

				if ($data['acc_attribute_data'] != "" && $data['acc_attribute_data'] != 0)
				{
					$acc_attribute_data = explode('@@', $data['acc_attribute_data']);

					if ($acc_attribute_data[$i] != "")
					{
						$acc_attribute_data = explode('##', $acc_attribute_data[$i]);
						$countAccessoryAttribute = count($acc_attribute_data);

						for ($ia = 0; $ia < $countAccessoryAttribute; $ia++)
						{
							$accPropertyCart                         = array();
							$attribute                               = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $acc_attribute_data[$ia]);
							$accAttributeCart[$ia]['attribute_id']   = $acc_attribute_data[$ia];
							$accAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

							if ($attribute[0]->text != "" && $data['acc_property_data'] != "" && $data['acc_property_data'] != 0)
							{
								$acc_property_data = explode('@@', $data['acc_property_data']);
								$acc_property_data = explode('##', $acc_property_data[$i]);

								if (empty($acc_property_data[$ia]) && $attribute[0]->attribute_required == 1)
								{
									return array();
								}

								if (isset($acc_property_data[$ia]) && $acc_property_data[$ia] != "")
								{
									$acc_property_data = explode(',,', $acc_property_data[$ia]);
									$countAccessoryProperty = count($acc_property_data);

									for ($ip = 0; $ip < $countAccessoryProperty; $ip++)
									{
										$accSubpropertyCart = array();
										$property_price     = 0;
										$property           = RedshopHelperProduct_Attribute::getAttributeProperties($acc_property_data[$ip]);
										$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($acc_property_data[$ip], $data['quantity'], 'property', $user_id);

										if (count($pricelist) > 0)
										{
											$property_price = $pricelist->product_price;
										}
										else
										{
											$property_price = $property[0]->property_price;
										}

										$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
										$accPropertyCart[$ip]['property_name']   = $property[0]->text;
										$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
										$accPropertyCart[$ip]['property_price']  = $property_price;

										if ($data['acc_subproperty_data'] != "" && $data['acc_subproperty_data'] != 0)
										{
											$acc_subproperty_data = explode('@@', $data['acc_subproperty_data']);
											$acc_subproperty_data = @explode('##', $acc_subproperty_data[$i]);
											$acc_subproperty_data = @explode(',,', $acc_subproperty_data[$ia]);


											if (isset($acc_subproperty_data[$ip]) && $acc_subproperty_data[$ip] != "")
											{
												$acc_subproperty_data = explode('::', $acc_subproperty_data[$ip]);
												$countAccessorySubproperty = count($acc_subproperty_data);

												for ($isp = 0; $isp < $countAccessorySubproperty; $isp++)
												{
													$subproperty_price = 0;
													$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($acc_subproperty_data[$isp]);
													$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($acc_subproperty_data[$isp], $data['quantity'], 'subproperty', $user_id);

													if (count($pricelist) > 0)
													{
														$subproperty_price = $pricelist->product_price;
													}
													else
													{
														$subproperty_price = $subproperty[0]->subattribute_color_price;
													}

													$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subproperty_data[$isp];
													$accSubpropertyCart[$isp]['subproperty_name']   = $subproperty[0]->text;
													$accSubpropertyCart[$isp]['subproperty_oprand'] = $subproperty[0]->oprand;
													$accSubpropertyCart[$isp]['subproperty_price']  = $subproperty_price;
												}
											}
										}

										$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
									}
								}
							}

							$accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
						}
					}
				}
				else
				{
					$attribute_set_id   = $this->getAttributeSetId($accessory[0]->child_product_id);
					$attributes_acc_set = array();

					if ($attribute_set_id > 0)
					{
						$attributes_acc_set = $this->getProductAccAttribute($accessory[0]->child_product_id, $attribute_set_id, 0, 0, 1);
					}

					$requireAttribute = RedshopHelperProduct_Attribute::getProductAttribute($accessory[0]->child_product_id, 0, 0, 0, 1);
					$requireAttribute = array_merge($requireAttribute, $attributes_acc_set);

					if (count($requireAttribute) > 0)
					{
						$requied_attributeArr = array();

						for ($re = 0, $countAttribute = count($requireAttribute); $re < $countAttribute; $re++)
						{
							$requied_attributeArr[$re] = urldecode($requireAttribute[$re]->attribute_name);
						}

						$requied_attribute_name = implode(", ", $requied_attributeArr);

						// Throw an error as first attribute is required
						$msg      = urldecode($requied_attribute_name) . " " . JText::_('IS_REQUIRED');
						JFactory::getApplication()->enqueueMessage($msg);

						return false;
					}
				}

				$generateAccessoryCart[$i]['accessory_childs'] = $accAttributeCart;
			}
		}

		return $generateAccessoryCart;
	}

	public function getProductAccAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			// Secure productsIds
			if ($productsIds = explode(',', $product_id))
			{
				$productsIds = Joomla\Utilities\ArrayHelper::toInteger($productsIds);

				$and .= "AND p.product_id IN (" . implode(',', $productsIds) . ") ";
			}
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id=" . (int) $attribute_set_id . " ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published=" . (int) $published . " ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required=" . (int) $attribute_required . " ";
		}

		if ($notAttributeId != 0)
		{
			// Secure notAttributeId
			if ($notAttributeIds = explode(',', $notAttributeId))
			{
				$notAttributeIds = Joomla\Utilities\ArrayHelper::toInteger($notAttributeIds);

				$and .= "AND a.attribute_id NOT IN (" . implode(',', $notAttributeIds) . ") ";
			}
		}

		$query = "SELECT a.attribute_id AS value,a.attribute_name AS text,a.*,ast.attribute_set_name "
			. "FROM " . $this->_table_prefix . "product_attribute AS a "
			. "LEFT JOIN " . $this->_table_prefix . "attribute_set AS ast ON ast.attribute_set_id=a.attribute_set_id "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.attribute_set_id=a.attribute_set_id " . $astpublished
			. "WHERE a.attribute_name!='' "
			. $and
			. " and attribute_published=1 ORDER BY a.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttributeSetId($pid)
	{
		return RedshopEntityProduct::getInstance($pid)->loadItem()->get('attribute_set_id');
	}

	public function generateAttributeArray($data, $user_id = 0)
	{
		$generateAttributeCart = array();

		$attribute_data       = explode('##', $data['attribute_data']);
		$acc_property_data    = explode('##', $data['property_data']);
		$acc_subproperty_data = !empty($data['subproperty_data']) ? explode('##', $data['subproperty_data']) : null;

		if ($data['attribute_data'] != "" && $data['attribute_data'] != 0)
		{
			$countAttribute = count($attribute_data);

			for ($ia = 0; $ia < $countAttribute; $ia++)
			{
				$prooprand                                    = array();
				$proprice                                     = array();
				$accPropertyCart                              = array();
				$attribute                                    = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $attribute_data[$ia]);
				$generateAttributeCart[$ia]['attribute_id']   = $attribute_data[$ia];
				$generateAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

				if ($attribute[0]->text != "" && ($data['property_data'] != "" || $data['property_data'] != 0))
				{
					if (isset($acc_property_data[$ia]) && $acc_property_data[$ia] != "")
					{
						$accessoriesPropertiesData = explode(',,', $acc_property_data[$ia]);
						$countProperty = count($accessoriesPropertiesData);

						for ($ip = 0; $ip < $countProperty; $ip++)
						{
							$accSubpropertyCart = array();
							$property_price     = 0;
							$property           = RedshopHelperProduct_Attribute::getAttributeProperties($accessoriesPropertiesData[$ip]);
							$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($accessoriesPropertiesData[$ip], $data['quantity'], 'property', $user_id);

							if (count($pricelist) > 0)
							{
								$property_price = $pricelist->product_price;
							}
							else
							{
								$property_price = $property[0]->property_price;
							}

							$accPropertyCart[$ip]['property_id']     = $accessoriesPropertiesData[$ip];
							$accPropertyCart[$ip]['attribute_id']    = $property[0]->attribute_id;
							$accPropertyCart[$ip]['property_name']   = $property[0]->text;
							$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
							$accPropertyCart[$ip]['property_price']  = $property_price;
							$prooprand[$ip]                          = $property[0]->oprand;
							$proprice[$ip]                           = $property_price;

							if (!empty($acc_subproperty_data))
							{
								$subPropertiesData = @explode(',,', $acc_subproperty_data[$ia]);

								if (isset($subPropertiesData[$ip]) && $subPropertiesData[$ip] != "")
								{
									$subSubPropertyData = explode('::', $subPropertiesData[$ip]);
									$countSubproperty = count($subSubPropertyData);

									for ($isp = 0; $isp < $countSubproperty; $isp++)
									{
										$subproperty_price = 0;
										$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($subSubPropertyData[$isp]);

										$pricelist = RedshopHelperProduct_Attribute::getPropertyPrice($subSubPropertyData[$isp], $data['quantity'], 'subproperty', $user_id);

										if (count($pricelist) > 0)
										{
											$subproperty_price = $pricelist->product_price;
										}
										else
										{
											$subproperty_price = $subproperty[0]->subattribute_color_price;
										}

										$accSubpropertyCart[$isp]['subproperty_id']           = $subSubPropertyData[$isp];
										$accSubpropertyCart[$isp]['subproperty_name']         = $subproperty[0]->text;
										$accSubpropertyCart[$isp]['subproperty_oprand']       = $subproperty[0]->oprand;
										$accSubpropertyCart[$isp]['subattribute_color_title'] = $subproperty[0]->subattribute_color_title;
										$accSubpropertyCart[$isp]['subproperty_price']        = $subproperty_price;
									}
								}
							}

							$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
						}
					}
				}

				if (!empty($accPropertyCart))
				{
					$generateAttributeCart[array_search($accPropertyCart[0]['attribute_id'], $attribute_data)]['attribute_childs'] = $accPropertyCart;
				}
			}
		}

		return $generateAttributeCart;
	}

	public function getSelectedCartAttributeArray($attArr = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
			{
				$selectedproperty[] = $propArr[$k]['property_id'];
				$subpropArr         = $propArr[$k]['property_childs'];

				for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
				{
					$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
				}
			}
		}

		$ret = array($selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function getSelectedCartAccessoryArray($attArr = array())
	{
		$selectedAccessory   = array();
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$selectedAccessory[] = $attArr[$i]['accessory_id'];
			$attchildArr         = $attArr[$i]['accessory_childs'];

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$selectedproperty[] = $propArr[$k]['property_id'];
					$subpropArr         = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty);

		return $ret;
	}

	/**
	 * @param   int  $order_item_id
	 * @param   int  $is_accessory
	 * @param   int  $parent_section_id
	 * @param   int  $quantity
	 *
	 * @return  array
	 */
	public function generateAttributeFromOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		$generateAttributeCart = array();

		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
		{
			$accPropertyCart                             = array();
			$generateAttributeCart[$i]['attribute_id']   = $orderItemAttdata[$i]->section_id;
			$generateAttributeCart[$i]['attribute_name'] = $orderItemAttdata[$i]->section_name;

			$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

			for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
			{
				$accSubpropertyCart = array();
				$property_price     = 0;
				$property           = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id);
				$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($orderPropdata[$p]->section_id, $quantity, 'property');

				if (count($pricelist) > 0)
				{
					$property_price = $pricelist->product_price;
				}
				else
				{
					$property_price = $property[0]->property_price;
				}

				$accPropertyCart[$p]['property_id']     = $orderPropdata[$p]->section_id;
				$accPropertyCart[$p]['property_name']   = $property[0]->text;
				$accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
				$accPropertyCart[$p]['property_price']  = $property_price;

				$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

				for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
				{
					$subproperty_price = 0;
					$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id);
					$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($orderSubpropdata[$sp]->section_id, $quantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subproperty_price = $pricelist->product_price;
					}
					else
					{
						$subproperty_price = $subproperty[0]->subattribute_color_price;
					}

					$accSubpropertyCart[$sp]['subproperty_id']     = $orderSubpropdata[$sp]->section_id;
					$accSubpropertyCart[$sp]['subproperty_name']   = $subproperty[0]->text;
					$accSubpropertyCart[$sp]['subproperty_oprand'] = $subproperty[0]->oprand;
					$accSubpropertyCart[$sp]['subproperty_price']  = $subproperty_price;
				}

				$accPropertyCart[$p]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
		}

		return $generateAttributeCart;
	}

	public function generateAccessoryFromOrder($order_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$orderItemdata = RedshopHelperOrder::getOrderItemAccessoryDetail($order_item_id);

		foreach ($orderItemdata as $index => $orderItem)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($orderItem->product_id);
			$accessorypricelist = $this->_producthelper->getAccessoryPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$index]['accessory_id']       = $orderItem->product_id;
			$generateAccessoryCart[$index]['accessory_name']     = $accessory[0]->product_name;
			$generateAccessoryCart[$index]['accessory_oprand']   = $accessory[0]->oprand;
			$generateAccessoryCart[$index]['accessory_price']    = $accessory_price;
			$generateAccessoryCart[$index]['accessory_quantity'] = $orderItem->product_quantity;
			$generateAccessoryCart[$index]['accessory_childs']   = $this->generateAttributeFromOrder($order_item_id, 1, $orderItem->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function discountCalculatorData($product_data, $data)
	{
		$use_discount_calculator = $product_data->use_discount_calc;
		$discount_calc_method    = $product_data->discount_calc_method;
		$use_range               = $product_data->use_range;
		$calc_output             = "";
		$calc_output_array       = array();

		if ($use_discount_calculator)
		{
			$discount_cal = $this->discountCalculator($data);

			$calculator_price  = $discount_cal['product_price'];
			$product_price_tax = $discount_cal['product_price_tax'];

			if ($calculator_price)
			{
				$calc_output               = "Type : " . $discount_calc_method . "<br />";
				$calc_output_array['type'] = $discount_calc_method;

				if ($use_range)
				{
					$calcHeight        = @$data['calcHeight'];
					$calcWidth         = @$data['calcWidth'];
					$calcDepth         = @$data['calcDepth'];
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = "";
					$totalPiece        = "";
				}
				else
				{
					$calcHeight        = @$product_data->product_height;
					$calcWidth         = @$product_data->product_width;
					$calcDepth         = @$product_data->product_length;
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = @$discount_cal['price_per_piece'];
					$totalPiece        = @$discount_cal['total_piece'];
				}

				switch ($discount_calc_method)
				{
					case "volume":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_HEIGHT') . " " . $calcHeight . "<br />";
						$calc_output_array['calcHeight'] = $calcHeight;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_LENGTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "area":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_DEPTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "circumference":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_RADIUS') . " " . $calcRadius . "<br />";
						$calc_output_array['calcRadius'] = $calcRadius;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}
						break;
				}

				$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
				$calc_output_array['calcUnit'] = $data['calcUnit'];

				// Extra selected value data
				$calc_output .= "<br />" . $discount_cal['pdcextra_data'];

				// Extra selected value ids
				$calc_output_array['calcextra_ids'] = $discount_cal['pdcextra_ids'];

				$discountArr[] = $calc_output;
				$discountArr[] = $calc_output_array;
				$discountArr[] = $calculator_price;
				$discountArr[] = $product_price_tax;

				return $discountArr;
			}
			else
			{
				return array();
			}
		}
	}

	/**
	 * Discount calculator Ajax Function
	 *
	 * @param   array  $get
	 *
	 * @return  array
	 */
	public function discountCalculator($get)
	{
		$productId = (int) $get['product_id'];

		$discount_cal = array();

		$productPrice = RedshopHelperProductPrice::getNetPrice($productId);

		$product_price = $productPrice['product_price_novat'];

		$data = RedshopHelperProduct::getProductById($productId);

		// Default calculation method
		$calcMethod = $data->discount_calc_method;

		// Default calculation unit
		$globalUnit = "m";

		// Use range or not
		$use_range = $data->use_range;

		$calcHeight = $get['calcHeight'];
		$calcWidth  = $get['calcWidth'];
		$calcLength = $get['calcDepth'];
		$calcRadius = $get['calcRadius'];
		$calcUnit   = trim($get['calcUnit']);

		$calcHeight = str_replace(",", ".", $calcHeight);
		$calcWidth  = str_replace(",", ".", $calcWidth);
		$calcLength = str_replace(",", ".", $calcLength);
		$calcRadius = $cart_mdata = str_replace(",", ".", $calcRadius);
		$calcUnit   = $cart_mdata = str_replace(",", ".", $calcUnit);

		// Convert unit using helper function
		$unit = 1;
		$unit = $this->_producthelper->getUnitConversation($globalUnit, $calcUnit);

		$calcHeight *= $unit;
		$calcWidth *= $unit;
		$calcLength *= $unit;
		$calcRadius *= $unit;

		$product_unit = 1;

		if (!$use_range)
		{
			$product_unit = $this->_producthelper->getUnitConversation($globalUnit, Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

			$product_height   = $data->product_height * $product_unit;
			$product_width    = $data->product_width * $product_unit;
			$product_length   = $data->product_length * $product_unit;
			$product_diameter = $data->product_diameter * $product_unit;
		}

		$finalArea = 0;
		$Area      = 0;

		switch ($calcMethod)
		{
			case "volume":

				$Area = $calcHeight * $calcWidth * $calcLength;

				if (!$use_range)
					$product_area = $product_height * $product_width * $product_length;
				break;

			case "area":
				$Area = $calcLength * $calcWidth;

				if (!$use_range)
					$product_area = $product_length * $product_width;
				break;

			case "circumference":

				$Area = 2 * PI * $calcRadius;

				if (!$use_range)
					$product_area = PI * $product_diameter;
				break;
		}

		$finalArea = $Area;

		if ($use_range)
		{
			$finalArea = number_format($finalArea, 8, '.', '');

			// Calculation prices as per various area
			$discount_calc_data = $this->getDiscountCalcData($finalArea, $productId);

		}
		else
		{
			// Shandard size of product
			$final_product_Area = $product_area;

			// Total sheet calculation
			if ($final_product_Area <= 0)
				$final_product_Area = 1;
			$total_sheet = $finalArea / $final_product_Area;

			// Returns the next highest integer value by rounding up value if necessary.
			if (isset($data->allow_decimal_piece) && $data->allow_decimal_piece)
			{
				$total_sheet = ceil($total_sheet);
			}

			// If sheet is less than 0 or equal to 0 than
			if ($total_sheet <= 0)
				$total_sheet = 1;

			// Product price of all sheets
			$product_price_total = $total_sheet * $product_price;

			$discount_calc_data = array();
			$discount_calc_data[0] = new stdClass;

			// Generating array
			$discount_calc_data[0]->area_price         = $product_price;
			$discount_calc_data[0]->discount_calc_unit = $product_unit;
			$discount_calc_data[0]->price_per_piece    = $product_price_total;
		}

		$area_price          = 0;
		$price_per_piece     = 0;
		$price_per_piece_tax = 0;
		$conversation_unit   = "m";

		if (count($discount_calc_data))
		{
			$area_price = $discount_calc_data[0]->area_price;

			// Discount calculator extra price enhancement
			$pdcextraid = $get['pdcextraid'];
			$pdcstring  = $pdcids = array();

			if (trim($pdcextraid) != "")
			{
				$pdcextradatas = $this->getDiscountCalcDataExtra($pdcextraid);

				for ($pdc = 0, $countExtrafield = count($pdcextradatas); $pdc < $countExtrafield; $pdc++)
				{
					$pdcextradata = $pdcextradatas[$pdc];
					$option_name  = $pdcextradata->option_name;
					$pdcprice     = $pdcextradata->price;
					$pdcoprand    = $pdcextradata->oprand;
					$pdcextra_id  = $pdcextradata->pdcextra_id;

					$pdcstring[] = $option_name . ' (' . $pdcoprand . ' ' . $pdcprice . ' )';
					$pdcids[]    = $pdcextra_id;

					switch ($pdcoprand)
					{
						case "+":
							$area_price += $pdcprice;
							break;
						case "-":
							$area_price -= $pdcprice;
							break;
						case "%":
							$area_price *= 1 + ($pdcprice / 100);
							break;
					}
				}
			}

			// Applying TAX
			$chktag              = $this->_producthelper->getApplyattributeVatOrNot();

			$conversation_unit = $discount_calc_data[0]->discount_calc_unit;

			if ($use_range)
			{
				$display_final_area = $finalArea / ($unit * $unit);

				$price_per_piece = $area_price * $finalArea;

				$price_per_piece = $area_price;

				$formatted_price_per_area = RedshopHelperProductPrice::formattedPrice($area_price);

				$price_per_piece_tax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $display_final_area . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $price_per_piece_tax . "\n";
				echo $chktag . "\n";
			}
			else
			{
				$price_per_piece = $discount_calc_data[0]->price_per_piece;

				$price_per_piece_tax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $Area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $total_sheet . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $price_per_piece_tax . "\n";
				echo $chktag . "\n";
			}
		}
		else
		{
			$price_per_piece = false;
			echo "fail";
		}

		$discount_cal['product_price']     = $price_per_piece;
		$discount_cal['product_price_tax'] = $price_per_piece_tax;
		$discount_cal['pdcextra_data']     = "";

		if (isset($pdcstring) && count($pdcstring) > 0)
		{
			$discount_cal['pdcextra_data'] = implode("<br />", $pdcstring);
		}

		$discount_cal['pdcextra_ids']      = '';

		if (isset($pdcids) && (count($pdcids) > 0))
		{
			$discount_cal['pdcextra_ids'] = implode(",", $pdcids);
		}

		if (isset($total_sheet))
		{
			$discount_cal['total_piece']       = $total_sheet;
		}

		$discount_cal['price_per_piece']   = $area_price;

		return $discount_cal;
	}

	/**
	 * Funtion get Discount calculation data
	 *
	 * @param   number  $area         default value is 0
	 * @param   number  $pid          default value can be null
	 * @param   number  $areabetween  default value is 0
	 *
	 * @return object
	 */
	public function getDiscountCalcData($area = 0, $pid = 0, $areabetween = 0)
	{
		$query = $this->_db->getQuery(true)
			->select("*")
			->from($this->_db->quoteName("#__redshop_product_discount_calc"))
			->where($this->_db->quoteName("product_id") . "=" . (int) $pid)
			->order("id ASC");

		if ($areabetween)
		{
			$query->where((floatval($area)) . " BETWEEN `area_start` AND `area_end` ");
		}

		if ($area)
		{
			$query->where($this->_db->quoteName("area_start_converted") . "<=" . floatval($area))
				->where($this->_db->quoteName("area_end_converted") . ">=" . floatval($area));
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * @param   string  $pdcextraids
	 * @param   int     $productId
	 *
	 * @return  mixed
	 */
	public function getDiscountCalcDataExtra($pdcextraids = "", $productId = 0)
	{
		return RedshopHelperCartDiscount::getDiscountCalcDataExtra($pdcextraids, $productId);
	}

	/**
	 * Handle required attribute before add in to cart messages
	 *
	 * @param   array   $data                  cart data
	 * @param   string  $attributeTemplate     Attribute added data
	 * @param   array   $selectedAttrId        Selected attribute id for add to cart
	 * @param   array   $selectedPropId        Selected Property Id for Add to cart
	 * @param   array   $notselectedSubpropId  Not selected subproperty ids during add to cart
	 *
	 * @return  string  Error Message if found otherwise return null.
	 */
	public function handleRequiredSelectedAttributeCartMessage($data, $attributeTemplate, $selectedAttrId, $selectedPropId, $notselectedSubpropId)
	{
		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			return;
		}

		// Check if required attribute is filled or not ...
		$attributeTemplateArray = $this->_producthelper->getAttributeTemplate($attributeTemplate);

		if (!empty($attributeTemplateArray))
		{
			$selectedAttributId = 0;

			if (count($selectedAttrId) > 0)
			{
				$selectedAttributId = implode(",", $selectedAttrId);
			}

			$requiredAttribute = RedshopHelperProduct_Attribute::getProductAttribute(
								$data['product_id'],
								0,
								0,
								0,
								1,
								$selectedAttributId
							);

			if (!empty($requiredAttribute))
			{
				$requiredAttributeArray = array();

				for ($re = 0, $countAttribute = count($requiredAttribute); $re < $countAttribute; $re++)
				{
					$requiredAttributeArray[$re] = urldecode($requiredAttribute[$re]->attribute_name);
				}

				$requiredAttributeName = implode(", ", $requiredAttributeArray);

				// Error message if first attribute is required
				return $requiredAttributeName . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
			}

			$selectedPropertyId = 0;

			if (!empty($selectedPropId))
			{
				$selectedPropertyId = implode(",", $selectedPropId);
			}

			$notselectedSubpropertyId = 0;

			if (count($notselectedSubpropId) > 0)
			{
				$notselectedSubpropertyId = implode(",", $notselectedSubpropId);
			}

			$requiredProperty = RedshopHelperProduct_Attribute::getAttributeProperties(
								$selectedPropertyId,
								$selectedAttributId,
								$data['product_id'],
								0,
								1,
								$notselectedSubpropertyId
							);

			if (!empty($requiredProperty))
			{
				$requiredSubAttributeArray = array();

				for ($re1 = 0, $countProperty = count($requiredProperty); $re1 < $countProperty; $re1++)
				{
					$requiredSubAttributeArray[$re1] = urldecode($requiredProperty[$re1]->property_name);
				}

				$requiredSubAttributeName = implode(",", $requiredSubAttributeArray);

				// Give error as second attribute is required
				if ($data['reorder'] != 1)
				{
					return $requiredSubAttributeName . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');
				}
			}
		}

		return;
	}
}
