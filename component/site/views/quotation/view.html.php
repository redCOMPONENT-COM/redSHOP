<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class quotationViewquotation extends JView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();

		$redconfig = new Redconfiguration;
		$uri       = & JFactory::getURI();

		$option  = JRequest::getVar('option');
		$Itemid  = JRequest::getVar('Itemid');
		$session =& JFactory::getSession();
		$cart    = $session->get('cart');
		$return  = JRequest::getVar('return');

		if (!$return)
		{
			if ($cart['idx'] < 1)
			{
				$mainframe->Redirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid);
			}
		}

		JHTML::Script('validation.js', 'administrator/components/com_redshop/assets/js/', false);

		$model  = $this->getModel('quotation');

		$detail = $model->getData();

		$this->assignRef('detail', $detail);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
