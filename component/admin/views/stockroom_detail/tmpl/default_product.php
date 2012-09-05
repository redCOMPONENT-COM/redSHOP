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

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
 $pane = @JPane::getInstance('sliders',array('startOffset'=>'-1'));
 
 		echo $pane->startPane( 'stat-pane' );
		 
?>
 <table class="adminlist" cellpadding="0" cellspacing="0">
 <tr>
 <th align="right">
 <button onclick="window.print();" ><?php echo JTEXT::_('PRINT');?></button>
 <button onclick="export_data();" ><?php echo JTEXT::_('EXPORT');?></button>
 </th>
 </tr>
 <tr><th><?php echo JText::_( 'STOCKROOM_CONTAINER_NAME' ); ?></th></tr>
 </table>
 <?php 
for($i=0;$i<count($this->lists);$i++)
{
		$model = $this->getModel ( 'stockroom_detail' );
		$product=$model->stock_product($this->lists[$i]->container_id);
		echo $pane->startPanel( $this->lists[$i]->container_name, $this->lists[$i]->container_name);
		echo '<table class="adminlist">';
		 for($p=0;$p<count($product);$p++)
		{
		echo'<tr>
				<td>'.$product[$p]->product_name.'
				</td>
			</tr>';
		}
echo'</table>';
 echo $pane->endPanel();
}
echo $pane->endPane();
?>
<script language="javascript" type="text/javascript">

function export_data(){
	
	document.location.href="index.php?option=com_redshop&view=stockroom_detail&task=export_data"; 
}

</script>