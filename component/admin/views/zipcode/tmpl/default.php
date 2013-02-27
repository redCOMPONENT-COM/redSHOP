<?php 
defined('_JEXEC') or die('Restricted access');

$option = JRequest::getVar('option','','request','string');
$filter = JRequest::getVar('filter');

?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) {submitbutton(pressbutton);}
submitbutton = function(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') || (pressbutton=='saveorder') ||(pressbutton=='orderup') ||(pressbutton=='orderdown') )
	 {		 
	  form.view.value="zipcode_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
</script>

<form action="<?php echo 'index.php?option='.$option; ?>" class="admin" id="adminForm" method="post" name="adminForm">
<table class="adminlist">
<thead>
<tr>
<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
<th width="10"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->fields);?>)"? /></th>
<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ZIPCODE'), 'z.zipcode', $this->lists['order_Dir'], $this->lists['order']);?></th>
<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_CITY_NAME'), 'z.city_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_NAME'), 'c.country_name', $this->lists['order_Dir'], $this->lists['order']);?></th>

<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_STATE_NAME'), 's.state_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
<th ><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'z.zipcode_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	</th>
</tr>
</thead>
<?php

 $k = 0;
for ($i=0, $n=count( $this->fields ); $i < $n; $i++)
{
	$row=&$this->fields[$i];
	$row->id = $row->zipcode_id;
	
	$link 	= JRoute::_( 'index.php?option='.$option.'&view=zipcode_detail&task=edit&cid[]='. $row->zipcode_id );
	 
	?> 
<tr class="<?php echo "row$k"; ?>">
	<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
	<td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
	<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_ZIPCODE' ); ?>"><?php echo $row->zipcode ?></a></td>
    <td><a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_ZIPCODE' ); ?>"><?php echo $row->city_name ?></a></td>
    <td align="center" width="10%"><?php echo $row->country_name; ?></td>
    <td align="center" width="10%"><?php echo $row->state_name; ?></td>
    <td align="center" width="10%"><?php echo $row->zipcode_id;?></td>
   
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
<input type="hidden" name="view" value="zipcode"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order'];?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>


