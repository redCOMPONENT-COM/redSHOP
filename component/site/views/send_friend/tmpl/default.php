<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$url = JURI::base();

$user     = JFactory::getUser();
$pid      = JFactory::getApplication()->input->getInt('pid');
$document = JFactory::getDocument();
JHtml::_('redshopjquery.framework');
JHtml::script('com_redshop/jquery.tools.min.js', false, true);
?>
<script type="text/javascript" language="javascript">var J = jQuery.noConflict();</script>
<script type="text/javascript" language="javascript">
	function friend_val() {
		var your_name = document.getElementById('your_name').value;
		var friends_email = document.getElementById('friends_email').value;
		var friends_name = document.getElementById('friends_name').value;

		if (your_name == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_YOUR_NAME');?>");
			return false;
		} else if (friends_name == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FRIENDS_NAME');?>");
			return false;
		} else if (friends_email == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FRIENDS_EMAIL_ADDRESS');?>");
			return false;
		} else
			return true;
	}
</script>
<div align="center">
	<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=send_friend'); ?>" method="post">
		<table>
			<tr>
				<td>&nbsp;
				</td>
				<th>
					<?php echo JText::_('COM_REDSHOP_SEND_TO_FRIEND'); ?>
				</th>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_REDSHOP_YOUR_NAME'); ?>
				</td>
				<td>
					<input type="text" name="your_name" id="your_name" value="<?php echo $user->name ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_REDSHOP_FRIENDS_NAME'); ?>
				</td>
				<td>
					<input type="text" name="friends_name" id="friends_name"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_REDSHOP_FRIENDS_EMAIL'); ?>
				</td>
				<td>
					<input type="text" name="friends_email" id="friends_email"/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="<?php echo JText::_('COM_REDSHOP_SEND_TO_FRIEND'); ?>"
					       onclick="return friend_val();"/>
				</td>
			</tr>
			<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>"/>
			<input type="hidden" name="task" id="task" value="sendmail"/>
			<input type="hidden" name="option" value="com_redshop"/>
			<input type="hidden" name="view" value="send_friend"/>
		</table>
	</form>
</div>
