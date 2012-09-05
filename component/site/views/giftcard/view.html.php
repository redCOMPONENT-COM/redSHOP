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

//require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'category.php');

require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php');

class giftcardViewgiftcard extends JView
{

   	function display ($tpl=null)
   	{
   		global $mainframe,$context;

   		// Request variables
		$option	= JRequest::getVar('option');
   		$params = &$mainframe->getParams($option);
		$document =& JFactory::getDocument();
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
		JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

		$pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');

		$model = $this->getModel('giftcard');
		$giftcard_template = $model->getGiftcardTemplate();
   		$detail = $this->get('data');

		$this->assignRef('detail',		$detail);
		$this->assignRef('lists',		$lists);
		$this->assignRef('template',	$giftcard_template);
		$this->assignRef('pageheadingtag',	$pageheadingtag);
		$this->assignRef('params',$params);
   		parent::display($tpl);
  	}
}?>