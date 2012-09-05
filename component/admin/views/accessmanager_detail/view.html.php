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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );

class accessmanager_detailVIEWaccessmanager_detail extends JView
{
	function display($tpl = null)
	{
		$producthelper = new producthelper();
		$option = JRequest::getVar('option');
		$acl		= JFactory::getACL();
		$section = JRequest::getVar('section');
		$model = $this->getModel ( 'accessmanager_detail' );
		$accessmanager	= $model->getaccessmanager();

 		/**
		 * get groups
		 */
		$aclGroups = $acl->sort_groups();

		/**
		 * format groups
		 */
		$groups = $acl->format_groups( $aclGroups,'html',28 );

		JToolBarHelper::title(JText::_( 'ACCESS_MANAGER' ).': <small><small>[ '.$section.' ]</small></small>', 'redshop_catalogmanagement48' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
 		JToolBarHelper::cancel();

 		$this->assignRef('groups',$groups);

 		$this->assignRef('accessmanager',$accessmanager);

		parent::display($tpl);
	}
}	?>