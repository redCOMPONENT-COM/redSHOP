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

class newsletterViewnewsletter extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

 		$selected_product = JRequest::getVar('product','');
 		$n=$cid[0];
		$model = $this->getModel('newsletter');
		$subscribers=$model->listallsubscribers($n);

		$db = JFactory::getDBO();
		$product_category = new product_category();

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_NEWSLETTER') );

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_NEWSLETTER_MANAGEMENT' ), 'redshop_newsletter48' );

		JToolBarHelper::custom( 'send_newsletter','send.png','send.png','Send Newsletter');
		JToolBarHelper::cancel( 'close', 'Close' );

		$uri = JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order','filter_order','newsletter_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir','filter_order_Dir','');

		$lists['order'] 	= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$newsletters= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

		$oprand = JRequest::getVar('oprand','>');

		$optionoprand = array();
		$optionoprand[]   	= JHTML::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$optionoprand[]   	= JHTML::_('select.option', '>=', JText::_('COM_REDSHOP_GTOREQUEL'));
		$optionoprand[]   	= JHTML::_('select.option', '<=', JText::_('COM_REDSHOP_LTOREQUEL'));
		$optionoprand[]   	= JHTML::_('select.option', '=', JText::_('COM_REDSHOP_EQUAL_SIGN'));
		$lists['oprand'] 	= JHTML::_('select.genericlist',$optionoprand,  'oprand', 'class="inputbox" size="1" ' , 'value', 'text',  $oprand );

		$country_option = array();
		$country_option[]   	= JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT_COUNTRY'));

		$country = $model->getContry();

		$country_data = array_merge($country_option,$country);

		$country_value = JRequest::getVar('country','');

		$lists['country'] 	= JHTML::_('select.genericlist',$country_data,  'country[]', 'class="inputbox" multiple="multiple" size="4" ' , 'value', 'text',  $country_value );


		//$productcats = $model->getproductcats();
		$categories = array();

		$categories = $product_category->list_all("product_category[]",0,'',10,true,true);
		$lists['categories'] =$categories;


		$product_data = array();
		$product_data = $model->getProduct();

		$lists['product'] 	= JHTML::_('select.genericlist', $product_data,  'product[]', 'class="inputbox" multiple="multiple" size="8" ' , 'value', 'text', $selected_product);

		$shopper_option = array();
		$shopper_option[]   	= JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$shoppergroup= JRequest::getVar('shoppergroups','');
		$ShopperGrup = $model->getShopperGroup();
		$ShopperGroups = array_merge($shopper_option,$ShopperGrup);

		$lists['shoppergroups'] 	= JHTML::_('select.genericlist', $ShopperGroups,  'shoppergroups[]', 'class="inputbox" multiple="multiple" size="8" ' , 'value', 'text',$shoppergroup);

    	$this->assignRef('subscribers',	$subscribers);
    	$this->assignRef('lists',		$lists);
  		$this->assignRef('newsletters',	$newsletters);
    	$this->assignRef('pagination',	$pagination);
    	$this->assignRef('request_url',	$uri->toString());

    	$this->setLayout('preview');
    	parent::display($tpl);
  	}
}
?>
