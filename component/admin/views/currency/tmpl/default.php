<?php 
defined('_JEXEC') or die('Restricted access');

$option = JRequest::getVar('option','','request','string');
$filter = JRequest::getVar('filter');
$currencyobject = JRequest::getVar( 'object' );

if($currencyobject=='cid')
{
	$frmlink 	= 'index3.php?option='.$option.'&object=cid';
}
else
{
	$frmlink 	= 'index.php?option='.$option;
}
?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton)
{
	var form = document.adminForm;
   	if (pressbutton)
    {
   	    form.task.value=pressbutton;
	}
	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') || (pressbutton=='saveorder') ||(pressbutton=='orderup') ||(pressbutton=='orderdown') )
	{		 
		form.view.value="currency_detail";
	}
	try {
		form.onsubmit();
	}
	catch(e){}
	form.submit();
}
</script>

<form action="<?php echo $frmlink; ?>" class="admin" id="admin" method="post" name="adminForm">
<table class="adminlist">
<thead>
	<tr>
	<th width="5%"><?php echo JText::_('NUM'); ?></th>
	<th width="5%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->fields);?>)"? /></th>
	<th width="50%"><?php echo JHTML::_('grid.sort', JText::_('CURRENCY_NAME'), 'currency_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
	<th width="10%"><?php echo JHTML::_('grid.sort', JText::_('CURRENCY_CODE_LBL'), 'currency_code', $this->lists['order_Dir'], $this->lists['order']);?></th>
	<th width="5%"><?php echo JHTML::_('grid.sort', JText::_('ID'), 'currency_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	</th>
	</tr>
</thead>
<?php $k = 0;
for ($i=0, $n=count( $this->fields ); $i < $n; $i++)
{
	$row=&$this->fields[$i];
	$row->id = $row->currency_id;
	
	if($currencyobject=='cid')
	{
		$link 	= JRoute::_( 'index2.php?option='.$option.'&view=currency_detail&task=edit&cid[]='. $row->currency_id.'&object=cid' );
	}
	else
	{
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=currency_detail&task=edit&cid[]='. $row->currency_id );
	}?> 
	<tr class="<?php echo "row$k"; ?>">
		<td align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
		<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
		<td><a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_CURRENCY' ); ?>"><?php echo $row->currency_name ?></a></td>
	    <td align="center"><?php echo $row->currency_code; ?></td>
	 	<td align="center"><?php echo $row->currency_id;?></td>
	</tr><?php
		$k = 1 - $k;
	}?>
<tfoot><td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
</table>
<input type="hidden" name="view" value="currency"/>
<input type="hidden" name="task" value=""/>
<?php if($currencyobject=='cid'){?>
<input type="hidden" name="object" value="<?php echo $currencyobject;?>"/>
<?php }?>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order'];?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>