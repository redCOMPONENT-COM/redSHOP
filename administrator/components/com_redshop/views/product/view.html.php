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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'extra_field.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'product.php' );
class productViewproduct extends JView
{

	var $_product = array();

	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$context ='product_id';
		$GLOBALS['productlist'] = array();
		$redTemplate = new Redtemplate();
		$extra_field = new extra_field();
		$adminproducthelper = new adminproducthelper();

		$list_in_products	=	$extra_field->list_all_field_in_product();


		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_PRODUCT') );
   	    $layout =  JRequest::getVar ( 'layout' );
   		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT' ), 'redshop_products48' );

   		if($layout!='importproduct' && $layout!='importattribute' && $layout!='listing' &&  $layout!='ins_product') {

			JToolBarHelper::customX( 'gbasefeed', 'gbase.png', 'gbase.png', JText :: _('COM_REDSHOP_GOOGLEBASE') , true );
			JToolBarHelper::custom( 'assignCategory', 'save.png', 'save_f2.png',JText :: _('COM_REDSHOP_ASSIGN_CATEGORY'), true );
			JToolBarHelper::custom( 'removeCategory', 'delete.png', 'delete_f2.png',JText :: _('COM_REDSHOP_REMOVE_CATEGORY'), true );
	 		JToolBarHelper::addNewX();
	 		JToolBarHelper::editListX();
	 		JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
			JToolBarHelper::deleteList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();

   		}

		if($layout == 'listing'){
			JToolBarHelper::back();
   		}

		$uri	=& JFactory::getURI();

		$category_id = $mainframe->getUserStateFromRequest( $context.'category_id',  'category_id', '' );

		if($category_id){
			$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'x.ordering' );
		}else{
			$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'p.product_id' );
		}
		//$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'product_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$search_field = $mainframe->getUserStateFromRequest( $context.'search_field',  'search_field', '' );
		$keyword = $mainframe->getUserStateFromRequest( $context.'keyword',  'keyword', '' );
		//$category_id = $mainframe->getUserStateFromRequest( $context.'category_id',  'category_id', '' );

//		$product_category = new product_category();
		$categories = $this->get('CategoryList');//$categories = $product_category->getCategoryListArray();
		$categories1 = array();
		foreach($categories as $key=>$value)
		{
			$categories1[$key]->id =  $categories[$key]->id;
			$categories1[$key]->parent_id =  $categories[$key]->parent_id;
			$categories1[$key]->title =  $categories[$key]->title;
			$treename = str_replace("&#160;&#160;&#160;&#160;&#160;&#160;"," ",$categories[$key]->treename);
			$treename = str_replace("<sup>"," ",$treename);
			$treename = str_replace("</sup>&#160;"," ",$treename);
			$categories1[$key]->treename =  $treename;
			$categories1[$key]->children =  $categories[$key]->children;
		}
		$temps = array();
		$temps[0]->id="0";
		$temps[0]->treename=JText::_('COM_REDSHOP_SELECT');
		$categories1=@array_merge($temps,$categories1);
		$lists['category'] 	= JHTML::_('select.genericlist',$categories1,  'category_id', 'class="inputbox" onchange="document.adminForm2.submit();" ', 'id', 'treename', $category_id );
	    
	    $product_sort=$adminproducthelper->getProductrBySortedList();
	    $product_sort_select = JRequest::getVar( 'product_sort',0);
	    $lists['product_sort'] =JHTML::_('select.genericlist',$product_sort, 'product_sort','class="inputbox"  onchange="document.adminForm2.submit();" ', 'value', 'text', $product_sort_select );
		
	    $lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
//		$total = & $this->get( 'Total');
		$products	= & $this->get( 'Data');

		$pagination = & $this->get( 'Pagination' );

		/*
	     * assign template
	     */
	    $templates	= $redTemplate->getTemplate('product');
	    $temps = array();
		$temps[0]->template_id="0";
		$temps[0]->template_name = JText::_('COM_REDSHOP_ASSIGN_TEMPLATE');
		$templates=@array_merge($temps,$templates);

	    $lists['product_template'] = JHTML::_('select.genericlist',$templates,'product_template','class="inputbox" size="1"  onchange="return AssignTemplate()" ','template_id','template_name',0);
	    // End

		$this->assignRef('list_in_products',		$list_in_products);
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