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
$app = JFactory::getApplication();
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$user = JFactory::getUser();
$params = $app->getParams($option);
$menu = JFactory::getApplication()->getMenu();

$returnitemid = $params->get('logout', $Itemid);
?>
<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=login'); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php
					if ($user->id > 0)
						echo JText::_('COM_REDSHOP_LOGOUT_DESCRIPTION');
					else
						echo JText::_('COM_REDSHOP_LOGOUT_SUCCESS');
					?></p>
			</td>
		</tr>
		<?php if ($user->id > 0) : ?>
			<tr>
				<td><input type="submit" name="submit" class="button"
				           value="<?php echo JText::_('COM_REDSHOP_LOGOUT'); ?>"></td>
			</tr>
		<?php endif; ?>
	</table>

	<input type="hidden" name="logout" id="logout" value="<?php echo $returnitemid; ?>">
	<input type="hidden" name="task" id="task" value="logout">
</form>