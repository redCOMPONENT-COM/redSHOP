<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Shipping.Rate
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer
 *
 * @todo Improve this class follow PSR
 *
 * @since  2.0
 */
class RedshopTagsSectionsOrder_print extends RedshopTagsAbstract implements RedshopTagsInterface
{
	
	/**
	 * @todo  Provide complete tags
	 *
	 * @var array
	 * @since version
	 */
	public $tags = array(
		'order_mail_intro_text_title' => '',
		'order_mail_intro_text' => '',
		'order_information_lbl' => '',
		'order_id_lbl' => '',
		'order_id' => '',
		'order_number_lbl' => '',
		'order_number' => '',
		'order_date_lbl' => '',
		'order_date' => '',
		'order_status_lbl' => '',
		'order_status' => '',
		'billing_address_information_lbl' => '',
		'billing_address' => '',
		'shipping_address_information_lbl' => '',
		'shipping_address' => '',
		'order_detail_lbl' => '',
		'product_name_lbl' => '',
		'product_name' => '',
		'product_s_desc' => '',
		'product_number' => '',
		'note_lbl' => '',
		'product_wrapper' => '',
		'price_lbl' => '',
		'product_price' => '',
		'product_sku' => '',
		'quantity_lbl' => '',
		'product_quantity' => '',
	);
	
	/**
	 * Order object
	 *
	 * @var   object
	 * @since version
	 */
	private $data;
	
