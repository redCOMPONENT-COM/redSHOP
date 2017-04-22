<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewQuotation extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$redconfig = Redconfiguration::getInstance();
		$uri       = JFactory::getURI();

		$Itemid  = $app->input->getInt('Itemid');
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$return  = $app->input->getString('return');

		if (!$return)
		{
			if ($cart['idx'] < 1)
			{
				$app->redirect(JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid));
			}
		}

		JHTML::script('administrator/components/com_redshop/assets/js/validation.js');

		$model  = $this->getModel('quotation');

		$detail = $model->getData();

		$this->detail = $detail;
		$this->request_url = $uri->toString();
		JFilterOutput::cleanText($this->request_url);

		parent::display($tpl);
	}
}
