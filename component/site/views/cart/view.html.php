<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/product.php';

class cartViewcart extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$redTemplate = new Redtemplate;
		$user        = JFactory::getUser();

		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$layout  = JRequest::getVar('layout');

		if (!$cart)
		{
			$cart = array();
		}

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		if (JRequest::getVar('quotemsg') != "")
		{
			$app->Redirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, JRequest::getVar('quotemsg'));
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

		$data = $this->get('data');

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
