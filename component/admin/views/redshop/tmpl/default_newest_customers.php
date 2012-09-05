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
$model = $this->getModel('redshop');
?>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm"  id="adminForm" >
<div id="editcell">

	<table class="adminlist" width="100%"><thead>
	<thead>
		<tr >
		    <th align="center"><?php echo JText::_('COM_REDSHOP_FULLNAME' ); ?></th>
		    <th align="center"><?php echo JText::_('COM_REDSHOP_NUMBER_OF_ORDERS' ); ?></th>
		    <th align="center"><?php echo JText::_('COM_REDSHOP_AVG_AMOUNT_OF_ORDERS' ); ?></th>
			<th align="center"><?php echo JText::_('COM_REDSHOP_TOTAL_AMOUNT_OF_ORDERS' ); ?></th>

	  </tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0,$n=count($this->newcustomers);$i<$n;$i++)
	{
		$row = &$this->newcustomers[$i];
        $row->id = $row->users_info_id;
		
        $order=$model->gettotalOrder($row->id);
        
        $order->order_total = ($order->order_total) ? $order->order_total : 0;
        $avg_amount=($order->tot_order>0) ? $order->order_total/$order->tot_order : 0;

		$link = "index.php?option=".$option."&view=user_detail&task=edit&cid[]=".$row->id;
		?>
	    <tr class="<?php echo "row$k"; ?>" onclick="window.location.href='<?php echo $link;?>'">
	      <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $row->firstname.' '.$row->lastname; ?></a></td>
	      <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $order->tot_order ?></a></td>
		  <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $producthelper->getProductFormattedPrice($avg_amount); ?></a></td>
		  <td align="center"><a href="<?php echo $link;?>" style="color:black;"><?php echo $producthelper->getProductFormattedPrice($order->order_total);?></a></td>
		</tr>
<?php	$k = 1 - $k;
	}	?>
	</table>
</div>
</form>