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

class answerViewanswer extends JView
{
	/*function __construct( $config = array())
	{
		 parent::__construct( $config );
	}*/

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_answer') );
		$model = $this->getModel('answer');

		$array = JRequest::getVar('parent_id',  0, '', 'array');
		$parent_id = (int)$array[0];

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_ANSWER_MANAGEMENT' ), 'redshop_question48' );
   		JToolBarHelper::addNewX();
   		JToolBarHelper::editListX();
   		JToolBarHelper::deleteList();
   		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();


		$uri	= JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order', 	  'question_date' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir', 'DESC' );
		$product_id = $mainframe->getUserStateFromRequest( $context.'product_id',		'product_id',	0 );

		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$question	= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

		$option= $model->getProduct();
		$optionsection = array();
		$optionsection[0]->product_id = 0;
		$optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');
		if(count($option)>0)
		{
			$optionsection = @array_merge($optionsection,$option);
		}
		$lists['product_id'] 	= JHTML::_('select.genericlist',$optionsection,  'product_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'product_id', 'product_name',  $product_id );

	    $this->assignRef('lists',		$lists);
	    $this->assignRef('parent_id',	$parent_id);
	  	$this->assignRef('question',	$question);
	    $this->assignRef('pagination',	$pagination);
	    $this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
	}
}
?>
