<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class country_detailVIEWcountry_detail extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);

	}

	function display($tpl = null)
	{
		$db = jFactory::getDBO();
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');
		$document = & JFactory::getDocument();
		$uri =& JFactory::getURI();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$lists = array();
		$detail =& $this->get('data');
		$isNew = ($detail->country_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{

			JToolBarHelper::cancel('cancel', 'Close');
		}
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_country_48');
		$model = $this->getModel('country_detail');

		$this->assignRef('detail', $detail);
		$this->assignRef('lists', $lists);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}