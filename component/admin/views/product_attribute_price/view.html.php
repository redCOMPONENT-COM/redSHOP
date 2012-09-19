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
jimport( 'joomla.application.component.view' );

class product_attribute_priceViewproduct_attribute_price extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{	
		global $mainframe, $context;
		
		$db = JFactory::getDBO();
		$section_id = JRequest::getVar('section_id');
		$section = JRequest::getVar('section');
		$cid = JRequest::getVar('cid');
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_PRODUCT_PRICE') );
   		jimport('joomla.html.pagination');
   		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRODUCT_PRICE' ), 'redshop_vatrates48' );
   		
   		$sql = "SELECT * FROM #__redshop_product WHERE product_id = '$cid'";
 	 	$db->setQuery($sql);
	 	$product = $db->loadObject(); 
   		
   		$sql = "SELECT g.*,p.product_price,p.price_id,p.price_quantity_end,p.price_quantity_start FROM #__redshop_shopper_group g LEFT JOIN #__redshop_product_attribute_price p ON g.shopper_group_id = p.shopper_group_id   AND section_id = '$section_id'";
 	 	$db->setQuery($sql);
	 	$prices = $db->loadObjectList(); 
		$uri	=& JFactory::getURI();
 	 
		$this->assignRef('product',		$product);
		
    	$this->assignRef('prices',		$prices);
    	 
		$this->assignRef('section_id',	$section_id);   
		$this->assignRef('section',	$section);
		$this->assignRef('cid',	$cid);
  		 
   	 	$this->assignRef('request_url',	$uri->toString());   
   	 	 	
   	 	parent::display($tpl);
	}
}?>