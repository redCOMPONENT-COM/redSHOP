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
defined ('_JEXEC') or die ('restricted access');

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$url= JURI::base();

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$mid = JRequest::getInt('mid');
$redTemplate = new Redtemplate();

$document =& JFactory::getDocument();

$model = $this->getModel('manufacturers');
$manufacturers_template = $model->getManufacturertemplate("manufacturer");
 
for($i=0;$i<count($this->detail);$i++)
{
	if($this->detail[$i]->manufacturer_id==$mid)
	{
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=manufacturer_products&mid='.$this->detail[$i]->manufacturer_id.'&Itemid='.$Itemid);
		$manufacturer_name = "<a href='".$link."'>".$this->detail[$i]->manufacturer_name."</a>";
			
		$manufacturers_data = str_replace("{manufacturer_name}",$manufacturer_name,$manufacturers_template); 					
		$manufacturers_data = str_replace("{manufacturer_description}",$this->detail[$i]->manufacturer_desc,$manufacturers_data);
		echo "<div style='float:left;'>";
//		echo $manufacturers_data;

		$manufacturers_data = $redTemplate->parseredSHOPplugin($manufacturers_data);
		echo eval("?>".$manufacturers_data."<?php ");
		echo "</div>";
	}
}

?>
<!--Display Pagination start -->
<table cellpadding="0" cellspacing="0" align="center">
<tr>
	<td valign="top" align="center">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<br /><br />
	</td>
</tr>
<tr>
	<td valign="top" align="center">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</td>
</tr>
</table>
<!--Display Pagination End -->
