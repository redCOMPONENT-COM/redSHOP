<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class ordersVieworders extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$user = JFactory::getUser();
		// preform security checks
		if ($user->id == 0)
		{
			$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));
			exit;
		}

		$option = JRequest::getCmd('option');
		$layout = JRequest::getCmd('layout', 'default');
		$this->setLayout($layout);

		$params        = & $mainframe->getParams($option);
		$prodhelperobj = new producthelper();
		$prodhelperobj->generateBreadcrumb();

		// Request variables
		$limit      = $mainframe->getUserStateFromRequest($option . 'limit', 'limit', 10, 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		$detail           =& $this->get('data');
		$this->pagination = & $this->get('Pagination');

		$this->assignRef('detail', $detail);
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
