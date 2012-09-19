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
 
$option = JRequest::getVar('option','','request','string');
$showbuttons = JRequest::getVar('showbuttons','0');
$print_l=JRoute::_ ( 'index.php?tmpl=component&option=' . $option . '&view=container&id=0&showbuttons=1' );
if($showbuttons==1)
{
echo '<div align="right"><br><br><input type="button" class="button" value="Print" onClick="window.print()"><br><br></div>';
}
?>
<script language="javascript" type="text/javascript">

Joomla.submitbutton = function(pressbutton) {submitbutton(pressbutton);}
submitbutton = function(pressbutton){

	if(pressbutton=="print_da")
	{
		window.open("<?php echo $print_l;?>","print_window","status=1,toolbar=1");
		return false;
	}
	
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="container_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}

</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm" >
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_('COM_REDSHOP_NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->containers ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CONTAINER_NAME', 'container_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<th>
				<?php
				echo JHTML::_ ( 'grid.sort', 'STOCKROOM_NAME', 'stockroom_name', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>		
		 	<th width="1%">
				<?php echo JHTML::_('grid.sort','MINIMUM_DELIVERY_TIME', 'min_del_time', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.sort','MAXIMUM_DELIVERY_TIME', 'max_del_time', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.sort','DELIVERY_VOLUME', 'container_volume', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'container_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
					
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->containers ); $i < $n; $i++)
	{
		$row = &$this->containers[$i];
        $row->id = $row->container_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=container_detail&task=edit&cid[]='. $row->container_id );
		
		$published 	= JHtml::_('jgrid.published', $row->published, $i,'',1);		
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td width="30%">
			<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_CONTAINER' ); ?>"><?php echo $row->container_name; ?></a>
			</td>
			<td class="order" width="20%">
				<?php 
				if($row->stockroom_name)
				echo $row->stockroom_name;
				else
				echo '-';?>
				
			</td>
			<td width="15%">
				<?php echo $row->min_del_time; ?>
			</td>
			<td class="order" width="15%">
				<?php echo $row->max_del_time; ?>
				
			</td>
			<td class="order" width="12%">
				<?php echo $row->container_volume; ?>
				
			</td>
			<td align="center" width="8%">
				<?php echo $published;?>
			</td>
			<td align="center" width="5%">
				<?php echo $row->container_id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>	

<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="view" value="container" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
