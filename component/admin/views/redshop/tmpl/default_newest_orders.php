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
defined ( '_JEXEC' ) or die ( 'restricted access' );
$producthelper = new producthelper();
$option = JRequest::getVar('option');
?>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">

	<table class="adminlist" width="100%">
	<thead>
		<tr><th align="center"><?php echo JText::_( '#' ); ?></th>
			<th align="center"><?php echo JText::_( 'ORDER_ID' ); ?></th>
			<th align="center"><?php echo JText::_( 'FULLNAME' ); ?></th>
			<th align="center"><?php echo JText::_( 'PRICE' ); ?></th></tr></thead>
<?php
	$k = 0;
	for ($i=0,$n=count($this->neworders);$i<$n;$i++)
	{
		$row = &$this->neworders[$i];
        $row->id = $row->order_id;
        $link = "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=".$row->id;		?>
	    <tr class="<?php echo "row$k"; ?>" onclick="window.location.href='<?php echo $link;?>'">
	       <td align="center"> <a href="<?php echo $link;?>" style="color:black;"><?php echo $i+1; ?></a></td>

	      <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $row->id; ?></a></td>

		  <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $row->name; ?></a></td>
		  <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $producthelper->getProductFormattedPrice($row->order_total); ?></a></td>
		</tr>
<?php	$k = 1 - $k;
	}	?>

	</table>
</div>
<input type="hidden" name="view" value="statistic" />
<input type="hidden" name="layout" value="<?php echo $this->layout;?>" />
<input type="hidden" name="boxchecked" value="0" />
</form>
