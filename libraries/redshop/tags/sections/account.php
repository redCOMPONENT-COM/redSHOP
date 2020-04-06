<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 20020 - 2021 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsAccount extends RedshopTagsAbstract
{
	use \Redshop\Traits\Replace\NewsletterSubscription;

	/**
	 * @var    array
	 *
	 * @since   3.0.1
	 */
	public $tags = array();

	/**
	 * @var    integer
	 *
	 * @since   3.0.1
	 */
	public $itemId;

	/**
	 * @var    JUser
	 *
	 * @since   3.0.1
	 */
	private $user;

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	public function init()
	{
		$this->optionLayout = RedshopLayoutHelper::$layoutOption;
		$this->itemId       = JFactory::getApplication()->input->getInt('Itemid');
		$this->user         = JFactory::getUser();
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	public function replace()
	{
		$params       = JFactory::getApplication()->getParams('com_redshop');
		$returnItemId = $params->get('logout', $this->itemId);
		$db           = JFactory::getDbo();

		if ($this->data['params']->get('show_page_heading', 1)) {
			$this->template = RedshopLayoutHelper::render(
					'tags.common.pageheading',
					[
						'params'         => $this->data['params'],
						'pageheading'    => trim($db->escape($this->data['params']->get('page_title'))),
						'pageHeadingTag' => JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE'),
						'class'          => 'account'
					],
					'',
					$this->optionLayout
				) . $this->template;
		}

		JPluginHelper::importPlugin('redshop_account');
		JPluginHelper::importPlugin('user');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onReplaceAccountTemplate', array(&$this->template, $this->data['userData']));

		$this->replacements['{welcome_introtext}'] = Redshop::getConfig()->get('WELCOMEPAGE_INTROTEXT');

		$this->replacements['{logout_link}'] = RedshopLayoutHelper::render(
			'tags.account.logout_link',
			[
				'logoutLink' => JRoute::_(
					"index.php?option=com_redshop&view=login&task=logout&logout=" . $returnItemId . "&Itemid=" . $this->itemId
				)
			],
			'',
			$this->optionLayout
		);

		$this->replacements['{account_image}'] = RedshopLayoutHelper::render(
			'tags.common.img',
			[
				'src'  => REDSHOP_FRONT_IMAGES_ABSPATH . 'account/home.jpg',
				'attr' => 'align="absmiddle"'
			],
			'',
			$this->optionLayout
		);

		$this->replacements['{account_title}'] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');

		$this->template = RedshopHelperBillingTag::replaceBillingAddress($this->template, $this->data['userData']);

		$this->replacements['{edit_account_link}'] = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'class'   => 'btn btn-primary',
				'link'    => JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $this->itemId),
				'content' => JText::_('COM_REDSHOP_EDIT_ACCOUNT_INFORMATION')
			],
			'',
			$this->optionLayout
		);

		$this->replacements['{delete_account_link}'] = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'class'   => 'btn btn-primary',
				'link'    => JRoute::_(
					"index.php?option=com_redshop&view=account&task=deleteAccount&Itemid=" . $this->itemId
				),
				'attr'    => 'onclick="return confirm(\'' . JText::_(
						'COM_REDSHOP_DO_YOU_WANT_TO_DELETE'
					) . '\');"',
				'content' => JText::_('COM_REDSHOP_DELETE_ACCOUNT')
			],
			'',
			$this->optionLayout
		);

		$this->template = $this->replaceNewsletterSubscription($this->template, 1);
		$this->replaceTagShipping();

		$isCompany = $this->data['userData']->is_company;

		if ($isCompany == 1) {
			$extrafields = RedshopHelperExtrafields::listAllFieldDisplay(8, $this->data['userData']->users_info_id);
		} else {
			$extrafields = RedshopHelperExtrafields::listAllFieldDisplay(7, $this->data['userData']->users_info_id);
		}

		$this->replacements['{customer_custom_fields}'] = $extrafields;

		if ($this->isTagExists('{reserve_discount}')) {
			$reserveDiscount = Redshop\Account\Helper::getReserveDiscount();
			$reserveDiscount = RedshopHelperProductPrice::formattedPrice($reserveDiscount);

			$this->replacements['{reserve_discount}']     = $reserveDiscount;
			$this->replacements['{reserve_discount_lbl}'] = JText::_('COM_REDSHOP_RESERVED_DISCOUNT_LBL');
		}

		$this->replaceOrder();
		$this->replaceCoupon();
		$this->replaceMyTag();
		$this->replaceQuotation();
		$this->replaceWishlist();
		$this->replaceProductSerial();
		$this->replaceCompareProduct();

		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		return parent::replace();
	}

	/**
	 * Execute replace tag shipping
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceTagShipping()
	{
		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) {
			$this->replacements['{shipping_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_FRONT_IMAGES_ABSPATH . 'account/order.jpg',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{edit_shipping_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'tags.common.link',
					'link'    => JRoute::_("index.php?option=com_redshop&view=account_shipto&Itemid=" . $this->itemId),
					'content' => JText::_('COM_REDSHOP_UPDATE_SHIPPING_INFO')
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{shipping_title}'] = JText::_('COM_REDSHOP_SHIPPING_INFO');
		} else {
			$this->replacements['{shipping_image}']     = '';
			$this->replacements['{shipping_title}']     = '';
			$this->replacements['{edit_shipping_link}'] = '';
		}
	}

	/**
	 * Execute replace order
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceOrder()
	{
		$subTemplate = $this->getTemplateBetweenLoop('{order_loop_start}', '{order_loop_end}');

		if (!empty($subTemplate)) {
			$this->replacements['{order_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'order16.png',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{order_title}'] = JText::_('COM_REDSHOP_ORDER_INFORMATION');

			// More Order information
			$ordersList = RedshopHelperOrder::getUserOrderDetails($this->user->id);

			if (count($ordersList) > 0) {
				$this->replacements['{more_orders}'] = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => JRoute::_('index.php?option=com_redshop&view=orders&Itemid=' . $this->itemId),
						'content' => JText::_('COM_REDSHOP_MORE'),
					],
					'',
					$this->optionLayout
				);
			} else {
				$this->replacements['{more_orders}'] = '';
			}

			$orderData = '';

			if (count($ordersList)) {
				for ($j = 0, $jn = count($ordersList); $j < $jn; $j++) {
					if ($j >= 5) {
						break;
					}

					$replaceOrder = [];

					$replaceOrder['{order_detail_link}'] = RedshopLayoutHelper::render(
						'tags.common.link',
						[
							'link'    => JRoute::_(
								'index.php?option=com_redshop&view=order_detail&oid=' . $ordersList[$j]->order_id . '&Itemid=' . $this->itemId
							),
							'content' => JText::_('COM_REDSHOP_DETAILS')

						],
						'',
						$this->optionLayout
					);

					$replaceOrder['{order_index}']  = JText::_('COM_REDSHOP_ORDER_NUM');
					$replaceOrder['{order_id}']     = $ordersList[$j]->order_id;
					$replaceOrder['{order_number}'] = $ordersList[$j]->order_number;
					$replaceOrder['{order_total}']  = RedshopHelperProductPrice::formattedPrice(
						$ordersList[$j]->order_total
					);

					$orderData .= $this->strReplace($replaceOrder, $subTemplate['template']);
				}
			} else {
				$replaceOrder['{order_index}']       = '';
				$replaceOrder['{order_id}']          = '';
				$replaceOrder['{order_number}']      = '';
				$replaceOrder['{order_total}']       = '';
				$replaceOrder['{order_detail_link}'] = JText::_('COM_REDSHOP_NO_ORDERS_PLACED_YET');

				$orderData .= $this->strReplace($replaceOrder, $subTemplate['template']);
			}

			$this->template = $subTemplate['begin'] . $orderData . $subTemplate['end'];
		}
	}

	/**
	 * Execute replace coupon
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceCoupon()
	{
		$couponTemplate = $this->getTemplateBetweenLoop('{coupon_loop_start}', '{coupon_loop_end}');
		$couponData     = '';

		if (!empty($couponTemplate)) {
			$userCoupons = \Redshop\Promotion\Coupon::getUserCoupons($this->user->id);

			if (Redshop::getConfig()->get('COUPONINFO') && count($userCoupons) > 0) {
				$this->replacements['{coupon_title}'] = JText::_('COM_REDSHOP_COUPON_INFO');
				$this->replacements['{coupon_image}'] = RedshopLayoutHelper::render(
					'tags.common.img',
					[
						'src'  => REDSHOP_FRONT_IMAGES_ABSPATH . 'account/coupon.jpg',
						'attr' => 'align="absmiddle"'
					],
					'',
					$this->optionLayout
				);

				for ($i = 0, $in = count($userCoupons); $i < $in; $i++) {
					$replaceCoupon = [];

					$replaceCoupon['{coupon_code_lbl}']     = JText::_('COM_REDSHOP_COUPON_CODE');
					$replaceCoupon['{coupon_code}']         = $userCoupons[$i]->code;
					$replaceCoupon['{coupon_value_lbl}']    = JText::_('COM_REDSHOP_COUPON_VALUE');
					$replaceCoupon['{coupon_value_lbl}']    = JText::_('COM_REDSHOP_COUPON_VALUE');
					$replaceCoupon['{unused_coupon_value}'] = Redshop\Account\Helper::getUnusedCouponAmount(
						$this->user->id,
						$userCoupons[$i]->code
					);

					$replaceCoupon['{coupon_value}'] = ($userCoupons[$i]->type == 0) ? RedshopHelperProductPrice::formattedPrice(
						$userCoupons[$i]->value
					) : $userCoupons[$i]->value . ' %';

					$couponData .= $this->strReplace($replaceCoupon, $couponTemplate['template']);
				}
			} else {
				$this->replacements['{coupon_title}'] = '';
				$this->replacements['{coupon_image}'] = '';
			}

			$this->template = $couponTemplate['begin'] . $couponData . $couponTemplate['end'];
		}
	}

	/**
	 * Execute replace my tag
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceMyTag()
	{
		$myTags = \Redshop\Account\Helper::countMyTags();

		if (Redshop::getConfig()->get('MY_TAGS')) {
			$this->replacements['{tag_title}'] = JText::_('COM_REDSHOP_MY_TAGS');
			$this->replacements['{tag_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'textlibrary16.png',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$tagLink = JText::_('COM_REDSHOP_NO_TAGS_AVAILABLE');

			if ($myTags > 0) {
				$tagLink = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => JRoute::_(
							"index.php?option=com_redshop&view=account&layout=mytags&Itemid=" . $this->itemId
						),
						'content' => JText::_("COM_REDSHOP_SHOW_TAG"),
						'attr'    => 'style="text-decoration: none;"',
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{edit_tag_link}'] = $tagLink;
		} else {
			$this->replacements['{tag_title}']     = '';
			$this->replacements['{tag_image}']     = '';
			$this->replacements['{edit_tag_link}'] = '';
		}
	}

	/**
	 * Execute replace quotation
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceQuotation()
	{
		$subTemplateQuotation = $this->getTemplateBetweenLoop('{quotation_loop_start}', '{quotation_loop_end}');

		if (!empty($subTemplateQuotation)) {
			$this->replacements['{quotation_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'quotation_16.jpg',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{quotation_title}'] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');

			$quotations = RedshopHelperQuotation::getQuotationUserList();

			$quotationData  = '';
			$countQuotation = count($quotations);

			if ($countQuotation) {
				$this->replacements['{more_quotations}'] = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => JRoute::_('index.php?option=com_redshop&view=quotation&Itemid=' . $this->itemId),
						'content' => JText::_('COM_REDSHOP_MORE')
					],
					'',
					$this->optionLayout
				);

				for ($j = 0, $jn = $countQuotation; $j < $jn; $j++) {
					if ($j >= 5) {
						break;
					}

					$replaceQuotation = [];

					$replaceQuotation['{quotation_detail_link}'] = RedshopLayoutHelper::render(
						'tags.common.link',
						[
							'link'    => JRoute::_(
								'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotations[$j]->quotation_id . '&Itemid=' . $this->itemId
							),
							'content' => JText::_('COM_REDSHOP_DETAILS'),
							'attr'    => 'title="' . JText::_('COM_REDSHOP_VIEW_QUOTATION') . '"'
						],
						'',
						$this->optionLayout
					);

					$replaceQuotation['{quotation_index}'] = JText::_('COM_REDSHOP_QUOTATION') . " #";
					$replaceQuotation['{quotation_id}']    = $quotations[$j]->quotation_id;
					$quotationData                         .= $this->strReplace(
						$replaceQuotation,
						$subTemplateQuotation['template']
					);
				}
			} else {
				$quotationData                           = JText::_('COM_REDSHOP_NO_QUOTATION_PLACED_YET');
				$this->replacements['{more_quotations}'] = '';
			}

			$this->template = $subTemplateQuotation['begin'] . $quotationData . $subTemplateQuotation['end'];
		}
	}

	/**
	 * Execute replace wishlist
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceWishlist()
	{
		if (Redshop::getConfig()->get('MY_WISHLIST')) {
			$this->replacements['{wishlist_title}'] = JText::_('COM_REDSHOP_MY_WISHLIST');
			$this->replacements['{wishlist_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'textlibrary16.png',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$editWishlistLink = JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST');
			$myWishist        = \Redshop\Wishlist\Helper::countMyWishlist();

			if ($myWishist) {
				$editWishlistLink = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => JRoute::_(
							"index.php?option=com_redshop&view=wishlist&task=viewwishlist&Itemid=" . $this->itemId
						),
						'content' => JText::_("COM_REDSHOP_SHOW_WISHLIST_PRODUCTS"),
						'style'   => 'style="text-decoration: none;"'
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{edit_wishlist_link}'] = $editWishlistLink;
		} else {
			$this->replacements['{wishlist_title}']     = '';
			$this->replacements['{wishlist_image}']     = '';
			$this->replacements['{edit_wishlist_link}'] = '';
		}
	}

	/**
	 * Execute replace product serial
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceProductSerial()
	{
		$subTemplateProductSerial = $this->getTemplateBetweenLoop(
			'{product_serial_loop_start}',
			'{product_serial_loop_end}'
		);

		if (!empty($subTemplateProductSerial)) {
			$this->replacements['{product_serial_title}'] = JText::_('COM_REDSHOP_MY_SERIALS');
			$this->replacements['{product_serial_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'products16.png',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$userDownloadProduct = Redshop\Account\Helper::getDownloadProductList($this->user->id);
			$serialData          = '';

			if (!empty($userDownloadProduct)) {
				for ($j = 0, $jn = count($userDownloadProduct); $j < $jn; $j++) {
					$replaceProductSerial = [];

					$replaceProductSerial['{product_name}']          = $userDownloadProduct[$j]->product_name;
					$replaceProductSerial['{product_serial_number}'] = $userDownloadProduct[$j]->product_serial_number;

					$serialData .= $this->strReplace(
						$replaceProductSerial,
						$subTemplateProductSerial['template']
					);
				}
			}

			$this->template = $subTemplateProductSerial['begin'] . $serialData . $subTemplateProductSerial['end'];
		}
	}

	/**
	 * Execute replace compare product
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceCompareProduct()
	{
		if (Redshop::getConfig()->get('COMPARE_PRODUCTS')) {
			$this->replacements['{compare_title}'] = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
			$this->replacements['{compare_image}'] = RedshopLayoutHelper::render(
				'tags.common.img',
				[
					'src'  => REDSHOP_MEDIA_IMAGES_ABSPATH . 'textlibrary16.png',
					'attr' => 'align="absmiddle"'
				],
				'',
				$this->optionLayout
			);

			$cmpLink = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
			$compare = new RedshopProductCompare;

			if (!$compare->isEmpty()) {
				$cmpLink = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => JRoute::_(
							"index.php?option=com_redshop&view=product&layout=compare&Itemid=" . $this->itemId
						),
						'content' => JText::_("COM_REDSHOP_SHOW_PRODUCTS_TO_COMPARE"),
						'attr'    => 'style="text-decoration: none;"'
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{edit_compare_link}'] = $cmpLink;
		} else {
			$this->replacements['{compare_title}']     = '';
			$this->replacements['{compare_image}']     = '';
			$this->replacements['{edit_compare_link}'] = '';
		}
	}
}