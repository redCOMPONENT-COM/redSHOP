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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'category.php' );
class product_miniViewproduct_mini extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;
		
		$redTemplate = new Redtemplate();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_PRODUCT') );
   	    
		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'product_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$limitstart     = $mainframe->getUserStateFromRequest( $context.'limitstart',      'limitstart', 	  '0' );
		$limit = $mainframe->getUserStateFromRequest( $context.'limit',  'limit', '10' );
				
		$search_field = $mainframe->getUserStateFromRequest( $context.'search_field',  'search_field', '' );
		$keyword = $mainframe->getUserStateFromRequest( $context.'keyword',  'keyword', '' );
		$category_id = $mainframe->getUserStateFromRequest( $context.'category_id',  'category_id', '' );
		
		$product_category = new product_category();
		$categories = $product_category->getCategoryListArray();
		
		$temps = array();
		$temps[0]->category_id="0";
		$temps[0]->category_name=JText::_('COM_REDSHOP_SELECT');
		$categories=@array_merge($temps,$categories);
		
	//	echo $lists['categories'] =$categories;
	    $lists['category'] 	= JHTML::_('select.genericlist',$categories,  'category_id', 'class="inputbox" onchange="document.adminForm2.submit();"      ', 'category_id', 'category_name', $category_id );
		
		$lists['order'] = $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		 $total = & $this->get( 'Total');
		$products	= & $this->get( 'Data');
		
		$pagination = & $this->get( 'Pagination' );
		//$pagination = new JPagination( $total, $limitstart, $limit);
		
		    
	  
		
		$this->assignRef('keyword',		$keyword); 
		$this->assignRef('search_field',		$search_field); 
    	$this->assignRef('user',		JFactory::getUser());	
    	$this->assignRef('lists',		$lists);    
  		$this->assignRef('products',	$products); 		
    	$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>