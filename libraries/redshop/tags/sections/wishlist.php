<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsWishlist extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $tags = array(
		'{wishlist_button}',
		'{wishlist_link}',
		'{property_wishlist_link}'
	);

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		if (empty($this->data['productId']))
		{
			return;
		}

		$productId = $this->data['productId'];
		$formId    = $this->data['formId'];
		$user      = JFactory::getUser();

		JHtml::script('com_redshop/redshop.wishlist.js', false, true, false, false);

		// Product Wishlist - New Feature Like Magento Store
		if (!$user->guest)
		{
			$link = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component';

			$wishListButton = '<a class="redshop-wishlist-link" href="' . $link . '" data-productid="' . $productId . '" data-formid="' . $formId . '" >
				<input class="redshop-wishlist-button" data-productid="' . $productId . '"'
				. ' type="button" value="' . JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '"'
				. ' data-href="' . $link . '" /></a>';

			$wishListLink = '<a class="redshop-wishlist-link" href="' . $link . '" data-productid="' . $productId . '" data-formid="' . $formId . '" >'
				. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '</a>';
		}
		else
		{
			if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0)
			{
				$link = JRoute::_('index.php?option=com_redshop&view=login&wishlist=1');

				$wishListLink = '<a class="" href="' . $link . '">' . JText::_('COM_REDSHOP_ADD_TO_WISHLIST') . '</a>';
				$wishListButton = '<a class="" href="' . $link . '">'
					. '<input type="submit" class="redshop-wishlist-form-button" name="btnwishlist" id="btnwishlist" value="'
					. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '">'
					. '</a>';
			}
			else
			{
				$wishListButton = "<form method='post' action='' id='form_wishlist_" . $productId . "_button'
					name='form_wishlist_" . $productId . "_button'>
					<input type='hidden' name='task' value='addtowishlist' />
					<input type='hidden' name='product_id' value='" . $productId . "' />
					<input type='hidden' name='view' value='product' />
					<input type='hidden' name='attribute_id' value='' />
					<input type='hidden' name='property_id' value='' />
					<input type='hidden' name='subattribute_id' value='' />
					<input type='hidden' name='rurl' value='" . base64_encode(JUri::getInstance()->toString()) . "' />";
				$wishListButton .= '<input type="submit" data-productid="' . $productId . '" data-formid="'
					. $formId . '" class="redshop-wishlist-form-button" name="btnwishlist" id="btnwishlist" value="'
					. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '" /></form>';

				$wishListLink = "<form method='post' action='' id='form_wishlist_" . $productId . "_link'
					name='form_wishlist_" . $productId . "_link'>
					<input type='hidden' name='task' value='addtowishlist' />
				    <input type='hidden' name='product_id' value='" . $productId . "' />
					<input type='hidden' name='view' value='product' />
					<input type='hidden' name='attribute_id' value='' />
					<input type='hidden' name='property_id' value='' />
					<input type='hidden' name='subattribute_id' value='' />
					<input type='hidden' name='rurl' value='" . base64_encode(JUri::getInstance()->toString()) . "' />";
				$wishListLink .= '<a href="javascript:void(0);" data-productid="' . $productId . '" data-formid="' . $formId . '"
					class="redshop-wishlist-form-link" data-target="form_wishlist_' . $productId . '_link">'
					. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '</a></form>';
			}
		}

		$this->addReplace('{wishlist_button}', $wishListButton);
		$this->addReplace('{wishlist_link}', $wishListLink);
		$this->addReplace('{property_wishlist_link}', $wishListLink);
	}
}
