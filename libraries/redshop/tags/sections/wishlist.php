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
		'{wishlist_link}'
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

		$user = JFactory::getUser();

		JHtml::script('com_redshop/redshop.wishlist.js', false, true, false, false);

		// Product Wishlist - New Feature Like Magento Store
		if (!$user->guest)
		{
			$link = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component';

			$wishListButton = '<input class="redshop-wishlist-button" data-productid="' . $productId . '"'
				. ' type="button" value="' . JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . '"'
				. ' data-href="' . $link . '" />';

			$wishListLink = JText::_("COM_REDSHOP_ADD_TO_WISHLIST");
			$wishPrefix = '<a class="redshop-wishlist-link" href="' . $link . '" data-productid="' . $productId . '" >';
			$wishSuffix = '</a>';
		}
		else
		{
			$wishListButton = "<input type='submit' name='btnwishlist' id='btnwishlist' value='"
				. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . "'>";

			if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0)
			{
				// @TODO: Refactor -> Redirect to login / register and return to current page. Disable pop-up
				$link = (string) JRoute::_('index.php?option=com_redshop&view=wishlist&task=viewloginwishlist&tmpl=component');
				$wishListLink = JText::_("COM_REDSHOP_ADD_TO_WISHLIST");
				$wishPrefix = "<a class=\"modal btn btn-primary\" href=\"" . $link
					. "\" rel=\"{handler:'iframe',size:{x:450,y:350}}\" >";
				$wishSuffix = '</a>';
			}
			else
			{
				$wishListLink = "<a href=\"#\" onclick=\"document.getElementById('form_wishlist_" . $productId
					. "').submit();return false;\" class='wishlistlink btn btn-primary'>" . JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . "</a>";
				$wishPrefix = "<form method='post' action='' id='form_wishlist_" . $productId
					. "' name='form_wishlist_" . $productId . "'>
								<input type='hidden' name='task' value='addtowishlist' />
							    <input type='hidden' name='product_id' value='" . $productId . "' />
								<input type='hidden' name='view' value='product' />
								<input type='hidden' name='rurl' value='" . base64_encode(JUri::getInstance()->toString()) . "' />";
				$wishSuffix = '</form>';
			}
		}

		$this->addReplace('{wishlist_button}', $wishPrefix . $wishListButton . $wishSuffix);
		$this->addReplace('{wishlist_link}', $wishPrefix . $wishListLink . $wishSuffix);
	}
}
