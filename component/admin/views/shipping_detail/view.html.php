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

class shipping_detailViewshipping_detail extends JView
{
	function display($tpl = null)
	{
		$uri 		=& JFactory::getURI();
		$this->setLayout('default');
		$lists = array();
		$detail	=& $this->get('data');
		
		$isNew		= ($detail->id < 1);
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::title(   JText::_( 'SHIPPING' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_shipping48' );

		$adminpath=JPATH_ROOT.DS.'plugins';
		$shippingpath=$adminpath.DS.$detail->folder.DS.$detail->element.'.xml';
		$shippingcfg=$adminpath.DS.$detail->folder.DS.$detail->element.'.cfg.php';
		if(file_exists($shippingcfg))
		{
	        include_once ($shippingcfg);
        }

 	   	$myparams = new JParameter($detail->params,$shippingpath);
 	    $is_shipper = $myparams->get( 'is_shipper' );
     	$shipper_location = $myparams->get( 'shipper_location' );
		if($is_shipper)
 	    {
 	    	JToolBarHelper :: custom( 'shipping_rate', 'redshop_shipping_rates32' , JText::_('SHIPPING_RATE_LBL') , JText::_('SHIPPING_RATE_LBL'), false, false );
 	    } elseif($shipper_location)
 	    {
 	    	JToolBarHelper :: custom( 'shipping_rate', 'redshop_shipping_rates32' , JText::_('SHIPPING_LOCATION') , JText::_('SHIPPING_LOCATION'), false, false );
 	    }
	    JToolBarHelper::apply();
	    JToolBarHelper::save();
		JToolBarHelper::cancel();

        $lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
        
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}
?>