	/**
	 * RedshopTagsSectionsOrder_print constructor.
	 *
	 * @param $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
		
		$this->_db = JFactory::getDBO();
		$this->_session = JFactory::getSession();
		$this->_order_functions = order_functions::getInstance();
		$this->_extra_field = extra_field::getInstance();
		$this->_extraFieldFront = extraField::getInstance();
		$this->_redhelper = redhelper::getInstance();
		$this->_producthelper = productHelper::getInstance();
		$this->_shippinghelper = shipping::getInstance();
	}
	
	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function getTags()
	{
		return $this->tags;
	}
	
	/**
	 * Replace all available tags
	 *
	 * @param $content
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function replace($content)
	{
		foreach ($this->tags as $tag => $description) {
			// Replace data properties tags
			if (isset($this->data->$tag)) {
				// Use preg_replace instead
				$content = str_replace('{' . $tag . '}', $this->data->$tag, $content);
			}
		}
		// Replace specific tags
		$content = $this->_general($this->data, $content, true);
		return parent::replace($content);
	}
	
	/**
	 * @param      $row
	 * @param      $ReceiptTemplate
	 * @param bool $sendmail
	 *
	 * @return mixed|string
	 *
	 * @since version
	 */
	protected function _general($row, $ReceiptTemplate, $sendmail = false)
	{
		$url = JURI::base();
		$redconfig = Redconfiguration::getInstance();
		$order_id = $row->order_id;
		$session = JFactory::getSession();
		$orderitem = $this->_order_functions->getOrderItemDetail($order_id);
		
		$search = array();
		$replace = array();
		
		if (strpos($ReceiptTemplate, "{product_loop_start}") !== false && strpos($ReceiptTemplate, "{product_loop_end}") !== false) {
			$template_sdata = explode('{product_loop_start}', $ReceiptTemplate);
			$template_start = $template_sdata[0];
			$template_edata = explode('{product_loop_end}', $template_sdata[1]);
			$template_end = $template_edata[1];
			$template_middle = $template_edata[0];
			$cartArr = $this->replaceOrderItems($template_middle, $orderitem);
			$ReceiptTemplate = $template_start . $cartArr[0] . $template_end;
		}
		
		$orderdetailurl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $order_id . '&encr=' . $row->encr_key;
		
		$downloadProducts = $this->_order_functions->getDownloadProduct($order_id);
		$paymentmethod = $this->_order_functions->getOrderPaymentDetail($order_id);
		$paymentmethod = $paymentmethod[0];
		
		// Initialize Transaction label
		$transactionIdLabel = '';
		
		// Check if transaction Id is set
		if ($paymentmethod->order_payment_trans_id != null) {
			$transactionIdLabel = JText::_('COM_REDSHOP_PAYMENT_TRANSACTION_ID_LABEL');
		}
		
		// Replace Transaction Id and Label
		$ReceiptTemplate = str_replace("{transaction_id_label}", $transactionIdLabel, $ReceiptTemplate);
		$ReceiptTemplate = str_replace("{transaction_id}", $paymentmethod->order_payment_trans_id, $ReceiptTemplate);
		
		// Get Payment Method information
		$paymentmethod_detail = $this->_order_functions->getPaymentMethodInfo($paymentmethod->payment_method_class);
		$paymentmethod_detail = $paymentmethod_detail [0];
		$OrderStatus = $this->_order_functions->getOrderStatusTitle($row->order_status);
		
		$product_name = "";
		$product_price = "";
		$subtotal_excl_vat = $cartArr[1];
		$barcode_code = $row->barcode;
		$img_url = REDSHOP_FRONT_IMAGES_ABSPATH . "barcode/" . $barcode_code . ".png";
		$bar_replace = '<img alt="" src="' . $img_url . '">';
		
		$total_excl_vat = $subtotal_excl_vat + ($row->order_shipping - $row->order_shipping_tax) - ($row->order_discount - $row->order_discount_vat);
		$sub_total_vat = $row->order_tax + $row->order_shipping_tax;
		
		if (isset($row->voucher_discount) === false) {
			$row->voucher_discount = 0;
		}
		
		$Total_discount = $row->coupon_discount + $row->order_discount + $row->special_discount + $row->tax_after_discount + $row->voucher_discount;
		
		// For Payment and Shipping Extra Fields
		if (strpos($ReceiptTemplate, '{payment_extrafields}') !== false) {
			$PaymentExtrafields = $this->_producthelper->getPaymentandShippingExtrafields($row, 18);
			
			if ($PaymentExtrafields == "") {
				$ReceiptTemplate = str_replace("{payment_extrafields_lbl}", "", $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{payment_extrafields}", "", $ReceiptTemplate);
			} else {
				$ReceiptTemplate = str_replace("{payment_extrafields_lbl}", JText::_("COM_REDSHOP_ORDER_PAYMENT_EXTRA_FILEDS"), $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{payment_extrafields}", $PaymentExtrafields, $ReceiptTemplate);
			}
		}
		
		if (strpos($ReceiptTemplate, '{shipping_extrafields}') !== false) {
			$ShippingExtrafields = $this->_producthelper->getPaymentandShippingExtrafields($row, 19);
			
			if ($ShippingExtrafields == "") {
				$ReceiptTemplate = str_replace("{shipping_extrafields_lbl}", "", $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{shipping_extrafields}", "", $ReceiptTemplate);
			} else {
				$ReceiptTemplate = str_replace("{shipping_extrafields_lbl}", JText::_("COM_REDSHOP_ORDER_SHIPPING_EXTRA_FILEDS"), $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{shipping_extrafields}", $ShippingExtrafields, $ReceiptTemplate);
			}
		}
		
		// End
		$ReceiptTemplate = $this->replaceShippingMethod($row, $ReceiptTemplate);
		
		if (!APPLY_VAT_ON_DISCOUNT) {
			$total_for_discount = $subtotal_excl_vat;
		} else {
			$total_for_discount = $row->order_subtotal;
		}
		
		$ReceiptTemplate = $this->replaceLabel($ReceiptTemplate);
		$search[] = "{order_subtotal}";
		$chktag = $this->_producthelper->getApplyVatOrNot($ReceiptTemplate);
		
		if (!empty($chktag)) {
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_total);
		} else {
			$replace[] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		}
		
		$search[] = "{subtotal_excl_vat}";
		$replace[] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search[] = "{product_subtotal}";
		
		if (!empty($chktag)) {
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_subtotal);
		} else {
			$replace[] = $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat);
		}
		
		$search[] = "{product_subtotal_excl_vat}";
		$replace[] = $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat);
		$search[] = "{order_subtotal_excl_vat}";
		$replace[] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search[] = "{order_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		$search[] = "{order_number}";
		$replace[] = $row->order_number;
		$search  [] = "{special_discount}";
		$replace [] = $row->special_discount . '%';
		$search  [] = "{special_discount_amount}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->special_discount_amount);
		$search[] = "{special_discount_lbl}";
		$replace[] = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');
		
		$search[] = "{order_detail_link}";
		$replace[] = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";
		
		$dpData = "";
		
		if (count($downloadProducts) > 0) {
			$dpData .= "<table>";
			
			for ($d = 0, $dn = count($downloadProducts); $d < $dn; $d++) {
				$g = $d + 1;
				$downloadProduct = $downloadProducts[$d];
				$downloadfilename = substr(basename($downloadProduct->file_name), 11);
				$downloadToken = $downloadProduct->download_id;
				$product_name = $downloadProduct->product_name;
				$mailtoken = $product_name . ": <a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadToken . "'>" . $downloadfilename . "</a>";
				
				$dpData .= "</tr>";
				$dpData .= "<td>(" . $g . ") " . $mailtoken . "</td>";
				$dpData .= "</tr>";
			}
			
			$dpData .= "</table>";
		}
		
		if ($row->order_status == "C" && $row->order_payment_status == "Paid") {
			$search  [] = "{download_token}";
			$replace [] = $dpData;
			
			$search  [] = "{download_token_lbl}";
			
			if ($dpData != "") {
				$replace [] = JText::_('COM_REDSHOP_DOWNLOAD_TOKEN');
			} else {
				$replace [] = "";
			}
		} else {
			$search  [] = "{download_token}";
			$replace [] = "";
			$search  [] = "{download_token_lbl}";
			$replace [] = "";
		}
		
		$issplitdisplay = "";
		$issplitdisplay2 = "";
		
		if ((strpos($ReceiptTemplate, "{discount_denotation}") !== false || strpos($ReceiptTemplate, "{shipping_denotation}") !== false) && ($Total_discount != 0 || $row->order_shipping != 0)) {
			$search  [] = "{denotation_label}";
			$replace [] = JText::_('COM_REDSHOP_DENOTATION_TXT');
		} else {
			$search  [] = "{denotation_label}";
			$replace [] = "";
			
		}
		
		$search  [] = "{discount_denotation}";
		
		if (strpos($ReceiptTemplate, "{discount_excl_vat}") !== false) {
			$replace [] = "*";
		} else {
			$replace [] = "";
		}
		
		$search  [] = "{shipping_denotation}";
		
		if (strpos($ReceiptTemplate, "{shipping_excl_vat}") !== false) {
			$replace [] = "*";
		} else {
			$replace [] = "";
		}
		
		$search[] = "{payment_status}";
		
		if (trim($row->order_payment_status) == 'Paid') {
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
		} elseif (trim($row->order_payment_status) == 'Unpaid') {
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');
		} elseif (trim($row->order_payment_status) == 'Partial Paid') {
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID');
		} else {
			$orderPaymentStatus = $row->order_payment_status;
		}
		
		$replace[] = $orderPaymentStatus . " " . JRequest::getVar('order_payment_log') . $issplitdisplay . $issplitdisplay2;
		$search[] = "{order_payment_status}";
		$replace[] = $orderPaymentStatus . " " . JRequest::getVar('order_payment_log') . $issplitdisplay . $issplitdisplay2;
		
		$search  [] = "{order_total}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->order_total);
		$search  [] = "{total_excl_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search  [] = "{sub_total_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($sub_total_vat);
		$search  [] = "{order_id}";
		$replace [] = $order_id;
		$search  [] = "{discount_denotation}";
		$replace [] = "*";
		
		$arr_discount_type = array();
		$arr_discount = explode('@', $row->discount_type);
		$discount_type = '';
		
		for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++) {
			if ($arr_discount[$d]) {
				$arr_discount_type = explode(':', $arr_discount[$d]);
				
				if ($arr_discount_type[0] == 'c') {
					$discount_type .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}
				
				if ($arr_discount_type[0] == 'v') {
					$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}
			}
		}
		
		$search[] = "{discount_type}";
		$replace[] = $discount_type;
		
		$search  [] = "{discount_excl_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->order_discount - $row->order_discount_vat);
		$search  [] = "{order_status}";
		$replace [] = $OrderStatus;
		$search  [] = "{order_id_lbl}";
		$replace [] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		$search  [] = "{order_date}";
		$replace [] = $redconfig->convertDateFormat($row->cdate);
		$search  [] = "{customer_note}";
		$replace [] = $row->customer_note;
		$search  [] = "{customer_message}";
		$replace [] = $row->customer_message;
		$search  [] = "{referral_code}";
		$replace [] = $row->referral_code;
		
		$search  [] = "{payment_method}";
		$replace [] = JText::_($paymentmethod->order_payment_name);
		
		$txtextra_info = '';
		
		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentmethod_detail->element);
		
		if ($isBankTransferPaymentType) {
			$paymentpath = JPATH_SITE . '/plugins/redshop_payment/'
				. $paymentmethod_detail->element . '/' . $paymentmethod_detail->element . '.xml';
			$paymentparams = new JRegistry($paymentmethod_detail->params);
			$txtextra_info = $paymentparams->get('txtextra_info', '');
		}
		
		$search  [] = "{payment_extrainfo}";
		$replace [] = $txtextra_info;
		
		// Set order transaction fee tag
		$orderTransFeeLabel = '';
		$orderTransFee = '';
		
		if ($paymentmethod->order_transfee > 0) {
			$orderTransFeeLabel = JText::_('COM_REDSHOP_ORDER_TRANSACTION_FEE_LABEL');
			$orderTransFee = $this->_producthelper->getProductFormattedPrice($paymentmethod->order_transfee);
		}
		
