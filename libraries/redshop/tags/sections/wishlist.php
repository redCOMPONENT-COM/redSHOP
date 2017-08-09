<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.0.3
 */
class RedshopTagsSectionsWishlist extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.0.3
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
	 * @since   2.0.3
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
		$link      = '';

		if (!$user->guest)
		{
			$link = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component';
		}
		else
		{
			if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0)
			{
				$link = JRoute::_('index.php?option=com_redshop&view=login&wishlist=1');
			}
		}

		$wishListButton = RedshopLayoutHelper::render(
			'tags.product.wishlist_button',
			array(
				'link'      => $link,
				'productId' => $productId,
				'formId'    => $formId
			),
			'',
			array(
				'component' => 'com_redshop'
			)
		);

		$wishListLink = RedshopLayoutHelper::render(
			'tags.product.wishlist_link',
			array(
				'link'      => $link,
				'productId' => $productId,
				'formId'    => $formId
			),
			'',
			array(
				'component' => 'com_redshop'
			)
		);

		$this->addReplace('{wishlist_button}', $wishListButton);
		$this->addReplace('{wishlist_link}', $wishListLink);
		$this->addReplace('{property_wishlist_link}', $wishListLink);
	}
}
