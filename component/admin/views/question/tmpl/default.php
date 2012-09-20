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

$config = new Redconfiguration();

$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$lists = $this->lists;
$ordering = ($this->lists['order'] == 'ordering');

//$model = $this->getModel('question');
?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton){
var form = document.adminForm;
	if (pressbutton)
    {
	    form.task.value=pressbutton;
	}

	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='remove')
		||(pressbutton=='publish')||(pressbutton=='unpublish')
		|| (pressbutton=='saveorder') || (pressbutton=='orderup') || (pressbutton=='orderdown') )
	{
	 	form.view.value="question_detail";
	}
	try
	{
		form.onsubmit();
	}
	catch(e){}

	form.submit();
}
</script>

<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" >
<div id="editcell">
<table class="adminlist" width="100%">
<tr><td valign="top" align="right" class="key">
	<?php echo JText::_( 'FILTER' ); ?>:
		<input type="text" name="filter" id="filter" value="<?php echo $filter;?>" onchange="document.adminForm.submit();">
	<?php echo JText::_('PRODUCT_NAME').": ".$this->lists['product_id']; ?>
	<button onclick="document.getElementById('filter').value='';document.getElementById('product_id').value='0';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
	</td></tr>
</table>
<table class="adminlist">
<thead>
	<tr><th width="5%"><?php echo JText::_( 'NUM' ); ?></th>
		<th width="5%" class="title">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->question ); ?>);"  /></th>
		<th class="title" width="15%">
			<?php echo JHTML::_('grid.sort', 'PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<th class="title" width="50%">
			<?php echo JHTML::_('grid.sort', 'QUESTION', 'question', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<th class="title" width="5%">
			<?php echo JText::_('ANSWERS'); ?></th>
		<th class="title" width="10%">
			<?php echo JHTML::_('grid.sort', 'USER_NAME', 'user_name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<th class="title" width="10%">
			<?php echo JHTML::_('grid.sort', 'USER_EMAIL', 'user_email', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<th class="order" width="10%">
				<?php  echo JHTML::_('grid.sort',  'ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php  if($ordering) { echo JHTML::_('grid.order',  $this->question ); }?></th>
		<th class="title" width="5%">
			<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<th width="5%">
			<?php echo JHTML::_('grid.sort', 'ID', 'question_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
	</tr>
</thead>
<?php
	$k = 0;
	for ($i=0,$n=count($this->question);$i<$n;$i++)
	{
		$row = &$this->question[$i];
        $row->id = $row->question_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=question_detail&task=edit&cid[]='.$row->id );
		$anslink= JRoute::_( 'index.php?option='.$option.'&view=question_detail&task=edit&cid[]='.$row->id.'#answerlists');
		//$anslink= JRoute::_( 'index.php?option='.$option.'&view=answer&parent_id[]='.$row->id );


		$product = $producthelper->getProductById($row->product_id);
		$answer = $producthelper->getQuestionAnswer($row->id,0,1);
		$answer = count($answer);

		$published 	= JHTML::_('grid.published', $row, $i );	?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
		<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
		<td align="center"><a href="<?php echo $link; ?>" title="<?php echo JText::_( 'VIEW_QUESTION' ); ?>"><?php echo $product->product_name; ?></a></td>
		<td><?php if(strlen($row->question)>50)
		{	echo substr($row->question,0,50)."..."; 	}
		else {	echo $row->question;}?></td>
		<td align="center"><a href="<?php echo $anslink;?>">( <?php echo $answer; ?> )</a></td>
		<td><?php echo $row->user_name; ?></td>
		<td><?php echo $row->user_email; ?></td>
		<td class="order">
			<span><?php echo $this->pagination->orderUpIcon( $i, true ,'orderup', 'Move Up', $ordering ); ?></span>
			<span><?php echo $this->pagination->orderDownIcon( $i, $n, true , 'orderdown', 'Move Down', $ordering ); ?></span>
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="text_area" style="text-align: center" <?php if(!$ordering){?> disabled="disabled"<?php }?> /></td>
		<td align="center"><?php echo $published;?></td>
		<td align="center"><?php echo $row->id; ?></td></tr>
	</tr>
<?php	$k = 1 - $k;
	}	?>
	<tr><td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
	</table>
</div>

<input type="hidden" name="view" value="question" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>