		$search [] = "{order_transfee_label}";
		$replace[] = $orderTransFeeLabel;
		
		$search [] = "{order_transfee}";
		$replace[] = $orderTransFee;
		
		$search [] = "{order_total_incl_transfee}";
		$replace[] = $this->_producthelper->getProductFormattedPrice(
			$paymentmethod->order_transfee + $row->order_total
		);
		
		if (JRequest::getVar('order_delivery')) {
			$search  [] = "{delivery_time_lbl}";
			$replace [] = JText::_('COM_REDSHOP_DELIVERY_TIME');
		} else {
			$search  [] = "{delivery_time_lbl}";
			$replace [] = " ";
		}
		
		$search  [] = "{delivery_time}";
		$replace [] = JRequest::getVar('order_delivery');
		$search  [] = "{without_vat}";
		$replace [] = '';
		$search  [] = "{with_vat}";
		$replace [] = '';
		
		if (strpos($ReceiptTemplate, '{order_detail_link_lbl}') !== false) {
			$search [] = "{order_detail_link_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LINK_LBL');
		}
		
		if (strpos($ReceiptTemplate, '{product_subtotal_lbl}') !== false) {
			$search [] = "{product_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL');
		}
		
		if (strpos($ReceiptTemplate, '{product_subtotal_excl_vat_lbl}') !== false) {
			$search [] = "{product_subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL');
		}
		
		if (strpos($ReceiptTemplate, '{shipping_with_vat_lbl}') !== false) {
			$search [] = "{shipping_with_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL');
		}
		
		if (strpos($ReceiptTemplate, '{shipping_excl_vat_lbl}') !== false) {
			$search [] = "{shipping_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL');
		}
		
		if (strpos($ReceiptTemplate, '{product_price_excl_lbl}') !== false) {
			$search [] = "{product_price_excl_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL');
		}
		
		$billingaddresses = RedshopHelperOrder::getOrderBillingUserInfo($order_id);
		$shippingaddresses = RedshopHelperOrder::getOrderShippingUserInfo($order_id);
		
		$search [] = "{requisition_number}";
		$replace[] = ($row->requisition_number) ? $row->requisition_number : "N/A";
		
		$search [] = "{requisition_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');
		
		$ReceiptTemplate = $this->replaceBillingAddress($ReceiptTemplate, $billingaddresses, $sendmail);
		$ReceiptTemplate = $this->replaceShippingAddress($ReceiptTemplate, $shippingaddresses, $sendmail);
		
		$message = str_replace($search, $replace, $ReceiptTemplate);
		$message = $this->replacePayment($message, $row->payment_discount, 0, $row->payment_oprand);
		$message = $this->replaceDiscount($message, $row->order_discount, $total_for_discount);
		$message = $this->replaceTax($message, $row->order_tax + $row->order_shipping_tax, $row->tax_after_discount, 1);
		
