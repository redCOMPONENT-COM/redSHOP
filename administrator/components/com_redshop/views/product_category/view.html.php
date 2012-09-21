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
class product_categoryViewproduct_category extends JView
{

	var $_product = array();

	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_PRODUCT') );
   	    $task =  JRequest::getVar ( 'task' );
   		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT' ), 'redshop_products48' );

   		if($task=='assignCategory')
   			JToolBarHelper::custom( 'saveProduct_Category', 'save.png', 'save_f2.png',JText :: _('COM_REDSHOP_ASSIGN_CATEGORY'),false);
   		else
   			JToolBarHelper::custom( 'removeProduct_Category', 'delete.png', 'delete.png',JText :: _('COM_REDSHOP_REMOVE_CATEGORY'),false);

   		JToolBarHelper::back();

   		$model = $this->getModel("product_category");
   		$products = $model->getProductlist();

   		$product_category = new product_category();
		$categories = $product_category->getCategoryListArray();

		$temps = array();
		$temps[0]->category_id="0";
		$temps[0]->category_name=JText::_('COM_REDSHOP_SELECT');
		$categories=@array_merge($temps,$categories);

	    $lists['category'] 	= JHTML::_('select.genericlist',$categories,  'category_id[]', 'class="inputbox" multiple="multiple"', 'category_id', 'category_name' );

  		$this->assignRef('products',	$products);
    	$this->assignRef('lists',		$lists);
    	parent::display($tpl);
  	}
}
?>
