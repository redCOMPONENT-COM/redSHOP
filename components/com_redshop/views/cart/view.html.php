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
jimport('joomla.application.component.view');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'product.php' );
class cartViewcart extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
		// Request variables
		$redTemplate = new Redtemplate();
		$user = &JFactory::getUser();

		$session =& JFactory::getSession();
		$cart = $session->get( 'cart');
		$layout	= JRequest::getVar('layout');

   		if(!$cart){
			$cart = array();
		}

		$option	= JRequest::getVar('option');
		$Itemid	= JRequest::getVar('Itemid');
		
		if(JRequest::getVar('quotemsg')!="")
		{
			$mainframe->Redirect( 'index.php?option='.$option.'&view=cart&Itemid='.$Itemid, JRequest::getVar('quotemsg') );
		}
		$document =& JFactory::getDocument();
		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
		if( !array_key_exists("idx",$cart) || (array_key_exists("idx",$cart) && $cart['idx']<1))
		{
			$cart_data = $redTemplate->getTemplate("empty_cart");
			if(count($cart_data)>0 && $cart_data[0]->template_desc!="")
			{
				$cart_template = $cart_data[0]->template_desc;
			} else {
				$cart_template = JText::_("COM_REDSHOP_EMPTY_CART");
			}
			echo eval ( "?>" . $cart_template . "<?php " );
			return false;
		}

		$Discount = & $this->get('DiscountId');

		$data	=& $this->get('data');

   		if($layout=='change_attribute')
		{
			$this->setLayout('change_attribute');
		}

		$this->assignRef('Discount',$Discount);
		$this->assignRef('cart',$cart);
		$this->assignRef('data',$data);
		parent::display($tpl);
  	}
}?>