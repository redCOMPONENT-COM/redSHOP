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
defined('_JEXEC') or die('Restricted access');
 
$option = JRequest::getVar('option');
$uri =& JURI::getInstance();
$url= $uri->root();
$comment = JRequest::getVar('filter');
 
?> 
 

	<table cellpadding="2"  cellspacing="2" border="0" width="100%">
	<tr><td ></td></tr>
	<tr><td><table cellpadding="2"  cellspacing="2" border="0" width="200">
	<?php 
	 
	for($i=0;$i<count($this->sample);$i++)
	{
		
		$sample_data=$this->sample[$i];
	echo'<tr><th>'.JText::_('COM_REDSHOP_SAMPLE_NAME' );
	echo '</th><td>';
	echo $sample_data->sample_name;
	echo '</td></tr>';			
	echo'<tr><td></td>';
	if($sample_data->is_image==0)
	echo '<td width="100"><div style="width:200px:height:200px;background-color:'.$sample_data->code_image.';">&nbsp;&nbsp;<br><br><br></div></td>';
	else
	echo '<td><img src="'.$url.$sample_data->code_image.'" border="0" /></td>';
	
	echo '</tr>';
	}
	?></table></td></tr>
	
	</table>
 