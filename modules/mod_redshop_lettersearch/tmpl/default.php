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

?>
<table cellspacing='5' cellpadding='5' border='0' width='100%'>
<tr>
<?php
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$letter = JRequest::getVar('letter');
$j=1;
for($i=0; $i<count($getcharacters); $i++){
	$moddiv = (int) ($j%$number_of_columns);
	if($letter==$getcharacters[$i]->chars){
		$active ='class="current"';
	}else{
		$active ='';
	}
?>
	<td <?php echo $active; ?> ><a href='<?php echo JRoute::_('index.php?option=com_redshop&view=category&letter='.$getcharacters[$i]->chars.'&modulename='.urlencode($module->title).'&layout=searchletter&Itemid='.$Itemid)?>'><?php echo $getcharacters[$i]->chars; ?></a></td>
	
	<?php
	if($moddiv == 0){ ?>
      	</tr><tr>
    <?php  }
    $j++;
}
?></table>