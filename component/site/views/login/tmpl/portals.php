<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.module.helper');

$app = JFactory::getApplication();
JHTML::_('behavior.tooltip');

$user = JFactory::getUser();
$params = $app->getParams('com_redshop');

$Itemid = JRequest::getInt('Itemid');

$returnitemid = $params->get('logout', $Itemid);

// Get redshop login module
if ($module = JModuleHelper::getModule('redshop_login'))
{
	// Get redshop login module params
	if ($module->params != "")
	{
		$moduleparams = new JRegistry($module->params);

		// Set return Itemid for logout
		$returnitemid = $moduleparams->get('logout', $returnitemid);
	}
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=login'); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<?php if (isset($this->ShopperGroupDetail[0])) : ?>
		<tr>
			<td>
				<h1><?php echo $this->ShopperGroupDetail[0]->shopper_group_name; ?></h1>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php echo $this->ShopperGroupDetail[0]->shopper_group_introtext; ?></strong>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td><input type="submit" name="submit" class="button btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_LOGOUT'); ?>">
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="logout">
	<input type="hidden" name="logout" id="logout" value="<?php echo $returnitemid; ?>">
</form>
