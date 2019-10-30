<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Cart view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewCart extends RedshopView
{
	/**
	 * @var array|null
	 */
	public $cart;

	/**
	 * @var array|null
	 */
	public $data;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$cart   = RedshopHelperCartSession::getCart();
		$layout = $app->input->getCmd('layout');
		$itemId = $app->input->getInt('Itemid');

		if ($app->input->getString('quotemsg') != "")
		{
			$app->redirect(
				JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false),
				$app->input->getString('quotemsg')
			);
		}

		JHtml::_('redshopjquery.framework');
		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/redshop.common.min.js', false, true);

		if (!array_key_exists("idx", $cart) || (array_key_exists("idx", $cart) && $cart['idx'] < 1))
		{
			$cartData = RedshopHelperTemplate::getTemplate("empty_cart");

			if (count($cartData) > 0 && $cartData[0]->template_desc != "")
			{
				$cartTemplate = $cartData[0]->template_desc;
			}
			else
			{
				$cartTemplate = JText::_("COM_REDSHOP_EMPTY_CART");
			}

			echo eval ("?>" . $cartTemplate . "<?php ");

			return false;
		}

		$data = $this->get('data');

		if ($layout == 'change_attribute')
		{
			$this->setLayout('change_attribute');
		}

		$this->cart = $cart;
		$this->data = $data;

		parent::display($tpl);
	}
}
