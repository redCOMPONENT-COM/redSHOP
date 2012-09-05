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
$option = JRequest::getVar( 'option', '', 'request', 'string');
$shipping_data = $this->order_functions->getShippingAddress($this->detail->user_id);

$addlink = JRoute::_( 'index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id='.$this->detail->users_info_id.'&cid[]=0' );
?>
<div id="editcell">

<div align="right"><a href="<?php echo $addlink;?>" style="text-decoration: none;"><?php echo JText::_('COM_REDSHOP_ADD');?></a></div>
<table class="adminlist">
<thead>
<tr><th class="title"><?php echo JText::_('COM_REDSHOP_FIRST_NAME');?></th>
	<th><?php echo JText::_('COM_REDSHOP_LAST_NAME' );?></th>
	<th><?php echo JText::_('COM_REDSHOP_ID' );?></th>
	<th><?php echo JText::_('COM_REDSHOP_DELETE' );?></th></tr></thead>
<?php
	$x = 0;
	for($j=0,$n=count($shipping_data);$j<$n;$j++)
	{
		$row = &$shipping_data[$j];
		$link = JRoute::_('index.php?option='.$option.'&view=user_detail&task=edit&shipping=1&info_id='.$this->detail->users_info_id.'&cid[]='.$row->users_info_id);
		$link_delete = JRoute::_('index.php?option='.$option.'&view=user_detail&task=remove&shipping=1&info_id='.$this->detail->users_info_id.'&cid[]='.$row->users_info_id);	?>
		<tr class="<?php echo "row$x";?>">
			<td align="center"><a href="<?php echo $link;?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_USER' );?>"><?php echo $row->firstname;?></a></td>
			<td align="center"><?php echo $row->lastname;?></td>
			<td align="center"><?php echo $row->users_info_id;?></td>
			<td align="center"><a href="<?php echo $link_delete;?>" title="<?php echo JText::_('COM_REDSHOP_DELETE_SHIPPING_DETAIL' );?>"><?php echo JText::_('COM_REDSHOP_DELETE' );?></a></td></tr>
<?php
	$x = 1 - $x;
	}?>
</table>
</div>