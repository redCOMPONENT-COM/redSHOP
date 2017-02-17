<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


class RedshopViewCart extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$redTemplate = Redtemplate::getInstance();
		$user        = JFactory::getUser();

		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$layout  = JRequest::getCmd('layout');

		if (!$cart)
		{
			$cart = array();
		}

		$Itemid = JRequest::getInt('Itemid');

		if (JRequest::getString('quotemsg') != "")
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, JRequest::getString('quotemsg')));
		}

		JHtml::script('com_redshop/common.js', false, true);

		if (!array_key_exists("idx", $cart) || (array_key_exists("idx", $cart) && $cart['idx'] < 1))
		{
			$cart_data = $redTemplate->getTemplate("empty_cart");

			if (count($cart_data) > 0 && $cart_data[0]->template_desc != "")
			{
				$cart_template = $cart_data[0]->template_desc;
			}
			else
			{
				$cart_template = JText::_("COM_REDSHOP_EMPTY_CART");
			}

			echo eval ("?>" . $cart_template . "<?php ");

			return false;
		}

		$Discount = $this->get('DiscountId');

		$data     = $this->get('data');

		if ($layout == 'change_attribute')
		{
			$this->setLayout('change_attribute');
		}

		$this->Discount = $Discount;
		$this->cart = $cart;
		$this->data = $data;
		parent::display($tpl);
	}
}
