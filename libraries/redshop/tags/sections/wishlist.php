<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since __DEPLOY_VERSION__
 */
class RedshopTagsSectionsWishlist extends RedshopTagsAbstract
{
    use \Redshop\Traits\Replace\Product;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $wishlistId;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $itemId;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $mainId = null;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $totalId = null;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $totalCountNoUserField = null;

	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $extraFieldName = array();

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $view;

	/**
	 * @var    string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $height = 'CATEGORY_PRODUCT_THUMB_HEIGHT';

	/**
	 * @var    string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $width = 'CATEGORY_PRODUCT_THUMB_WIDTH';

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function init()
	{
		$input = JFactory::getApplication()->input;
		$this->wishlistId = $input->getInt('wishlist_id');
		$this->itemId = $input->getInt('Itemid');
		$this->view = $input->get('view');
	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since __DEPLOY_VERSION__
	 */
	public function replace()
	{
		if ($this->view == 'product' || $this->view == 'category') {
			if (empty($this->data['productId'])) {
				return;
			}

			JHtml::script('com_redshop/redshop.wishlist.min.js', false, true);

			$productId = $this->data['productId'];
			$formId = $this->data['formId'];
			$user = JFactory::getUser();
			$link = '';

			if (!$user->guest) {
				$link = JURI::root(
					) . 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component';
			} else {
				if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0) {
					$link = JRoute::_('index.php?option=com_redshop&view=login&wishlist=1&product_id=' . $productId);
				}
			}

			if ($this->isTagExists('{wishlist_button}')) {
				$wishlistButton = RedshopLayoutHelper::render(
					'tags.product.wishlist_button',
					array(
						'link' => $link,
						'productId' => $productId,
						'formId' => $formId
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);

				$this->replacements["{wishlist_button}"] = $wishlistButton;
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			if ($this->isTagExists('{wishlist_link}') || $this->isTagExists('{property_wishlist_link}')) {
				$wishlistLink = RedshopLayoutHelper::render(
					'tags.product.wishlist_link',
					array(
						'link' => $link,
						'productId' => $productId,
						'formId' => $formId
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);

				$this->replacements["{wishlist_link}"] = $wishlistLink;
				$this->replacements["{property_wishlist_link}"] = $wishlistLink;
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			return $this->template;
		} elseif ($this->view == 'account') {
			$this->extraFieldName = \Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
			$wishlists = $this->data['wishlist'];
			$templateProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

			if (count($wishlists) > 0) {
                $wishlistTemplate = $this->replaceWishListMain($wishlists, $templateProduct['template']);
				$this->template = $wishlistTemplate;
			} else {
				$this->replacements["{mail_link}"] = '';
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

			if (count($wishlists) > 0) {
				return $this->template;
			} else {
				$noWishList = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'dev',
						'class' => 'nowishlist',
						'id' => 'nowishlist',
						'text' => JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST')
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->template = $noWishList;
				return $this->template;
			}
		} elseif ($this->view == 'wishlist') {
			$userFieldArr = $this->data['userFieldArr'];
			$wishlistSesions = $this->data['wishlistSesion'];
			$wishlists = $this->data['wishlists'];
			$template = $this->replaceWishListMain($wishlistSesions, $this->template);
			$user = $this->data['user'];
			$countWishlistSesion = count($this->data['wishlistSesion']);
			$wishlistsArr = array();

			if (count($wishlistSesions) > 0) {
				if (isset($user->id) && $user->id != 0) {
					$myProductId = '';
					$countNoUserField = 0;

					foreach ($wishlistSesions as $wishlistSesion) {
						for ($i = 1; $i < count($userFieldArr); $i++) {
							$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
								$userFieldArr[$i],
								12,
								'',
								0,
								0,
								$wishlistSesion->product_id
							);

							if ($productUserFields[1] != "") {
								$countNoUserField++;
							}
						}

						$myProductId .= $wishlistSesion->product_id . ",";
					}

					$link = "index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component";
				} else {
					$link = JRoute::_("index.php?wishlist=1&option=com_redshop&view=login&Itemid=" . $this->itemId);
					$myProductId = '';
					$countNoUserField = 0;

					foreach ($wishlistSesions as $wishlistSesion) {
						for ($k = 0; $k < count($userFieldArr); $k++) {
							$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
								$userFieldArr[$k],
								12,
								'',
								0,
								0,
                                $wishlistSesion->product_id
							);

							if ($productUserFields[1] != "") {
								$countNoUserField++;
							}
						}

						$myProductId .= $wishlistSesion->product_id . ",";
					}
				}
			}

			if (!empty($wishlists) && count((array)$wishlists) > 0) {
				foreach ($wishlists as $wishlist) {
					$wishlistName = $wishlist->wishlist_name ?? '';
					$wishlistLink = JRoute::_(
						"index.php?view=account&layout=mywishlist&wishlist_id=" . $wishlist->wishlist_id . "&option=com_redshop&Itemid=" . $this->itemId
					);
					$delWishlist = JRoute::_(
						"index.php?view=wishlist&task=delwishlist&wishlist_id=" . $wishlist->wishlist_id . "&option=com_redshop&Itemid=" . $this->itemId
					);
					$wishlist = array_merge(
						(array)$wishlist,
						array(
							'wishlistLink' => $wishlistLink,
							'wishlistName' => $wishlistName,
							'delWishlist' => $delWishlist
						)
					);
					$wishlistsArr[] = $wishlist;
				}
			}

			$layout = RedshopLayoutHelper::render(
				'tags.wishlist.template',
				array(
					'userId' => $user->id,
					'wishlistSesion' => $this->data['wishlistSesion'],
					'countSesion' => $countWishlistSesion,
					'content' => $template,
					'wishlists' => $wishlistsArr,
					'link' => $link ?? '',
					'countNoUserField' => $countNoUserField ?? '',
					'myProductId' => $myProductId ?? ''
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->template = $layout;
			return parent::replace();
		}
	}

	/**
	 * @param $templateProduct
	 * @param $wishlist
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since __DEPLOY_VERSION__
	 */
	public function replaceProduct($wishlist, $templateProduct)
	{
	    $templateProduct = $this->replaceCommonProduct($templateProduct, $wishlist->product_id);
		$isIndividualAddToCart = (boolean)Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE');

		$link = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $wishlist->product_id . '&Itemid=' . $this->itemId
		);
		$linkRemove = 'index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id=' . $this->wishlistId
			. '&pid=' . $wishlist->product_id . '&remove=1';

		if ($isIndividualAddToCart) {
		    if (isset($wishlist->wishlistData->wishlist_product_id))
            {
                $linkRemove .= '&wishlist_product_id=' . $wishlist->wishlistData->wishlist_product_id;
            }
		    else
            {
                $linkRemove .= '&wishlist_product_id=""';
            }
		}

		if (isset($wishlist->wishlist_id)) {
			$linkRemove = JRoute::_($linkRemove . '&Itemid=' . $this->itemId, false);
		} else {
			$linkRemove = JRoute::_(
				"index.php?mydel=1&view=wishlist&wishlist_id=" . $wishlist->product_id . "&task=mysessdelwishlist",
				false
			);
		}

		if ($this->isTagExists('{read_more}')) {
			$rMore = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $link,
					'attr' => 'title="' . $wishlist->product_name . '"',
					'content' => JText::_('COM_REDSHOP_READ_MORE')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

            $repleaceProduct["{read_more}"] = $rMore;
			$templateProduct = $this->strReplace($repleaceProduct, $templateProduct);
		}

		if ($this->isTagExists('{read_more_link}')) {
			$rMoreLink = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $link,
					'content' => JText::_('COM_REDSHOP_READ_MORE')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

            $repleaceProduct["{read_more_link}"] = $rMoreLink;
			$templateProduct = $this->strReplace($repleaceProduct, $templateProduct);
		}

		if ($this->isTagExists('{remove_product_link}')) {
			$removeProductLink = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $linkRemove,
					'content' => JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_WISHLIST'),
					'attr' => 'style="text-decoration:none"'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

            $repleaceProduct["{remove_product_link}"] = $removeProductLink;
			$templateProduct = $this->strReplace($repleaceProduct, $templateProduct);
		}

		return $templateProduct;
	}

	public function replaceWishListMain($wishlists, &$template)
	{
		$this->extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
		$wishlistTemplate = '';
		$templateProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

		if (count($wishlists) > 0) {
			$link = JURI::root(
				) . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component&wishlist_id=" . $this->wishlistId;

			foreach ($wishlists as $wishlist) {
				$wishlistTemplate .= $this->replaceProduct($wishlist, $templateProduct['template']);
			}

			$template = $templateProduct['begin'] . $wishlistTemplate . $templateProduct['end'];

			if ($this->isTagExists('{mail_link}')) {
				$srcImage = RedshopLayoutHelper::render(
					'tags.common.img',
					array(
						'src' => REDSHOP_MEDIA_IMAGES_ABSPATH . 'mailcenter16.png'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$mailLink = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'class' => 'redcolorproductimg',
						'link' => $link,
						'content' => $srcImage
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements["{mail_link}"] = $mailLink;
				$template = $this->strReplace($this->replacements, $template);
			}

			if ($this->isTagExists('{back_link}')) {
				$backLink = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'link' => JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $this->itemId),
						'content' => JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT'),
						'attr' => 'title="' . JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT') . '"'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements["{back_link}"] = $backLink;
				$template = $this->strReplace($this->replacements, $template);
			}

			if ($this->isTagExists('{all_cart}')) {
				$backLink = RedshopLayoutHelper::render(
					'tags.wishlist.all_cart',
					array(
						'wishlist' => count($wishlists),
						'mainId' => $this->mainId,
						'totalId' => $this->totalId,
						'totalCountNoUserField' => $this->totalCountNoUserField
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements["{all_cart}"] = $backLink;
				$template = $this->strReplace($this->replacements, $template);
			}
		} else {
			$template = '';
		}

		return $template;
	}
}