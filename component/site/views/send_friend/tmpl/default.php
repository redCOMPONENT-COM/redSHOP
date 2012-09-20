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
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$url= JURI::base();

$user = JFactory::getUser();
$option = JRequest::getVar('option');
$pid    = JRequest::getInt('pid');
$document = & JFactory::getDocument();
JHTML::Script('jquery.tools.min.js', 'components/com_redshop/assets/js/',false);
?>
<script type="text/javascript" language="javascript">var J=jQuery.noConflict();</script>
<script type="text/javascript" language="javascript">
function friend_val(){
	var your_name = document.getElementById('your_name').value;
	var friends_email = document.getElementById('friends_email').value;
	var friends_name  = document.getElementById('friends_name').value;
	if(your_name == '')
	{
		alert("<?php echo JText::_('PLEASE_ENTER_YOUR_NAME');?>");
		return false;
	}else  if(friends_name == '')
	{
		alert("<?php echo JText::_('PLEASE_ENTER_FRIENDS_NAME');?>");
		return false;
	}else if(friends_email == '')
	{
		alert("<?php echo JText::_('PLEASE_ENTER_FRIENDS_EMAIL_ADDRESS');?>");
		return false;
	}else
	    return true;
}
</script>
<div align="center">
<form action="<?php echo JRoute::_('index.php?option='.$option.'&view=send_friend'); ?>" method="post">
<table >
	<tr>
		<td>&nbsp;
		</td>
		<th>
			<?php echo JText::_( 'SEND_TO_FRIEND' ); ?>
		</th>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'YOUR_NAME' ); ?>
		</td>
		<td>
			<input type="text" name="your_name" id="your_name" value="<?php echo $user->name?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'FRIENDS_NAME' ); ?>
		</td>
		<td>
			<input type="text" name="friends_name" id="friends_name" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'FRIENDS_EMAIL' ); ?>
		</td>
		<td>
			<input type="text" name="friends_email" id="friends_email" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit"  value="<?php echo JText::_( 'SEND_TO_FRIEND' );?>" onclick="return friend_val();" />
		</td>
	</tr>
	<input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
	<input type="hidden" name="task" id="task" value="sendmail" />
	<input type="hidden" name="option"  value="<?php echo $option; ?>" />
	<input type="hidden" name="view"  value="send_friend" />
</table>
</form>
</div>