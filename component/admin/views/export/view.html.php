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
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'category.php' );

class exportViewexport extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		$task = JRequest::getVar('task');
		$post = JRequest::get('post');
		$product_category = new product_category();
		$model = $this->getModel ( 'export' );
		if ($task == 'exportfile') {
			/* Load the data to export */
			$this->get('Data');
		}
		
		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_EXPORT') );
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_EXPORT_MANAGEMENT' ), 'redshop_export48' );
		
		JToolBarHelper :: custom( 'exportfile', 'redshop_export_export32.png' , JText::_('COM_REDSHOP_EXPORT') , JText::_('COM_REDSHOP_EXPORT'), false, false );
		$categories = $product_category->list_all("product_category[]",0,$productcats,10,true,true);
		$lists['categories'] =$categories;
		
		$manufacturers	= $model->getmanufacturers();
		$lists['manufacturers'] = JHTML::_('select.genericlist',$manufacturers,'manufacturer_id[]','class="inputbox"  multiple="multiple"  size="10" style="width: 250px;"> ','value','text',$detail->manufacturer_id);
		
        $this->assignRef('lists',$lists);
    	parent::display($tpl);
  }
}
?>