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

class registrationViewregistration extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$user = JFactory::getUser ();
		$session = & JFactory::getSession ();
		$auth = $session->get ( 'auth' );
		if($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
		{
			$mainframe->Redirect( 'index.php?option='.$option.'&view=account&Itemid='.$Itemid);
		}

   		$params	= &$mainframe->getParams('com_redshop');
 		JHTML::Script('joomla.javascript.js', 'includes/js/',false);
 		JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/',false);
 		JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/',false);
 		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
 		JHTML::Script('jquery.metadata.js', 'components/com_redshop/assets/js/',false);
 		JHTML::Script('registration.js', 'components/com_redshop/assets/js/',false);
 		JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');

   		$field = new extraField();
		$lists['extra_field_user']=$field->list_all_field(7);  // field_section 7 : Customer Registration
		$lists['extra_field_company']=$field->list_all_field(8);  // field_section 8 : Company Address

		$this->assignRef('lists',$lists);
   		$this->assignRef('params',$params);
   		parent::display($tpl);
  	}
}
