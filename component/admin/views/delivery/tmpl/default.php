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
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();

$db = jFactory::getDBO();
		
$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$lists = $this->lists;
$model = $this->getModel('order');
?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	if ((pressbutton=='edit')||(pressbutton=='remove'))
	{		 
	 	form.view.value="order_detail";
	}
	try 
	{
		form.onsubmit();
	}
	catch(e){}
	
	form.submit();
}
</script>

<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
 
	<table class="adminlist">
	<thead>
		<tr>
			 <th  >
				<?php echo JHTML::_('grid.sort', 'ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>					                  
			<th width="10%">
				<?php echo JHTML::_('grid.sort','NAME', 'firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="5%">
				<?php echo JTEXT::_( 'ADDRESS' ); ?>
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JTEXT::_( 'TELEPHONE' ); ?>	
			</th>
			<th width="10%">
				<?php echo JText::_('PRODUCT_QUANTITY'); ?>	
			</th>
			<th width="10%">
				<?php echo JTEXT::_( 'PRODUCT_NUMBER'); ?>	
			</th>
			<th width="10%">
				<?php echo JTEXT::_( 'PRODUCT' ); ?>	
			</th>
			<th width="10%">
				<?php echo JTEXT::_( 'PRODUCT_VOLUME' ); ?>	
			</th>
			<th width="10%">
				<?php echo JTEXT::_( 'SOLD_FROM_STOCKROOM' ); ?>	
			</th>
			<th width="10%">
				<?php echo JTEXT::_( 'ORDER_STATUS' ); ?>	
			</th>
		</tr>
	</thead>
	
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->orders ); $i < $n; $i++)
	{
		$row = &$this->orders[$i];
		 
        $row->id = $row->order_id;
        
        $query = "SELECT oi.*,p.product_volume FROM #__".TABLE_PREFIX."_order_item oi "
        		."LEFT JOIN #__".TABLE_PREFIX."_product p ON p.product_id = oi.product_id "
        		."WHERE order_id = '".$row->order_id."' ORDER BY delivery_time";
        $db->setQuery($query);
        $products = $db->loadObjectList();
        $total = count($products);
		for($j=0;$j<count($products);$j++)
		{
			$product= $products[$j];
			$query = "SELECT * FROM #__".TABLE_PREFIX."_container WHERE container_id = '".$product->container_id."'";
	        $db->setQuery($query);
	        if(!$container = $db->loadObject())
	        {
	        	$container->container_name = '';
	        }
	        if($j==0)
		    { 	?>
		  <tr>
		  	<td align="center" rowspan="<?php echo $total;?>"><?php echo $row->order_id; ?> </td>
		    <td rowspan="<?php echo $total;?>"><?php echo $row->firstname; ?>  <?php //echo $row->lastname; ?> </td>
		    <td rowspan="<?php echo $total;?>"><?php echo $row->address; ?></td>
		    <td rowspan="<?php echo $total;?>"><?php echo $row->phone; ?></td>
		    <td align="center"><?php echo $product->product_quantity;?></td>
		    <td align="center"><?php echo $product->order_item_sku;?></td>
		    <td><?php echo $product->order_item_name;?></td>
		    <td align="center"><?php echo $product->product_volume;?></td> 
		    <td><?php echo $container->container_name;?></td>
		    <td rowspan="<?php echo $total;?>"><?php echo $row->order_status_name;?></td>
		  </tr>
		 <?php } else { ?>
		  <tr>
		    <td align="center"><?php echo $product->product_quantity;?></td>
		    <td align="center"><?php echo $product->order_item_sku;?></td>
		    <td><?php echo $product->order_item_name;?></td>		 
		    <td align="center"><?php echo $product->product_volume;?></td> 
		    <td><?php echo $container->container_name;?></td>
		  </tr>
 <?php	 	}
		}
		$k = 1 - $k;
	}
	?>	
	<tfoot>
		<td colspan="13">
			<?php //echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="view" value="delivery" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>