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

jimport('joomla.application.component.model');

class integrationModelintegration extends JModel
{
	function __construct()
	{
		parent::__construct();

	}
	/*
	 *  download googlebase xml file
	 */
	function gbasedownload()
	{	
		$file_path =  JPATH_COMPONENT_SITE.DS."assets".DS."document".DS."gbase".DS."product.xml";
		if(!file_exists($file_path))
			return false;
			
		$xml_code = implode("",file($file_path));
				
		header("Content-Type: application/rss+xml");
		header('Content-Encoding: UTF-8');
		header('Content-Disposition: attachment; filename="product.xml"');
		echo  $xml_code;
		exit;
	}
}