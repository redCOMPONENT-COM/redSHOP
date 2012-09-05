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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$producthelper = new producthelper();
$redhelper = new redhelper();
$userhelper 	= new rsUserhelper();

$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$model = $this->getModel ('user');	?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton){
	var form = document.adminForm;
   	if (pressbutton)
   	{
   	   	form.task.value=pressbutton;
	}
	if(pressbutton=='add')
	{
<?php	$link = 'index.php?option=' . $option . '&view=user_detail';
	   	$link = $redhelper->sslLink($link);?>

		window.location = '<?php echo $link;?>';
		return;
	}
	else if ((pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish') ||(pressbutton=='remove')|| (pressbutton=='copy') )
	{
		form.view.value="user_detail";
	}
	try {
		form.onsubmit();
	}
	catch(e){}
	form.submit();
}
</script>

<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm">
<div id="editcell">
<table width="100%">
<tr>
	<td valign="top" align="left" class="key"><?php echo JText::_( 'USER_FILTER' ); ?>:
		<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>" onchange="document.adminForm.submit();">
		<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
		<button onclick="document.getElementById('filter').value='';document.getElementById('tax_exempt_request_filter').value='select';document.getElementById('spgrp_filter').value='0';document.getElementById('approved_filter').value='select';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
	</td>
	<td valign="top" align="left" class="key"><?php echo JText::_( 'SHOPPERGRP_FILTER' ); ?>:<?php echo $this->lists ['shopper_group']; ?></td>
	<td valign="top" align="left" class="key"><?php echo JText::_( 'TAX_EXEMPT_REQUESTED' ); ?>:<?php echo $this->lists ['tax_exempt_request']; ?></td>
</tr>
</table>

<table class="adminlist">
<thead>
<tr><th width="5%"><?php echo JText::_ ( 'NUM' );?></th>
	<th width="5%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count ( $this->user );?>);" /></th>
	<th class="title"><?php echo JHTML::_ ( 'grid.sort', 'FIRST_NAME', 'firstname', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
	<th class="title"><?php echo JHTML::_ ( 'grid.sort', 'LAST_NAME', 'lastname', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
	<!--<th class="title"><?php echo JHTML::_ ( 'grid.sort', 'CONTACT_PERSON', 'firstname', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
	--><th class="title"><?php echo JHTML::_ ( 'grid.sort', 'REGISTER_AS', 'is_company', $this->lists ['order_Dir'], $this->lists ['order'] ); ?></th>
 	<th class="title"><?php echo JHTML::_ ( 'grid.sort', 'USERNAME', 'username', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
 	<th class="title"><?php echo JHTML::_ ( 'grid.sort', 'SHOPPER_GROUP_NAME', 'shopper_group_id', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
	<th width="5%"><?php echo JText::_ ( 'CUSTOMER_SALES' ); ?></th>
	<th width="5%" nowrap="nowrap"><?php echo JHTML::_ ( 'grid.sort', 'ID', 'users_info_id', $this->lists ['order_Dir'], $this->lists ['order'] );?></th></tr>
</thead>
<?php
	$k = 0;
	for($i = 0, $n = count ( $this->user ); $i < $n; $i ++)
	{
		$row = &$this->user [$i];
		$row->id = $row->user_id;
//		$row->published = $row->approved;
//		if($this->lists ['shipping'])
//		{
//			$link = 'index.php?option='.$option.'&view=user_detail&task=edit&shipping=1&user_id='.$row->id.'&cid[]='.$row->users_info_id;
//		}
//		else
//		{
			$link = 'index.php?option='.$option.'&view=user_detail&task=edit&user_id='.$row->id.'&cid[]='.$row->users_info_id;
//		}
		$link = $redhelper->sslLink($link);
		if($row->is_company)
		{
			$iscompany = JText::_('USER_COMPANY');

		}
		else
		{
			$iscompany = JText::_('USER_CUSTOMER');

		}

		    //$contact_person = '';
			$fisrt_name = '<a href="'.$link.'" title="'.JText::_ ( 'EDIT_USER' ).'">'.$row->firstname.'</a>';
			$last_name = $row->lastname;
//		$approved = JHTML::_('grid.published',$row, $i );	?>

		<tr class="<?php echo "row$k";?>">
			<td align="center"><?php echo $this->pagination->getRowOffset ( $i );?></td>
			<td align="center"><?php echo JHTML::_ ( 'grid.id', $i, $row->users_info_id );?></td>
			<td><?php echo $fisrt_name;?></td>
			<td><?php echo $last_name;?> </td>
			<!--<td><?php //echo $contact_person;?></td>
			--><td align="center"><?php echo $iscompany?></td>
			<td><?php echo $row->username;?></td>
			<td><?php $shoppergroup = $userhelper->getShopperGroupList($row->shopper_group_id);if(count($shoppergroup)>0){echo $shoppergroup[0]->text;}?></td>
			<td align="center"><?php $totalsales = $model->customertotalsales($row->user_id);
			echo $producthelper->getProductFormattedPrice($totalsales);?></td>
			<td align="center" width="5%"><?php echo $row->users_info_id;?></td>
		</tr>
<?php
	$k = 1 - $k;
}	?>
<tfoot><td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
</table>
</div>
	<!--<input type="hidden" name="shipping" value="<?php echo $this->lists ['shipping'];	?>"  />-->
	<input type="hidden" name="view" value="user" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked"	value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>" />
</form>
