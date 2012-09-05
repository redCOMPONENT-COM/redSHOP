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

$option = JRequest::getVar('option','','request','string');
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="producttags_detail";
	 }
	try {
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
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->tags ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'TAGS_NAME', 't.tags_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'TAGS_USAGE', 'usag', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'TAGS_PRODUCTS', 'products', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'TAGS_USERS', 'users', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'TAGS_POPULARITY', 't.tags_counter', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>	 	
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'tags_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
					
		</tr>
	</thead>
	<?php
	
	$k = 0;
	for ($i=0, $n=count( $this->tags ); $i < $n; $i++)
	{
		$row = &$this->tags[$i];
        $row->id = $row->tags_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=producttags_detail&task=edit&cid[]='. $row->tags_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
			<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_TAGS' ); ?>"><?php echo $row->tags_name; ?></a>
			</td>
			<td align="center">
			<?php echo $row->usag; ?>
			</td>
			<td align="center">
			<?php echo $row->products; ?>
			</td>
			<td align="center">
			<?php echo $row->users; ?>
			</td>
			<td align="center">
				<?php echo $row->tags_counter; ?>			
			</td>				
			<td align="center" width="8%"><?php echo $published;?></td>
			<td align="center" width="5%"><?php echo $row->tags_id; ?></td>
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

<input type="hidden" name="view" value="producttags" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
