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

class accountViewaccount extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe,$context;

   		$prodhelperobj = new producthelper();
   		$prodhelperobj->generateBreadcrumb();

   		$option = JRequest::getVar('option');
   		$Itemid = JRequest::getVar('Itemid');
   		$layout = JRequest::getVar('layout');
   		$params = &$mainframe->getParams($option);

		$document = &JFactory::getDocument();

   		$model = $this->getModel();
   		$user =& JFactory::getUser();

		$userdata = $model->getuseraccountinfo($user->id);
		if(!count($userdata)  && $layout!='mywishlist')
		{
			$msg =  JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER' );
			$mainframe->Redirect( "index.php?option=".$option."&view=account_billto&Itemid=".$Itemid , $msg );
		}
   		$layout = JRequest::getVar ('layout','default');
   		$mail = JRequest::getVar ('mail');
   		// preform security checks
		if (($user->id==0 && $layout!='mywishlist') || ($user->id==0 && $layout == 'mywishlist' && !isset($mail))) // give permission to send wishlist while not logged in )
		{
			$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid='.JRequest::getVar('Itemid'));	
			return;
		}


		if ($layout == 'mytags')
		{
			jimport('joomla.html.pagination');
			$this->setLayout('mytags');

			$remove = JRequest::getVar('remove',0);

			if ($remove == 1){

				$model->removeTag();
			}

			$maxcategory=$params->get('maxcategory',5);
			$limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $maxcategory, 5);
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
			$total =& $this->get('total');
			$pagination = new redPagination($total, $limitstart , $limit);
			$this->assignRef('pagination',		$pagination);
		}

   		if ($layout == 'mywishlist')
		{
			jimport('joomla.html.pagination');
			JHTML::Stylesheet('colorbox.css', 'components/com_redshop/assets/css/');  
		
			JHTML::Script('jquery.js', 'components/com_redshop/assets/js/',false);  
			JHTML::Script('jquery.colorbox-min.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
			$this->setLayout('mywishlist');

			$remove = JRequest::getVar('remove',0);

			if ($remove == 1){

				$model->removeWishlistProduct();
			}

			$maxcategory=$params->get('maxcategory',5);
			$limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $maxcategory, 5);
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
			$total =& $this->get('total');
			$pagination = new redPagination($total, $limitstart , $limit);
			$this->assignRef('pagination',		$pagination);
		}
   		if ($layout == 'compare')
		{
			$remove = JRequest::getVar('remove',0);

			if ($remove == 1){

				$model->removeCompare();
			}

			jimport('joomla.html.pagination');
			$this->setLayout('compare');
		}

		$this->assignRef('user',$user);
		$this->assignRef('userdata',$userdata);
		$this->assignRef('params',$params);

		# redCRM Template

		// helper object
		$helper = new redhelper();
		if($layout=="default" && $helper->isredCRM())
		{
			$tmplPath = JPATH_BASE.DS.'components'.DS.'com_redcrm'.DS.'views'.DS.'account'.DS.'tmpl';

			$this->addTemplatePath($tmplPath);

			parent::display('storemanagement');
		}
		# redCRM Template END

		parent::display($tpl);
  	}
}