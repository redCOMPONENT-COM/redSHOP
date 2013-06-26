<script language="javascript" type="text/javascript">
function submitform()
{
	if(document.adminForm.boxchecked.value > 0)
		document.adminForm.submit();
	else
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_WISHLIST')?>");
}

function checkValidation()
{
	if(trim(document.newwishlistForm.txtWishlistname.value)=="")
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_WISHLIST_NAME')?>");
	else
		document.newwishlistForm.submit();
}
</script>
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
defined ('_JEXEC') or die ('restricted access');
$session =& JFactory::getSession();
$add_products = $session->get( 'add_products', 'empty' );
$wishlist = $this->wishlist;
$flage = (count($wishlist) > 0 ) ? true : false;
$Itemid=JRequest::getVar('Itemid');
if($flage)
{
?>	
<form name="adminForm" method="post" action="">
	<table class="adminlist non-border">
		<thead>
		<tr>
			<th width="5%" align="center">
				<?php echo JText::_('COM_REDSHOP_NUM' ); ?>
			</th>
			<th width="5%" class="title" align="center">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $wishlist ); ?>);" />
			</th >
			<th class="title"  width="30%">
				<?php echo JText::_('COM_REDSHOP_WISHLIST_NAME'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count($wishlist ); $i < $n; $i++)
	{
		$row = &$wishlist[$i];
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo ( $i +1 ); ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('grid.id', $i, $row->wishlist_id ); ?>
			</td>
			<td>
				<?php echo $row->wishlist_name; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	<tr>
		<td></td>
		<td colspan="2"  align="left">
			<input type="button" value="<?php echo JText::_('COM_REDSHOP_ADD_TO_WISHLIST');?>" onclick="submitform();" />&nbsp;
			<input type="button" value="<?php echo JText::_('COM_REDSHOP_CANCEL');?>" onclick="javascript:history.go(-1)" />
		</td>
	</tr>
	</tbody>
	</table>
	<input type="hidden" name="view" value="subscription" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="layout" value="wishlist" />
	<input type="hidden" name="add_products" value="<?php echo $add_products; ?>"/>
	<input type="hidden" name="task" value="savewishlist" />
	<input type="hidden" name="option" value="com_redshop" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
	</form>
<?php 	
}
else
{
?>
	<form name="newwishlistForm" method="post" action="">
	<table>
		<tr>
			<td>
				<label for="txtWishlistname"><?php echo JText::_('COM_REDSHOP_WISHLIST_NAME');?> : </label>
			</td>
			<td>
				<input type="input" name="txtWishlistname" id="txtWishlistname" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" value="<?php echo JText::_('COM_REDSHOP_CREATE_SAVE');?>" onclick="checkValidation()" />&nbsp;
				<input type="button" value="<?php echo JText::_('COM_REDSHOP_CANCEL');?>" onclick="javascript:history.go(-1)" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="view" value="subscription" />
	<input type="hidden" name="option" value="com_redshop" />
	<input type="hidden" name="layout" value="wishlist" />
	<input type="hidden" name="add_products" value="<?php echo $add_products; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
	<input type="hidden" name="task" value="createsave" />
	</form>
<?php 
}
?>