		return $message;
	}
	
	/**
	 * @param       $data
	 * @param array $rowitem
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function replaceOrderItems($data, $rowitem = array())
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = JDispatcher::getInstance();
		$mainview = JRequest::getVar('view');
		$fieldArray = $this->_extraFieldFront->getSectionFieldList(17, 0, 0);
		
		$subtotal_excl_vat = 0;
		$cart = '';
		$url = JURI::root();
		$returnArr = array();
		
		$wrapper_name = "";
		
		$OrdersDetail = $this->_order_functions->getOrderDetails($rowitem [0]->order_id);
		
		for ($i = 0, $in = count($rowitem); $i < $in; $i++) {
			$product_id = $rowitem [$i]->product_id;
			$quantity = $rowitem [$i]->product_quantity;
			
			if ($rowitem [$i]->is_giftcard) {
				$giftcardData = $this->_producthelper->getGiftcardData($product_id);
				$product_name = $giftcardData->giftcard_name;
				$userfield_section = 13;
				$product = new stdClass;
			} else {
				$product = $this->_producthelper->getProductById($product_id);
				$product_name = $rowitem[$i]->order_item_name;
				$userfield_section = 12;
				$giftcardData = new stdClass;
			}
			
			$dirname = JPATH_COMPONENT_SITE . "/assets/images/orderMergeImages/" . $rowitem [$i]->attribute_image;
			
			if (is_file($dirname)) {
				$attribute_image_path = RedShopHelperImages::getImagePath(
					$rowitem[$i]->attribute_image,
					'',
					'thumb',
					'orderMergeImages',
					CART_THUMB_WIDTH,
					CART_THUMB_HEIGHT,
					USE_IMAGE_SIZE_SWAPPING
				);
				$attrib_img = '<img src="' . $attribute_image_path . '">';
			} else {
				if (is_file(JPATH_COMPONENT_SITE . "/assets/images/product_attributes/" . $rowitem [$i]->attribute_image)) {
					$attribute_image_path = RedShopHelperImages::getImagePath(
						$rowitem[$i]->attribute_image,
						'',
						'thumb',
						'product_attributes',
						CART_THUMB_WIDTH,
						CART_THUMB_HEIGHT,
						USE_IMAGE_SIZE_SWAPPING
					);
					$attrib_img = '<img src="' . $attribute_image_path . '">';
				} else {
					if ($rowitem [$i]->is_giftcard) {
						$product_full_image = $giftcardData->giftcard_image;
						$product_type = 'giftcard';
					} else {
						$product_full_image = $product->product_full_image;
						$product_type = 'product';
					}
					
					if ($product_full_image) {
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_type . "/" . $product_full_image)) {
							$attribute_image_path = RedShopHelperImages::getImagePath(
								$product_full_image,
								'',
								'thumb',
								$product_type,
								CART_THUMB_WIDTH,
								CART_THUMB_HEIGHT,
								USE_IMAGE_SIZE_SWAPPING
							);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						} else {
							if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE)) {
								$attribute_image_path = RedShopHelperImages::getImagePath(
									PRODUCT_DEFAULT_IMAGE,
									'',
									'thumb',
									'product',
									CART_THUMB_WIDTH,
									CART_THUMB_HEIGHT,
									USE_IMAGE_SIZE_SWAPPING
								);
								$attrib_img = '<img src="' . $attribute_image_path . '">';
							}
						}
					} else {
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE)) {
							$attribute_image_path = RedShopHelperImages::getImagePath(
								PRODUCT_DEFAULT_IMAGE,
								'',
								'thumb',
								'product',
								CART_THUMB_WIDTH,
								CART_THUMB_HEIGHT,
								USE_IMAGE_SIZE_SWAPPING
							);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						}
					}
				}
			}
			
			$product_name = "<div class='product_name'>" . $product_name . "</div>";
			$product_total_price = "<div class='product_price'>";
			
			if (!$this->_producthelper->getApplyVatOrNot($data)) {
				$product_total_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity);
			} else {
				$product_total_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price * $quantity);
			}
			
			$product_total_price .= "</div>";
			
			$product_price = "<div class='product_price'>";
			
			if (!$this->_producthelper->getApplyVatOrNot($data)) {
				$product_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat);
			} else {
				$product_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price);
			}
			
			$product_price .= "</div>";
			
			$product_old_price = $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_old_price);
			
			$product_quantity = '<div class="update_cart">' . $quantity . '</div>';
			
			if ($rowitem [$i]->wrapper_id) {
				$wrapper = $this->_producthelper->getWrapper($product_id, $rowitem [$i]->wrapper_id);
				
				if (count($wrapper) > 0) {
					$wrapper_name = $wrapper [0]->wrapper_name;
				}
				
				$wrapper_price = $this->_producthelper->getProductFormattedPrice($rowitem [$i]->wrapper_price);
				$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper_name . "(" . $wrapper_price . ")";
			}
			
			$cart_mdata = str_replace("{product_name}", $product_name, $data);
			
			$catId = $this->_producthelper->getCategoryProduct($product_id);
			$res = $this->_producthelper->getSection("category", $catId);
			
			if (count($res) > 0) {
				$cname = $res->category_name;
				$clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);
				$category_path = "<a href='" . $clink . "'>" . $cname . "</a>";
			} else {
				$category_path = '';
			}
			
			$cart_mdata = str_replace("{category_name}", $category_path, $cart_mdata);
			
			$cart_mdata = $this->_producthelper->replaceVatinfo($cart_mdata);
			
			$product_note = "<div class='product_note'>" . $wrapper_name . "</div>";
			
			$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);
			
			// Make attribute order template output
			$attribute_data = $this->_producthelper->makeAttributeOrder($rowitem[$i]->order_item_id, 0, $product_id, 0, 0, $data);
			
			// Assign template output into {product_attribute} tag
			$cart_mdata = str_replace("{product_attribute}", $attribute_data->product_attribute, $cart_mdata);
			
			// Assign template output into {attribute_middle_template} tag
			$cart_mdata = str_replace($attribute_data->attribute_middle_template_core, $attribute_data->attribute_middle_template, $cart_mdata);
			
			if (strpos($cart_mdata, '{remove_product_attribute_title}') !== false) {
				$cart_mdata = str_replace("{remove_product_attribute_title}", "", $cart_mdata);
			}
			
			if (strpos($cart_mdata, '{remove_product_subattribute_title}') !== false) {
				$cart_mdata = str_replace("{remove_product_subattribute_title}", "", $cart_mdata);
			}
			
			if (strpos($cart_mdata, '{product_attribute_number}') !== false) {
				$cart_mdata = str_replace("{product_attribute_number}", "", $cart_mdata);
			}
			
			$cart_mdata = str_replace("{product_accessory}", $this->_producthelper->makeAccessoryOrder($rowitem [$i]->order_item_id), $cart_mdata);
			
			$productUserFields = $this->_producthelper->getuserfield($rowitem [$i]->order_item_id, $userfield_section);
			
			$cart_mdata = str_replace("{product_userfields}", $productUserFields, $cart_mdata);
			
			$user_custom_fields = $this->_producthelper->GetProdcutfield_order($rowitem [$i]->order_item_id);
			$cart_mdata = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
			$cart_mdata = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);
			
			if ($rowitem [$i]->is_giftcard) {
				$cart_mdata = str_replace(
					array(
						'{product_sku}',
						'{product_number}',
						'{product_s_desc}',
						'{product_subscription}',
						'{product_subscription_lbl}'
					),
					'', $cart_mdata);
			} else {
				$cart_mdata = str_replace("{product_sku}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_number}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);
				
				if ($product->product_type == 'subscription') {
					$user_subscribe_detail = $this->_producthelper->getUserProductSubscriptionDetail($rowitem[$i]->order_item_id);
					$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product->product_id, $user_subscribe_detail->subscription_id);
					$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;
					
					$cart_mdata = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
				} else {
					$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
				}
			}
			
			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);
			
			$product_vat = ($rowitem [$i]->product_item_price - $rowitem [$i]->product_item_price_excl_vat) * $rowitem [$i]->product_quantity;
			
			$cart_mdata = str_replace("{product_vat}", $product_vat, $cart_mdata);
			
			$cart_mdata = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata);
			
			$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
			
			$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);
			
			// ProductFinderDatepicker Extra Field Start
			$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);
			
			// Change order item image based on plugin
			$prepareCartAttributes[$i] = get_object_vars($attribute_data);
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
				"<div  class='product_image'>" . $attrib_img . "</div>",
				$cart_mdata
			);
			
			$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
			
			$cart_mdata = str_replace("{product_old_price}", $product_old_price, $cart_mdata);
			
			$cart_mdata = str_replace("{product_quantity}", $quantity, $cart_mdata);
			
			$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);
			
			$cart_mdata = str_replace("{product_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat), $cart_mdata);
			
			$cart_mdata = str_replace("{product_total_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity), $cart_mdata);
			
			$subtotal_excl_vat += $rowitem [$i]->product_item_price_excl_vat * $quantity;
			
			if ($mainview == "order_detail") {
				$Itemid = JRequest::getVar('Itemid');
				$Itemid = $this->_redhelper->getCartItemid();
				$copytocart = "<a href='" . JRoute::_('index.php?option=com_redshop&view=order_detail&task=copyorderitemtocart&order_item_id=' . $rowitem[$i]->order_item_id . '&Itemid=' . $Itemid, false) . "'>";
				$copytocart .= "<img src='" . REDSHOP_ADMIN_IMAGES_ABSPATH . "add.jpg' title='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' alt='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' /></a>";
				$cart_mdata = str_replace("{copy_orderitem}", $copytocart, $cart_mdata);
			} else {
				$cart_mdata = str_replace("{copy_orderitem}", "", $cart_mdata);
			}
			
			// Get Downloadable Products
			$downloadProducts = $this->_order_functions->getDownloadProduct($rowitem[$i]->order_id);
			$totalDownloadProduct = count($downloadProducts);
			
			$dproducts = array();
			
			for ($t = 0; $t < $totalDownloadProduct; $t++) {
				$downloadProduct = $downloadProducts[$t];
				$dproducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
			}
			
			// Get Downloadable Products Logs
			$downloadProductslog = $this->_order_functions->getDownloadProductLog($rowitem[$i]->order_id);
			$totalDownloadProductlog = count($downloadProductslog);
			
			$dproductslog = array();
			
			for ($t = 0; $t < $totalDownloadProductlog; $t++) {
				$downloadProductlogs = $downloadProductslog[$t];
				$dproductslog[$downloadProductlogs->product_id][] = $downloadProductlogs;
			}
			
			// Download Product Tag Replace
			if (isset($dproducts[$product_id]) && count($dproducts[$product_id]) > 0 && $OrdersDetail->order_status == "C" && $OrdersDetail->order_payment_status == "Paid") {
				$downloadarray = $dproducts[$product_id];
				$dpData = "<table class='download_token'>";
				$limit = $dpData;
				$enddate = $dpData;
				$g = 1;
				
				foreach ($downloadarray as $downloads) {
					$file_name = substr(basename($downloads->file_name), 11);
					$product_name = $downloadProduct->product_name;
					$download_id = $downloads->download_id;
					$download_max = $downloads->download_max;
					$end_date = $downloads->end_date;
					$mailtoken = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "'>" . $file_name . "</a>";
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
			} else {
				$cart_mdata = str_replace("{download_token_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_token}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date}", "", $cart_mdata);
			}
			
			// Download Product log Tags Replace
			if (isset($dproductslog[$product_id]) && count($dproductslog[$product_id]) > 0 && $OrdersDetail->order_status == "C") {
				$downloadarraylog = $dproductslog[$product_id];
				$dpData = "<table class='download_token'>";
				$g = 1;
				
				foreach ($downloadarraylog as $downloads) {
					$file_name = substr(basename($downloads->file_name), 11);
					
					$download_id = $downloads->download_id;
					$download_time = $downloads->download_time;
					$download_date = date("d-m-Y H:i:s", $download_time);
					$ip = $downloads->ip;
					
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
			} else {
				$cart_mdata = str_replace("{download_date_list_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_list}", "", $cart_mdata);
			}
			
			// Process the product plugin for cart item
			$dispatcher->trigger('onOrderItemDisplay', array(& $cart_mdata, &$rowitem, $i));
			
			$cart .= $cart_mdata;
		}
		
		$returnArr[0] = $cart;
		$returnArr[1] = $subtotal_excl_vat;
		
		return $returnArr;
	}
	
	/**
	 * Replace shipping method
	 *
	 * @param array $row
	 * @param string $data
	 *
	 * @return mixed
	 */
	public function replaceShippingMethod($row = array(), $data = "")
	{
		$search = array();
		$search[] = "{shipping_method}";
		$search[] = "{order_shipping}";
		$search[] = "{shipping_excl_vat}";
		$search[] = "{shipping_rate_name}";
		$search[] = "{shipping}";
		$search[] = "{vat_shipping}";
		$search[] = "{order_shipping_shop_location}";
		
		if (SHIPPING_METHOD_ENABLE) {
			$details = RedshopShippingRate::decrypt($row->ship_method_id);
			
			if (count($details) <= 1) {
				$details = explode("|", $row->ship_method_id);
			}
			
			$shipping_method = "";
			$shipping_rate_name = "";
			
			if (count($details) > 0) {
				// Load language file of the shipping plugin
				JFactory::getLanguage()->load(
					'plg_redshop_shipping_' . strtolower(str_replace('plgredshop_shipping', '', $details[0])),
					JPATH_ADMINISTRATOR
				);
				
				if (array_key_exists(1, $details)) {
					$shipping_method = $details[1];
				}
				
				if (array_key_exists(2, $details)) {
					$shipping_rate_name = $details[2];
				}
			}
			
			$shopLocation = $row->shop_id;
			$replace = array();
			$replace[] = JText::_($shipping_method);
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_shipping - $row->order_shipping_tax);
			$replace[] = $shipping_rate_name;
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_shipping_tax);
			
			if ($details[0] != 'plgredshop_shippingdefault_shipping_gls') {
				$shopLocation = '';
			}
			
			$mobilearr = array();
			
			if ($shopLocation) {
				$mobilearr = explode('###', $shopLocation);
				$arrLocationDetails = explode('|', $shopLocation);
				$countLocDet = count($arrLocationDetails);
				$shopLocation = '';
				
				if ($countLocDet > 1) {
					$shopLocation .= '<b>' . $arrLocationDetails[0] . ' ' . $arrLocationDetails[1] . '</b>';
				}
				
				if ($countLocDet > 2) {
					$shopLocation .= '<br>' . $arrLocationDetails[2];
				}
				
				if ($countLocDet > 3) {
					$shopLocation .= '<br>' . $arrLocationDetails[3];
				}
				
				if ($countLocDet > 4) {
					$shopLocation .= ' ' . $arrLocationDetails[4];
				}
				
				if ($countLocDet > 5) {
					$shopLocation .= '<br>' . $arrLocationDetails[5];
				}
				
				if ($countLocDet > 6) {
					$arrLocationTime = explode('  ', $arrLocationDetails[6]);
					$shopLocation .= '<br>';
					
					for ($t = 0, $tn = count($arrLocationTime); $t < $tn; $t++) {
						$shopLocation .= $arrLocationTime[$t] . '<br>';
					}
				}
			}
			
			if (isset($mobilearr[1])) {
				$shopLocation .= ' ' . $mobilearr[1];
			}
			
			$replace[] = $shopLocation;
			$data = str_replace($search, $replace, $data);
		} else {
			$data = str_replace($search, array("", "", "", ""), $data);
		}
		
		return $data;
	}
	
	/**
	 * @param $data
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function replaceLabel($data)
	{
		$search = array();
		$replace = array();
		
		if (strpos($data, '{cart_lbl}') !== false) {
			$search[] = "{cart_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_LBL');
		}
		
		if (strpos($data, '{copy_orderitem_lbl}') !== false) {
			$search[] = "{copy_orderitem_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COPY_ORDERITEM_LBL');
		}
		
		if (strpos($data, '{totalpurchase_lbl}') !== false) {
			$search[] = "{totalpurchase_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_TOTAL_PURCHASE_TBL');
		}
		
		if (strpos($data, '{subtotal_excl_vat_lbl}') !== false) {
			$search[] = "{subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SUBTOTAL_EXCL_VAT_LBL');
		}
		
		if (strpos($data, '{product_name_lbl}') !== false) {
			$search[] = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}
		
		if (strpos($data, '{price_lbl}') !== false) {
			$search[] = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}
		
		if (strpos($data, '{quantity_lbl}') !== false) {
			$search[] = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}
		
		if (strpos($data, '{total_price_lbl}') !== false) {
			$search[] = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}
		
		if (strpos($data, '{total_price_exe_lbl}') !== false) {
			$search[] = "{total_price_exe_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_EXEL_LBL');
		}
		
		if (strpos($data, '{order_id_lbl}') !== false) {
			$search[] = "{order_id_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		}
		
		if (strpos($data, '{order_number_lbl}') !== false) {
			$search[] = "{order_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		}
		
		if (strpos($data, '{order_date_lbl}') !== false) {
			$search[] = "{order_date_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DATE_LBL');
		}
		
		if (strpos($data, '{requisition_number_lbl}') !== false) {
			$search[] = "{requisition_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');
		}
		
		if (strpos($data, '{order_status_lbl}') !== false) {
			$search[] = "{order_status_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}
		
		if (strpos($data, '{order_status_order_only_lbl}') !== false) {
			$search[] = "{order_status_order_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}
		
		if (strpos($data, '{order_status_payment_only_lbl}') !== false) {
			$search[] = "{order_status_payment_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL');
		}
		
		if (SHIPPING_METHOD_ENABLE) {
			if (strpos($data, '{shipping_lbl}') !== false) {
				$search[] = "{shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}
			
			if (strpos($data, '{tax_with_shipping_lbl}') !== false) {
				$search[] = "{tax_with_shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}
		} else {
			if (strpos($data, '{shipping_lbl}') !== false) {
				$search[] = "{shipping_lbl}";
				$replace[] = "";
			}
			
			if (strpos($data, '{tax_with_shipping_lbl}') !== false) {
				$search[] = "{tax_with_shipping_lbl}";
				$replace[] = "";
			}
		}
		
		if (strpos($data, '{order_information_lbl}') !== false) {
			$search[] = "{order_information_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_INFORMATION_LBL');
		}
		
		if (strpos($data, '{order_detail_lbl}') !== false) {
			$search[] = "{order_detail_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');
		}
		
		if (strpos($data, '{product_name_lbl}') !== false) {
			$search[] = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}
		
		if (strpos($data, '{note_lbl}') !== false) {
			$search[] = "{note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_NOTE_LBL');
		}
		
		if (strpos($data, '{price_lbl}') !== false) {
			$search[] = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}
		
		if (strpos($data, '{quantity_lbl}') !== false) {
			$search[] = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}
		
		if (strpos($data, '{total_price_lbl}') !== false) {
			$search[] = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}
		
		if (strpos($data, '{order_subtotal_lbl}') !== false) {
			$search[] = "{order_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_SUBTOTAL_LBL');
		}
		
		if (strpos($data, '{total_lbl}') !== false) {
			$search[] = "{total_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_LBL');
		}
		
		if (strpos($data, '{discount_type_lbl}') !== false) {
			$search[] = "{discount_type_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');
		}
		
		if (strpos($data, '{payment_lbl}') !== false) {
			$search[] = "{payment_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		}
		
		if (strpos($data, '{customer_note_lbl}') !== false) {
			$search [] = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL');
		}
		
		if (SHIPPING_METHOD_ENABLE) {
			if (strpos($data, '{shipping_method_lbl}') !== false) {
				$search[] = "{shipping_method_lbl}";
				$replace[] = JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');
			}
		} else {
			if (strpos($data, '{shipping_method_lbl}') !== false) {
				$search[] = "{shipping_method_lbl}";
				$replace[] = '';
			}
		}
		
		if (strpos($data, '{product_number_lbl}') !== false) {
			$search[] = "{product_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NUMBER');
		}
		
		if (strpos($data, '{shopname}') !== false) {
			$search [] = "{shopname}";
			$replace [] = SHOP_NAME;
		}
		
		if (strpos($data, '{quotation_id_lbl}') !== false) {
			$search [] = "{quotation_id_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_ID');
		}
		
		if (strpos($data, '{quotation_number_lbl}') !== false) {
			$search [] = "{quotation_number_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NUMBER');
		}
		
		if (strpos($data, '{quotation_date_lbl}') !== false) {
			$search [] = "{quotation_date_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DATE');
		}
		
		if (strpos($data, '{quotation_status_lbl}') !== false) {
			$search [] = "{quotation_status_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_STATUS');
		}
		
		if (strpos($data, '{quotation_note_lbl}') !== false) {
			$search [] = "{quotation_note_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NOTE');
		}
		
		if (strpos($data, '{quotation_information_lbl}') !== false) {
			$search [] = "{quotation_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');
		}
		
		if (strpos($data, '{account_information_lbl}') !== false) {
			$search [] = "{account_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');
		}
		
		if (strpos($data, '{quotation_detail_lbl}') !== false) {
			$search [] = "{quotation_detail_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DETAILS');
		}
		
		if (strpos($data, '{quotation_subtotal_lbl}') !== false) {
			$search [] = "{quotation_subtotal_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
		}
		
		if (strpos($data, '{quotation_discount_lbl}') !== false) {
			$search [] = "{quotation_discount_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');
		}
		
		if (strpos($data, '{thirdparty_email_lbl}') !== false) {
			$search [] = "{thirdparty_email_lbl}";
			$replace[] = JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL');
		}
		
		$data = str_replace($search, $replace, $data);
		
		return $data;
	}
	
	/**
	 * replace Billing Address
	 *
	 * @param $data
	 * @param $billingaddresses
	 *
	 * @return mixed
	 */
	public function replaceBillingAddress($data, $billingaddresses, $sendmail = false)
	{
		if (strpos($data, '{billing_address_start}') !== false && strpos($data, '{billing_address_end}') !== false) {
			$user = JFactory::getUser();
			$template_sdata = explode('{billing_address_start}', $data);
			$template_edata = explode('{billing_address_end}', $template_sdata[1]);
			$billingdata = $template_edata[0];
			
			$billing_extrafield = '';
			
			if (isset($billingaddresses)) {
				$extra_section = ($billingaddresses->is_company == 1) ? 8 : 7;
				
				if ($billingaddresses->is_company == 1 && $billingaddresses->company_name != "") {
					$billingdata = str_replace("{companyname}", $billingaddresses->company_name, $billingdata);
					$billingdata = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $billingdata);
				}
				
				if ($billingaddresses->firstname != "") {
					$billingdata = str_replace("{firstname}", $billingaddresses->firstname, $billingdata);
					$billingdata = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $billingdata);
				}
				
				if ($billingaddresses->lastname != "") {
					$billingdata = str_replace("{lastname}", $billingaddresses->lastname, $billingdata);
					$billingdata = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $billingdata);
				}
				
				if ($billingaddresses->address != "") {
					$billingdata = str_replace("{address}", $billingaddresses->address, $billingdata);
					$billingdata = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $billingdata);
				}
				
				if ($billingaddresses->zipcode != "") {
					$billingdata = str_replace("{zip}", $billingaddresses->zipcode, $billingdata);
					$billingdata = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $billingdata);
				}
				
				if ($billingaddresses->city != "") {
					$billingdata = str_replace("{city}", $billingaddresses->city, $billingdata);
					$billingdata = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $billingdata);
				}
				
				$cname = $this->_order_functions->getCountryName($billingaddresses->country_code);
				
				if ($cname != "") {
					$billingdata = str_replace("{country}", JText::_($cname), $billingdata);
					$billingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $billingdata);
				}
				
				$sname = $this->_order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code);
				
				if ($sname != "") {
					$billingdata = str_replace("{state}", $sname, $billingdata);
					$billingdata = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $billingdata);
				}
				
				if ($billingaddresses->phone != "") {
					$billingdata = str_replace("{phone}", $billingaddresses->phone, $billingdata);
					$billingdata = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $billingdata);
				}
				
				if ($billingaddresses->user_email != "") {
					$billingdata = str_replace("{email}", $billingaddresses->user_email, $billingdata);
					$billingdata = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $billingdata);
				} elseif ($user->email != '') {
					$billingdata = str_replace("{email}", $billingaddresses->email, $billingdata);
					$billingdata = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $billingdata);
				}
				
				if ($billingaddresses->is_company == 1) {
					if ($billingaddresses->vat_number != "") {
						$billingdata = str_replace("{vatnumber}", $billingaddresses->vat_number, $billingdata);
						$billingdata = str_replace("{vatnumber_lbl}", JText::_('COM_REDSHOP_VAT_NUMBER'), $billingdata);
					}
					
					if ($billingaddresses->ean_number != "") {
						$billingdata = str_replace("{ean_number}", $billingaddresses->ean_number, $billingdata);
						$billingdata = str_replace("{ean_number_lbl}", JText::_('COM_REDSHOP_EAN_NUMBER'), $billingdata);
					}
					
					if (SHOW_TAX_EXEMPT_INFRONT) {
						if ($billingaddresses->tax_exempt == 1) {
							$taxexe = JText::_("COM_REDSHOP_TAX_YES");
						} else {
							$taxexe = JText::_("COM_REDSHOP_TAX_NO");
						}
						
						$billingdata = str_replace("{taxexempt}", $taxexe, $billingdata);
						$billingdata = str_replace("{taxexempt_lbl}", JText::_('COM_REDSHOP_TAX_EXEMPT'), $billingdata);
						
						if ($billingaddresses->requesting_tax_exempt == 1) {
							$taxexereq = JText::_("COM_REDSHOP_YES");
						} else {
							$taxexereq = JText::_("COM_REDSHOP_NO");
						}
						
						$billingdata = str_replace("{user_taxexempt_request}", $taxexereq, $billingdata);
						$billingdata = str_replace("{user_taxexempt_request_lbl}", JText::_('COM_REDSHOP_USER_TAX_EXEMPT_REQUEST_LBL'), $billingdata);
					}
				}
				
				$billing_extrafield = $this->_extra_field->list_all_field_display($extra_section, $billingaddresses->users_info_id, 1);
			}
			
			$billingdata = str_replace("{companyname}", "", $billingdata);
			$billingdata = str_replace("{companyname_lbl}", "", $billingdata);
			$billingdata = str_replace("{firstname}", "", $billingdata);
			$billingdata = str_replace("{firstname_lbl}", "", $billingdata);
			$billingdata = str_replace("{lastname}", "", $billingdata);
			$billingdata = str_replace("{lastname_lbl}", "", $billingdata);
			$billingdata = str_replace("{address}", "", $billingdata);
			$billingdata = str_replace("{address_lbl}", "", $billingdata);
			$billingdata = str_replace("{zip}", "", $billingdata);
			$billingdata = str_replace("{zip_lbl}", "", $billingdata);
			$billingdata = str_replace("{city}", "", $billingdata);
			$billingdata = str_replace("{city_lbl}", "", $billingdata);
			$billingdata = str_replace("{country}", "", $billingdata);
			$billingdata = str_replace("{country_lbl}", "", $billingdata);
			$billingdata = str_replace("{state}", "", $billingdata);
			$billingdata = str_replace("{state_lbl}", "", $billingdata);
			$billingdata = str_replace("{email}", "", $billingdata);
			$billingdata = str_replace("{email_lbl}", "", $billingdata);
			$billingdata = str_replace("{phone}", "", $billingdata);
			$billingdata = str_replace("{phone_lbl}", "", $billingdata);
			$billingdata = str_replace("{vatnumber}", "", $billingdata);
			$billingdata = str_replace("{vatnumber_lbl}", "", $billingdata);
			$billingdata = str_replace("{ean_number}", "", $billingdata);
			$billingdata = str_replace("{ean_number_lbl}", "", $billingdata);
			$billingdata = str_replace("{taxexempt}", "", $billingdata);
			$billingdata = str_replace("{taxexempt_lbl}", "", $billingdata);
			$billingdata = str_replace("{user_taxexempt_request}", "", $billingdata);
			$billingdata = str_replace("{user_taxexempt_request_lbl}", "", $billingdata);
			$billingdata = str_replace("{billing_extrafield}", $billing_extrafield, $billingdata);
			
			$data = $template_sdata[0] . $billingdata . $template_edata[1];
		} elseif (strpos($data, '{billing_address}') !== false) {
			$billadd = '';
			
			if (isset($billingaddresses)) {
				$billingLayout = 'cart.billing';
				
				if ($sendmail) {
					$billingLayout = 'mail.billing';
				}
				
				$billadd = RedshopLayoutHelper::render(
					$billingLayout,
					array('billingaddresses' => $billingaddresses),
					null,
					array('client' => 0)
				);
				
				if (strpos($data, '{quotation_custom_field_list}') !== false) {
					$data = str_replace('{quotation_custom_field_list}', '', $data);
					
					if (DEFAULT_QUOTATION_MODE) {
						$billadd .= $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, '', '');
					}
				} elseif (DEFAULT_QUOTATION_MODE) {
					$data = $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, '', '', $data);
				}
			}
			
			$data = str_replace("{billing_address}", $billadd, $data);
		}
		
		$data = str_replace("{billing_address}", "", $data);
		$data = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $data);
		
		return $data;
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
		if (strpos($data, '{shipping_address_start}') !== false && strpos($data, '{shipping_address_end}') !== false) {
			$template_sdata = explode('{shipping_address_start}', $data);
			$template_edata = explode('{shipping_address_end}', $template_sdata[1]);
			$shippingdata = (SHIPPING_METHOD_ENABLE) ? $template_edata[0] : '';
			
			$shipping_extrafield = '';
			
			if (isset($shippingaddresses) && SHIPPING_METHOD_ENABLE) {
				$extra_section = ($shippingaddresses->is_company == 1) ? 15 : 14;
				
				if ($shippingaddresses->is_company == 1 && $shippingaddresses->company_name != "") {
					$shippingdata = str_replace("{companyname}", $shippingaddresses->company_name, $shippingdata);
					$shippingdata = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $shippingdata);
				}
				
				if ($shippingaddresses->firstname != "") {
					$shippingdata = str_replace("{firstname}", $shippingaddresses->firstname, $shippingdata);
					$shippingdata = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $shippingdata);
				}
				
				if ($shippingaddresses->lastname != "") {
					$shippingdata = str_replace("{lastname}", $shippingaddresses->lastname, $shippingdata);
					$shippingdata = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $shippingdata);
				}
				
				if ($shippingaddresses->address != "") {
					$shippingdata = str_replace("{address}", $shippingaddresses->address, $shippingdata);
					$shippingdata = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $shippingdata);
				}
				
				if ($shippingaddresses->zipcode != "") {
					$shippingdata = str_replace("{zip}", $shippingaddresses->zipcode, $shippingdata);
					$shippingdata = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $shippingdata);
				}
				
				if ($shippingaddresses->city != "") {
					$shippingdata = str_replace("{city}", $shippingaddresses->city, $shippingdata);
					$shippingdata = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $shippingdata);
				}
				
				$cname = $this->_order_functions->getCountryName($shippingaddresses->country_code);
				
				if ($cname != "") {
					$shippingdata = str_replace("{country}", JText::_($cname), $shippingdata);
					$shippingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $shippingdata);
				}
				
				$sname = $this->_order_functions->getStateName($shippingaddresses->state_code, $shippingaddresses->country_code);
				
				if ($sname != "") {
					$shippingdata = str_replace("{state}", $sname, $shippingdata);
					$shippingdata = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $shippingdata);
				}
				
				if ($shippingaddresses->phone != "") {
					$shippingdata = str_replace("{phone}", $shippingaddresses->phone, $shippingdata);
					$shippingdata = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $shippingdata);
				}
				
				// Additional functionality - more flexible way
				$shippingdata = $this->_extraFieldFront->extra_field_display($extra_section, $shippingaddresses->users_info_id, "", $shippingdata);
				
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
		} elseif (strpos($data, '{shipping_address}') !== false) {
			$shipadd = '';
			
			if (isset($shippingaddresses) && SHIPPING_METHOD_ENABLE) {
				$shippingLayout = 'cart.shipping';
				
				if ($sendmail) {
					$shippingLayout = 'mail.shipping';
				}
				
				$shipadd = RedshopLayoutHelper::render(
					$shippingLayout,
					array('shippingaddresses' => $shippingaddresses),
					null,
					array('client' => 0)
				);
				
				if ($shippingaddresses->is_company == 1) {
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(15, $shippingaddresses->users_info_id, "", $data);
				} else {
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(14, $shippingaddresses->users_info_id, "", $data);
				}
			}
			
			$data = str_replace("{shipping_address}", $shipadd, $data);
		}
		
		$shippingtext = (SHIPPING_METHOD_ENABLE) ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$data = str_replace("{shipping_address}", "", $data);
		$data = str_replace("{shipping_address_information_lbl}", $shippingtext, $data);
		
		return $data;
	}
	
	/*
	 * replace Conditional tag from Redshop Discount
	 */
	
	public function replaceDiscount($data = '', $discount = 0, $subtotal = 0, $quotation_mode = 0)
	{
		if (strpos($data, '{if discount}') !== false && strpos($data, '{discount end if}') !== false) {
			$percentage = '';
			
			if ($discount <= 0) {
				$template_discount_sdata = explode('{if discount}', $data);
				$template_discount_edata = explode('{discount end if}', $template_discount_sdata[1]);
				$data = $template_discount_sdata[0] . $template_discount_edata[1];
			} else {
				$data = str_replace("{if discount}", '', $data);
				
				if ($quotation_mode && !SHOW_QUOTATION_PRICE) {
					$data = str_replace("{discount}", "", $data);
					$data = str_replace("{discount_in_percentage}", $percentage, $data);
					
				} else {
					$data = str_replace("{discount}", $this->_producthelper->getProductFormattedPrice($discount, true), $data);
					$data = str_replace("{order_discount}", $this->_producthelper->getProductFormattedPrice($discount, true), $data);
					
					if (!empty($subtotal) && $subtotal > 0) {
						$percentage = round(($discount * 100 / $subtotal), 2) . " %";
					}
					
					$data = str_replace("{discount_in_percentage}", $percentage, $data);
				}
				
				$data = str_replace("{discount_lbl}", JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $data);
				$data = str_replace("{discount end if}", '', $data);
			}
		}
		
		return $data;
	}
	
	/**
	 * replace Conditional tag from Redshop payment Discount/charges
	 *
	 * @param string $data
	 * @param int $amount
	 * @param int $cart
	 * @param string $payment_oprand
	 *
	 * @return mixed|string
	 */
	public function replacePayment($data = '', $amount = 0, $cart = 0, $payment_oprand = '-')
	{
		if (strpos($data, '{if payment_discount}') !== false && strpos($data, '{payment_discount end if}') !== false) {
			if ($cart == 1 || $amount == 0) {
				$template_pdiscount_sdata = explode('{if payment_discount}', $data);
				$template_pdiscount_edata = explode('{payment_discount end if}', $template_pdiscount_sdata[1]);
				$data = $template_pdiscount_sdata[0] . $template_pdiscount_edata[1];
				
				return $data;
			}
			
			if ($amount <= 0) {
				$template_pd_sdata = explode('{if payment_discount}', $data);
				$template_pd_edata = explode('{payment_discount end if}', $template_pd_sdata[1]);
				$data = $template_pd_sdata[0] . $template_pd_edata[1];
			} else {
				$data = str_replace("{payment_order_discount}", $this->_producthelper->getProductFormattedPrice($amount), $data);
				$payText = ($payment_oprand == '+') ? JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL') : JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
				$data = str_replace("{payment_discount_lbl}", $payText, $data);
				$data = str_replace("{payment_discount end if}", '', $data);
				$data = str_replace("{if payment_discount}", '', $data);
			}
		}
		
		return $data;
	}
	
	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param string $data
	 * @param int $amount
	 * @param int $discount
	 * @param int $check
	 * @param int $quotation_mode
	 *
	 * @return mixed|string
	 */
	public function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotation_mode = 0)
	{
		if (strpos($data, '{if vat}') !== false && strpos($data, '{vat end if}') !== false) {
			$cart = $this->_session->get('cart');
			
			if ($amount <= 0) {
				$template_vat_sdata = explode('{if vat}', $data);
				$template_vat_edata = explode('{vat end if}', $template_vat_sdata[1]);
				$data = $template_vat_sdata[0] . $template_vat_edata[1];
			} else {
				if ($quotation_mode && !SHOW_QUOTATION_PRICE) {
					$data = str_replace("{tax}", "", $data);
					$data = str_replace("{order_tax}", "", $data);
				} else {
					$data = str_replace("{tax}", $this->_producthelper->getProductFormattedPrice($amount, true), $data);
					$data = str_replace("{order_tax}", $this->_producthelper->getProductFormattedPrice($amount, true), $data);
				}
				
				if (strpos($data, '{tax_after_discount}') !== false) {
					if (APPLY_VAT_ON_DISCOUNT && (float)VAT_RATE_AFTER_DISCOUNT) {
						if ($check) {
							$tax_after_discount = $discount;
						} else {
							if (!isset($cart['tax_after_discount'])) {
								$tax_after_discount = $this->calculateTaxafterDiscount($amount, $discount);
							} else {
								$tax_after_discount = $cart['tax_after_discount'];
							}
						}
						
						if ($tax_after_discount > 0) {
							$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($tax_after_discount), $data);
						} else {
							$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($cart['tax']), $data);
						}
					} else {
						$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($cart['tax']), $data);
					}
				}
				
				$data = str_replace("{vat_lbl}", JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $data);
				$data = str_replace("{if vat}", '', $data);
				$data = str_replace("{vat end if}", '', $data);
			}
		}
		
		return $data;
	}
}