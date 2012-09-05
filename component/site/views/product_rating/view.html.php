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
defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
class product_ratingViewproduct_rating extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
   		$producthelper = new producthelper();
		$pathway = &$mainframe->getPathway();
		$document = &JFactory::getDocument();

		$user = &JFactory::getUser();
   		// preform security checks
		if ($user->id==0)
		{
			echo JText::_('ALERTNOTAUTH_REVIEW');
			return;
		}
		$option = JRequest::getVar('option');
		$model = $this->getModel('product_rating');
		$userinfo = $model->getuserfullname($user->id);
   		$params = &$mainframe->getParams('com_redshop');
		$Itemid = JRequest::getVar('Itemid');
		$product_id=JRequest::getInt('product_id');
		$category_id=JRequest::getInt('category_id');
		$user = &JFactory::getUser();
		$model = $this->getModel('product_rating');
		$already_rated=$model->checkRatedProduct($product_id,$user->id);
		$rate = JRequest::getVar('rate');
		if($already_rated==1)
		{
			if($rate==1)
			{
				$msg=JText::_( 'YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');
				$link=JRoute::_('index.php?option='.$option.'&view=product&pid='.$product_id.'&cid='.$category_id.'&Itemid='.$Itemid);
				$mainframe->redirect($link,$msg);
			}
			else
			{
				echo  JText::_( 'YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');
				?>
				<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.document.getElementById( 'sbox-window' ).close();" /></span>
				<script>
				setTimeout("window.parent.document.getElementById( 'sbox-window' ).close()",2000);
				</script>
				<?php
				return;
			}
		}
   		$productinfo=$producthelper->getProductById($product_id);

   		//$pathway->addItem($productinfo->product_name,'');

		$this->assignRef('user',$user);
		$this->assignRef('userinfo',$userinfo);
		$this->assignRef('product_id',$product_id);
		$this->assignRef('category_id',$category_id);
		$this->assignRef('rate',$rate);
		$this->assignRef('productinfo',$productinfo);
		$this->assignRef('params',$params);

   		parent::display($tpl);
  	}
}