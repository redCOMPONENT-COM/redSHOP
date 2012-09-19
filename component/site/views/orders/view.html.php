<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class ordersVieworders extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
		
   	  	$user =& JFactory::getUser();
	   	// preform security checks
		if ($user->id==0)
		{
			$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid='.JRequest::getVar('Itemid'));	
			exit;
		}

		$option	= JRequest::getCmd('option');
		$layout	= JRequest::getCmd('layout','default');
		$this->setLayout($layout);
		
   		$params = &$mainframe->getParams($option);
   		$prodhelperobj = new producthelper();
   		$prodhelperobj->generateBreadcrumb();

		// Request variables
		$limit =  $mainframe->getUserStateFromRequest($option.'limit','limit',10,'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		$detail	=& $this->get('data');
		$this->pagination = & $this->get('Pagination');

		$this->assignRef('detail',		$detail);
		$this->assignRef('params',$params);
   		parent::display($tpl);
  	}
}