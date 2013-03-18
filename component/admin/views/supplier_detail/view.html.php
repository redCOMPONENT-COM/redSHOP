<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


jimport('joomla.application.component.view');

class supplier_detailVIEWsupplier_detail extends JView
{
	function display($tpl = null)
	{

		require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT_DETAIL'), 'redshop_manufact48');

		$uri =& JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail =& $this->get('data');

		$model = $this->getModel('supplier_detail');

		$isNew = ($detail->supplier_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SUPPLIER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_manufact48');


		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{

			JToolBarHelper::cancel('cancel', 'Close');
		}


		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->assignRef('lists', $lists);
		$this->assignRef('detail', $detail);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}

}
