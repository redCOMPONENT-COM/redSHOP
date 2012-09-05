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
 
$option = JRequest::getVar('option');
$url = JUri::base();
$comment = JRequest::getVar('filter');
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){ 
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="catalog_request";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
function clearreset()
{
	var form = document.adminForm;
	form.filter.value="";
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">	 
	<table class="adminlist">
	<thead>
		<tr>
			<th>
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			 <th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->catalog ); ?>);" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'NAME', 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'EMAIL', 'email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'REGISTRATORDATE', 'registerDate', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'REMINDER_1', 'remider_1', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'REMINDER_2', 'remider_2', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'REMINDER_3', 'remider_3', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'BLOCK', 'block', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th> 
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->catalog ); $i < $n; $i++)
	{
		$row = &$this->catalog[$i];
        $row->id = $row->catalog_user_id ;
        $row->published = $row->block ;
	 	$published 	= JHTML::_('grid.published', $row, $i );
	 	$reminder1 = $row->reminder_1?"<img src='images/tick.png'>":"<img src='images/publish_x.png'>";
	 	$reminder2 = $row->reminder_2?"<img src='images/tick.png'>":"<img src='images/publish_x.png'>";
	 	$reminder3 = $row->reminder_3?"<img src='images/tick.png'>":"<img src='images/publish_x.png'>";	?>
		<tr class="<?php echo "row$k"; ?>">
			<td width="1%">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			
			<td width="30%">
			<?php echo  $row->name; ?>
			</td>
			<td width="30%">
			<?php echo  $row->email; ?>
			</td>
			<td width="30%">
			<?php echo date("Y-m-d h:m:s",$row->registerDate); ?>
			</td>
			 <td align="center" width="8%">
				<?php echo $reminder1;?>
			</td>
			<td align="center" width="8%">
				<?php echo $reminder2;?>
			</td>
			<td align="center" width="8%">
				<?php echo $reminder3;?>
			</td>		
			<td align="center" width="8%">
				<?php echo $published;?>
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

<input type="hidden" name="view" value="catalog_request" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>