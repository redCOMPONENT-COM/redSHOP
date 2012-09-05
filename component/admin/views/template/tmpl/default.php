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
$filter = JRequest::getVar('filter');
$redtemplate = new Redtemplate();
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}

	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove')|| (pressbutton=='copy') )
	 {
	  form.view.value="template_detail";
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

	<table width="100%">
			<tr>
			<td>
			<table width="100%">
				<tr>
					<td valign="top" align="left" class="key">
						<?php echo JText::_( 'TEMPLATE_NAME' ); ?>:
							<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>" onchange="document.adminForm.submit();">
						<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
						<button onclick="document.getElementById('filter').value='';document.getElementById('template_section').value=0;this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
					</td>
				</tr>
			</table>
			</td>
			<td valign="top" align="right" class="key">

					<?php echo JText::_( 'TEMPLATE_SECTION' ); ?>:

			<?php echo $this->lists['section']; ?>
			</td>
		</tr>
	</table>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->templates ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'TEMPLATE_NAME', 'template_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>

			</th>
			<th>
				<?php echo JHTML::_('grid.sort',  'SECTION', 'template_section', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>

			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'template_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->templates ); $i < $n; $i++)
	{
		$row = &$this->templates[$i];
        $row->id = $row->template_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=template_detail&task=edit&cid[]='. $row->template_id );

		$published 	= JHTML::_('grid.published', $row, $i );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
			<?php echo @JHTML::_('grid.checkedout', $row, $i ); ?>
			</td>
			<td>
			<?php

			if (  JTable::isCheckedOut($this->user->get('id'), $row->checked_out ) ) {
				echo $row->template_name;
			} else {
			?>
				<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_TEMPLATES' ); ?>"><?php echo $row->template_name; ?></a>
			<?php
			}
			?>
			</td>
			<td>
				<?php echo $redtemplate->getTemplateSections($row->template_section); ?>
			</td>

			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo $row->template_id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>

<tfoot>
		<td colspan="9">
			<?php  echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="view" value="template" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
