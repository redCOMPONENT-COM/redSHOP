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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class statistic {

	function statistic()
	{	
		statistic::reshop_visitors();
		statistic::reshop_pageview();
	}
	
	function reshop_visitors() {

		$sid = session_id();
		$db = $db= & JFactory :: getDBO();
		$user =  & JFactory::getUser();
		
		$q = "SELECT * "
			."FROM #__".TABLE_PREFIX."_siteviewer "
			."WHERE session_id = '".$sid."'";
		$db->setQuery($q);
		$data = $db->loadObjectList();
		$date = time();
		if(!count($data))
		{
			$query = "INSERT INTO #__".TABLE_PREFIX."_siteviewer "
					."(session_id, user_id, created_date) "
					."VALUES ('".$sid."', '".$user->id."','".$date."')";			
			$db->setQuery($query);
			if($db->query()){
				return true;	
			}
		}
	}
	
	function reshop_pageview() {

		$sid = session_id();
		$db = $db= & JFactory :: getDBO();
		$user =  & JFactory::getUser();
		$view = JRequest::getVar('view');
		$section = "";
		
		switch( $view ) 
		{
			case "product":
				$section = $view;
				$sectionid = JRequest::getVar('pid');
				break;
			case "category":
				$section = $view;
				$sectionid = JRequest::getVar('cid');
				break;
			case "manufacturers":
				$section = $view;
				$sectionid = JRequest::getVar('mid');
				break;		
		}
		
		if($section!="")
		{
			$q = "SELECT * "
				."FROM #__".TABLE_PREFIX."_pageviewer "
				."WHERE session_id = '".$sid."' "
				."AND section='".$view."' "
				."AND section_id='".$sectionid."' ";
			$db->setQuery($q);
			$data = $db->loadObjectList();
			$date = time();
			
			$hit = count($data)+1;
			if(!count($data))
			{
				$query = "INSERT INTO #__".TABLE_PREFIX."_pageviewer "
						."(session_id, user_id, section, section_id, created_date) "
						."VALUES ('".$sid."','".$user->id."','".$view."','".$sectionid."','".$date."')";
				$db->setQuery($query);
				if($db->query()){
					return true;	
				}
			}
		}
	}		
}
$statistic = new statistic();?>