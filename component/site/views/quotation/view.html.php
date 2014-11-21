<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

class RedshopViewQuotation extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$redconfig = new Redconfiguration;
		$uri       = JFactory::getURI();

		$Itemid  = JRequest::getInt('Itemid');
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$return  = JRequest::getString('return');

		if (!$return)
		{
			if ($cart['idx'] < 1)
			{
				$app->redirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid);
			}
		}

		JHTML::Script('validation.js', 'administrator/components/com_redshop/assets/js/', false);

		$model  = $this->getModel('quotation');

		$detail = $model->getData();

		$this->detail = $detail;
		$this->request_url = JFilterOutput::cleanText($uri->toString());

		parent::display($tpl);
	}
}
