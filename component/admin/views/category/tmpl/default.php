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
$model = $this->getModel('category');
$category_main_filter = JRequest::getVar('category_main_filter');
$ordering = ($this->lists['order'] == 'ordering');
?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton){
	var form = document.adminForm;
	if (pressbutton)
    {
	    form.task.value=pressbutton;
	}
    if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') ||(pressbutton=='saveorder')||(pressbutton=='orderup') || (pressbutton=='orderdown') || (pressbutton=='copy') )
	{
		form.view.value="category_detail";
	}
	try {
		form.onsubmit();
	}
	catch(e){}

	if(pressbutton == 'remove'){
		var r = confirm('<?php echo JText::_("DELETE_CATEGORY")?>');
		if(r == true)	form.submit();
		else return false;
	}
	form.submit();
}

function AssignTemplate(){

	var form = document.adminForm;


	var templatevalue = document.getElementById('category_template').value;

	if(form.boxchecked.value==0){

		document.getElementById('category_template').value = 0;
		form.category_template.value = 0;
		alert('<?php echo JText::_('PLEASE_SELECT_CATEGORY');?>');

	}else{

		form.task.value = 'assignTemplate';

		if(confirm("<?php echo JText::_('SURE_WANT_TO_ASSIGN_TEMPLATE');?>")){

			//form.product_template.value = templatevalue;
			form.submit();
		}else{

			document.getElementById('category_template').value = 0;
			form.category_template.value = 0;
			return false;
		}
	}

}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td valign="top" align="left" class="key" width="30%">
			<?php echo JText::_( 'CATEGORY_FILTER' ); ?>:
				<input type="text" name="category_main_filter" id="category_main_filter" value="<?php echo $category_main_filter; ?>" onchange="document.adminForm.submit();">
		</td>
        <td width="">
        	<button onclick="document.adminForm.submit();"><?php echo JText::_( 'SEARCH' ); ?></button>
        </td>
        <td align="right">
        	<?php echo JText::_( 'ASSIGN_TEMPLATE' ); ?>:
			<?php echo $this->lists['category_template'];?>
		</td>
		<td valign="top" align="right" width="250">
					<?php echo JText::_( 'CATEGORY' ); ?>:
			<?php echo $this->lists['category']; ?>
		</td>
	</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'CATEGORY_NAME', 'category_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',  'CATEGORY_DESCRIPTION', 'category_description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>
		 	<th><?php echo JText::_('PRODUCTS'); ?></th>
		 	<th width="15%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php  if ($ordering) echo JHTML::_('grid.order',  $this->categories ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'category_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

		</tr>
	</thead>
<?php
	$k = 0;
	for ($i=0, $n=count( $this->categories ); $i < $n; $i++)
	{
		$row = &$this->categories[$i];
       	if(!is_object($row)) {
			break;
		}
        $row->id = $row->category_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=category_detail&task=edit&cid[]='. $row->category_id );
		$published 	= JHTML::_('grid.published', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
			<td>
				<?php 
				if($row->treename!="")
				{
				?>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_CATEGORY' ); ?>"><?php echo $row->treename; ?></a>
				<?php 
				} else {
				?>	
				<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_CATEGORY' ); ?>"><?php echo $row->category_name; ?></a>
				<?php 
				}
				?>
			</td>
			<td><?php	$shortdesc = substr(strip_tags($row->category_description),0,50);echo $shortdesc; ?></td>
			<td align="center" width="5%"><?php echo $model->getProducts($row->category_id); ?></td>
			<td class="order">
				<span><?php echo $row->orderup = $this->pagination->orderUpIcon( $i, ($row->category_parent_id == @$this->categories[$i-1]->category_parent_id),'orderup', 'Move Up', 1 ); ?></span>
				<span><?php echo $row->orderdown = $this->pagination->orderDownIcon( $i, $n, ($row->category_parent_id == @$this->categories[$i+1]->category_parent_id  ), 'orderdown', 'Move Down', 1 ); ?></span>
				<?php $ordering?$disable='':$disable='disabled="disabled"';	?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>"  <?php echo $disable;?> class="text_area" style="text-align: center" />
			</td>
			<td align="center" width="8%"><?php echo $published;?></td>
			<td align="center" width="5%"><?php echo $row->category_id; ?></td>
		</tr>
<?php	$k = 1 - $k;
	}	?>
<tfoot>
	<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
</tfoot>
</table>
</div>

<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>