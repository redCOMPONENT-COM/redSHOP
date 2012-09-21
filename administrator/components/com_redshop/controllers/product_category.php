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

jimport( 'joomla.application.component.controller' );

class product_categoryController extends JController
{
	function assignCategory()
    {
		 JRequest::setVar ( 'hidemainmenu', 1 );
		 parent::display ();
	}

	function saveProduct_Category()
    {
		global $mainframe;
		$model = $this->getModel("product_category");
		if($model->saveProduct_Category())
			$msg = JText::_('COM_REDSHOP_CATEGORY_ASSIGNED_TO_PRODUCT_SUCCESSFULLY');
		else
			$msg = JText::_('COM_REDSHOP_ERROR_WHILE_ASSIGNING_CATEGORY_TO_PRODUCT');
		$mainframe->redirect("index.php?option=com_redshop&view=product",$msg);
	}

	function removeProduct_Category()
    {
		global $mainframe;
		$model = $this->getModel("product_category");
		if($model->removeProduct_Category())
			$msg = JText::_('COM_REDSHOP_CATEGORY_REMOVED_FROM_PRODUCT_SUCCESSFULLY');
		else
			$msg = JText::_('COM_REDSHOP_ERROR_WHILE_REMOVING_CATEGORY_FROM_PRODUCT');
		$mainframe->redirect("index.php?option=com_redshop&view=product",$msg);
	}
}
