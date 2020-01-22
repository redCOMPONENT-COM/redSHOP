<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsAjaxCartBox extends RedshopTagsAbstract
{
	public $tags = array(
		'{ajax_cart_box_title}',
		'{show_cart_text}',
		'{show_cart_button}',
		'{continue_shopping_button}'
	);

	public function init()
	{

	}

	public function replace()
	{
		$itemId = \RedshopHelperRouter::getCartItemId();

		if ($this->isTagExists('{ajax_cart_box_title}'))
		{
			$cartBoxTitle = RedshopLayoutHelper::render(
				'tags.common.title',
				array(
					'text' => JText::_('COM_REDSHOP_CART_SAVE'),
					'tag' => 'div',
					'class' => 'ajax_cart_box_title'
				)
			);

			$this->addReplace("{ajax_cart_box_title}", $cartBoxTitle);
		}

		if ($this->isTagExists("{show_cart_text}"))
		{
			$showCartText = $selectedAccessoriesHtml = RedshopLayoutHelper::render(
				'tags.common.title',
				array(
					'text' => JText::_('COM_REDSHOP_SHOW_CART_TEXT'),
					'tag' => 'div',
					'class' => 'show_cart_text'
				)
			);

			$this->addReplace("{show_cart_text}", $showCartText);
		}

		if ($this->isTagExists("{show_cart_button}"))
		{
			$viewButton = RedshopLayoutHelper::render(
				'tags.common.button',
				array(
					'text' => JText::_('COM_REDSHOP_VIEW_CART'),
					'class' => 'view_cart_button btn btn-primary',
					'attr' => 'name="viewcart" onclick="javascript:window.location.href=\'' . JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId) . '\'"'
				)
			);

			$this->addReplace("{show_cart_button}", $viewButton);
		}

		if ($this->isTagExists('{continue_shopping_button}'))
		{
			if (Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK') != '')
			{
				$shopMoreLink = JRoute::_(Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK'));
			}
			else
			{
				$shopMoreLink = JUri::root();
			}


			$continueButton = RedshopLayoutHelper::render(
				'tags.common.button',
				array(
					'text' => JText::_('COM_REDSHOP_CONTINUE_SHOPPING'),
					'class' => 'continue_cart_button btn',
					'attr' => 'name="continuecart" onclick="document.location=\'' . $shopMoreLink . '\'"'
				)
			);

			$this->addReplace('{continue_shopping_button}', $continueButton);
		}

		return parent::replace();
	}
}