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
JHTMLBehavior::modal(); 
$option = JRequest::getVar('option');
$url = JUri::base();
$comment = JRequest::getVar('filter');
$model = $this->getModel ( 'catalog' );
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="catalog_detail";
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
<div id="editcell" style="background-color: ">	 
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->catalog ); ?>);" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'CATALOG_NAME', 'catalog_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>	 	
		 	<th>
				<?php echo JTEXT::_('MEDIA'); ?>
		 	</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'catalog_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->catalog ); $i < $n; $i++)
	{
		$row = &$this->catalog[$i];
        $row->id = $row->catalog_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=catalog_detail&task=edit&cid[]='. $row->catalog_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		

			?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
			<a href="<?php echo $link; ?>"><?php echo  $row->catalog_name; ?></a>
			</td>
			<td align="center"> 
			<?php $mediadetail = $model->MediaDetail($row->id);  ?>
			<a class="modal" href="index3.php?option=<?php echo $option;?>&amp;view=media&amp;section_id=<?php echo $row->id;?>&amp;showbuttons=1&amp;media_section=catalog&amp;section_name=<?php echo $row->catalog_name;?>" rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><img src="components/<?php echo $option;?>/assets/images/media16.png" align="absmiddle" alt="media" >(<?php  echo count($mediadetail);?>)</a>
			</td>			
			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo $row->catalog_id; ?>
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

<input type="hidden" name="view" value="catalog" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>