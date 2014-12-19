<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$app        = JFactory::getApplication();
$Itemid     = JRequest::getInt('Itemid');
$loginlink  = 'index.php?option=com_redshop&view=login&Itemid=' . $Itemid;
$mywishlist = JRequest::getString('wishlist');

if ($mywishlist != '')
{
	$newuser_link = 'index.php?wishlist=' . $mywishlist . '&option=com_redshop&view=registration&Itemid=' . $Itemid;
}
else
{
	$newuser_link = 'index.php?option=com_redshop&view=registration&Itemid=' . $Itemid;
}

$params       = $app->getParams('com_redshop');
$returnitemid = $params->get('login', $Itemid);

?>
<form action="<?php echo JRoute::_($loginlink); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="5" height="40">
				<p><?php echo JText::_('COM_REDSHOP_LOGIN_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td>
				<label for="username">
					<?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" id="username" name="username"/>
			</td>
			<td>
				<label for="password">
					<?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" id="password" name="password" type="password"/>
			</td>

			<td><input type="submit" name="submit" class="button" value="<?php echo JText::_('COM_REDSHOP_LOGIN'); ?>">
			</td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5">
				<a href="<?php echo JRoute::_($newuser_link); ?>">
					<?php echo JText::_('COM_REDSHOP_CREATE_USER_LINK'); ?></a>&nbsp;/&nbsp;<a
					href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('COM_REDSHOP_FORGOT_PWD_LINK'); ?></a>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="setlogin">
	<input type="hidden" name="mywishlist" id="mywishlist" value="<?php echo JRequest::getString('wishlist'); ?>">
	<input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid; ?>">
	<input type="hidden" name="option" id="option" value="com_redshop"/>
</form>
