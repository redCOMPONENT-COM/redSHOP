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
/**
 * catalog Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class catalogController extends JController  
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	
	/*
	 * Method to send catalog
	 */
	function catalog_send()
	{
		$post = JRequest::get ( 'post' );		
		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar('option','','request','string');
		$model = $this->getModel ( 'catalog' );
		$post["registerDate"] = time();
		$post["email"] = $post["email_address"];
		$post["name"] = $post["name_2"];
		if ($row=$model->catalogStore($post)) 
		{
			$redshopMail = new redshopMail();
			$redshopMail->sendCatalogRequest($row);
			$msg = JText::_('COM_REDSHOP_CATALOG_SEND_SUCCSEEFULLY' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_CATALOG_SEND_SUCCSEEFULLY' );
		}
		$this->setRedirect ( 'index.php?option=' . $option . '&view=catalog&Itemid='.$Itemid, $msg );
	}
	
	/*
	 * Method to send catalog sample
	 */
	function catalogsample_send()
	{
		$post = JRequest::get ( 'post' );
		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar('option','','request','string');
		$model = $this->getModel ( 'catalog' );

		if(isset($post["sample_code"]))
		{
			$colour_id= implode(",",$post["sample_code"]);
			$post ['colour_id'] = $colour_id;
		}
		$post["registerdate"] = time();
		$post["email"] = $post["email_address"];
		$post["name"] = $post["name_2"];
		if ($row=$model->catalogSampleStore( $post )) 
		{
			$extra_field = new extra_field();
			$extra_field->extra_field_save($post,9,$row->request_id);
			$msg = JText::_('COM_REDSHOP_SAMPLE_SEND_SUCCSEEFULLY' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAMPLE_SEND_SUCCSEEFULLY' );
		}
		$this->setRedirect ( 'index.php?option=' . $option . '&view=catalog&layout=sample&Itemid='.$Itemid, $msg );
	}
}?>