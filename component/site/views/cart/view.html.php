<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('joomla.application.component.view');
JLoader::load('RedshopHelperProduct');

class RedshopViewCart extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$redTemplate = new Redtemplate;
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
			$app->redirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, JRequest::getString('quotemsg'));
		}

		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);

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
