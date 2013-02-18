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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'category.php');

class send_friendViewsend_friend extends JView
{

   	function display ($tpl=null)
   	{
   		global $mainframe;

		// Request variables
		$id		= JRequest::getVar('id', null, '', 'int');
		$option	= JRequest::getVar('option', 'com_redshop');
		$Itemid	= JRequest::getVar('Itemid');
		$pid	= JRequest::getInt('pid');


		$params = &$mainframe->getParams('com_redshop');

		$pathway =& $mainframe->getPathway();
		$document = & JFactory::getDocument();

		// Include Javascript

		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('json.js', 'components/com_redshop/assets/js/',false);
		//JHTML::Stylesheet('scrollable-minimal.css', 'components/com_redshop/assets/css/');
		JHTML::Stylesheet('scrollable-navig.css', 'components/com_redshop/assets/css/');
		$data	=& $this->get('data');

		$template =& $this->get('template');

		// Next/Prev navigation end

		$this->assignRef('data',		$data);
		$this->assignRef('template',	$template);

		$this->assignRef('params',$params);
   		parent::display($tpl);
  	}
}