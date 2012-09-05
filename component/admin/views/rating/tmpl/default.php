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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );

$option = JRequest::getVar('option');
$model = $this->getModel ( 'rating' );
$config = new Redconfiguration();
$url = JUri::base();
$order_functions = new order_functions();
$comment = JRequest::getVar('comment_filter');
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove')||(pressbutton=='fv_publish')||(pressbutton=='fv_unpublish') )
	 {		 
	  form.view.value="rating_detail";
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
	form.comment_filter.value="";
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
	<table width="100%"> 
			<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'RATING_FILTER' ); ?>:
				<input type="text" name="comment_filter" id="comment_filter" value="<?php echo $comment; ?>">
				<input type="reset" name="reset" id="reset" value="<?php echo JText::_( 'RESET' ); ?>" onclick="return clearreset();"> 
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->ratings ); ?>);" />
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort', 'PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		 	<th width="20%">
				<?php echo JText::_( 'RATING_TITLE' ); ?>
			</th>
		 	<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'RATING_USERNAME', 'userid', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
		 	<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'RATING_DATE', 'time', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%">
				<?php echo JText::_( 'RATING' ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'FAVOURED', 'favoured', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'rating_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->ratings ); $i < $n; $i++)
	{
		$row = &$this->ratings[$i];
        $row->id = $row->rating_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=rating_detail&task=edit&cid[]='. $row->rating_id );
		$prodlink = JRoute::_( 'index.php?option='.$option.'&view=product_detail&task=edit&cid[]='. $row->product_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		$row->published = $row->favoured;
		$favoured 	= JHTML::_('grid.published', $row, $i,'tick.png','publish_x.png','fv_' );
		
		if($row->userid)
			$username = $order_functions->getUserFullname($row->userid);
		else
			$username= "";
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td>
			<a href="<?php echo $prodlink; ?>"><?php echo  $row->product_name; ?></a>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $title = substr($row->title,0,50); ?></a>
			</td>
			<td align="center">
				<?php if($username!="") echo $username; ?>
			</td>
			<td align="center"><?php echo $config->convertDateFormat($row->time);	?></td>
			
			<td class="order">
				<img src="<?php echo $url ?>components/<?php echo $option ?>/assets/images/star_rating/<?php echo $row->user_rating; ?>.gif" border="0">
			</td>
			<td align="center">
				<?php echo $favoured;?>
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo $row->rating_id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>	
<tfoot>
	<td colspan="10">
		<?php echo $this->pagination->getListFooter(); ?>
	</td>
</tfoot>
</table>
</div>

<input type="hidden" name="view" value="rating" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>