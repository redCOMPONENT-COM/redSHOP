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
//ccc
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );
 $context = 'ddd';;
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'category.php' );
class stockroom_listingViewstockroom_listing extends JView {
	function __construct($config = array()) {
		parent::__construct ( $config );
	}

	function display($tpl = null) {
		global $mainframe, $context2;

//		$container_get = JRequest::getVar ( 'container_list','0' );
		$context2='p.product_id';
		$document = & JFactory::getDocument ();
		$document->setTitle ( JText::_('COM_REDSHOP_STOCKROOM_LISTING' ) );
		JToolBarHelper::title ( JText::_('COM_REDSHOP_STOCKROOM_LISTING_MANAGEMENT' ), 'redshop_stockroom48' );

	 	JToolBarHelper::custom('export_data','save.png','save_f2.png','Export Data',false);
		JToolBarHelper::custom('print_data','save.png','save_f2.png','Print Data',false);

	 	$stockroom_type = $mainframe->getUserStateFromRequest( $context2.'stockroom_type','stockroom_type','product');
		//$stock_type = $mainframe->getUserStateFromRequest( $context2.'stock_type','stock_type',0 );
//		$atttype = $mainframe->getUserStateFromRequest( $context2.'atttype','atttype','property' );
		$uri = & JFactory::getURI ();
		$filter_order = $mainframe->getUserStateFromRequest( $context2.'filter_order', 'filter_order', 'p.product_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context2.'filter_order_Dir', 'filter_order_Dir', '' );
		$search_field = $mainframe->getUserStateFromRequest( $context2.'search_field',  'search_field', '' );
		$keyword = $mainframe->getUserStateFromRequest( $context2.'keyword',  'keyword', '' );
		$category_id = $mainframe->getUserStateFromRequest( $context2.'category_id',  'category_id', '' );

		//stockroom type and attribute type
		$optiontype = array();

		$optiontype[]   = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optiontype[]   = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optiontype[]   = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
		//JHTML::_('select.option', 'product_attribute', JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE'));
		$lists['stockroom_type'] 		= JHTML::_('select.genericlist',$optiontype,  'stockroom_type', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text',  $stockroom_type);

//		$optionval = array();
//		$optionval[]   = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
//		$optionval[]   = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));
//		$lists['atttype'] 		= JHTML::_('select.genericlist',$optionval,  'atttype', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text',  $atttype);

		$product_category = new product_category();
		$categories = $product_category->getCategoryListArray();

		$temps = array();
		$temps[0]->category_id="0";
		$temps[0]->category_name=JText::_('COM_REDSHOP_SELECT');
		$categories=@array_merge($temps,$categories);
	    $lists['category'] 	= JHTML::_('select.genericlist',$categories,  'category_id', 'class="inputbox" onchange="getTaskChange();document.adminForm.submit();" ', 'category_id', 'category_name', $category_id );

		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
		$resultlisting = & $this->get ( 'Data' );
		$stockroom = & $this->get ( 'Stockroom' );

		$total = & $this->get ( 'Total' );

//		$container_list = & $this->get ( 'Container' );
//		$supps = array();
//		$supps[0]->value="0";
//		$supps[0]->text=JText::_('COM_REDSHOP_SELECT');
//		$container_list=@array_merge($supps,$container_list);
//		$lists['container_list'] 	= JHTML::_('select.genericlist',$container_list,  'container_list', 'class="inputbox"  size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $container_get );


		$pagination = & $this->get ( 'Pagination' );

		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'keyword',	$keyword);
		$this->assignRef ( 'search_field', $search_field);
		$this->assignRef ( 'resultlisting', $resultlisting );
		$this->assignRef ( 'stockroom', $stockroom );
		$this->assignRef ( 'stockroom_type', $stockroom_type );
//		$this->assignRef ( 'atttype', $atttype );
		$this->assignRef ( 'pagination', $pagination );
		$this->assignRef ( 'request_url', $uri->toString () );
		parent::display ( $tpl );
	}
}
?>