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

class wishlistViewwishlist extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
		// Request variables

   		$params = &$mainframe->getParams('com_redshop');
   		$task = JRequest::getVar('task', 'com_redshop');

   		$option	= JRequest::getVar('option', 'com_redshop');
		$Itemid	= JRequest::getVar('Itemid');
		$pid	= JRequest::getInt('product_id');
		$layout	= JRequest::getVar('layout');

		$config = new Redconfiguration();

		$pageheadingtag = '';


		$params = &$mainframe->getParams('com_redshop');
		$document = & JFactory::getDocument();
		JHTML::Stylesheet('colorbox.css', 'components/com_redshop/assets/css/');  
		
		JHTML::Script('jquery.js', 'components/com_redshop/assets/js/',false);  
		JHTML::Script('jquery.colorbox-min.js', 'components/com_redshop/assets/js/',false);
		//JHTML::Script('fetchscript.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/',false);
		$model =& $this->getModel("wishlist");

		$wishlist = $model->getUserWishlist();
		$wish_products = $model->getWishlistProduct();
		$session_wishlists = $model->getWishlistProductFromSession();
   		if($task=='viewwishlist')
   		{
   			$this->setlayout('viewwishlist');
   			$this->assignRef('wishlists',$wishlist);
   			$this->assignRef('wish_products',$wish_products);
   			$this->assignRef('wish_session',$session_wishlists);
   			$this->assignRef('params',$params);
   			parent::display($tpl);
   		}else if($task=='viewloginwishlist')
   		{
			$this->setlayout('viewloginwishlist');
   			$this->assignRef('wishlists',$wishlist);
   			$this->assignRef('params',$params);
   			parent::display($tpl);
   		}
   		else
   		{
   			$this->assignRef('wish_session',$session_wishlists);
			$this->assignRef('wishlist',$wishlist);
			$this->assignRef('params',$params);
   			parent::display($tpl);
   		}
  	}
}?>