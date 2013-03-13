<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<div id="config-document">
	<table class="adminlist">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_CHECK'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_RESULT');?></th>
			<th><?php echo JText::_('COM_REDSHOP_PUBLISHED');?></th>
		</tr>
		</thead>
		<tbody>
		<?php if (count($this->getinstalledmodule) > 0)
		{
			foreach ($this->getinstalledmodule as $getinstalledmodule)
			{

				?>
				<tr>
					<td><strong><?php echo $getinstalledmodule->element?></strong></td>
					<td><?php echo (is_null(JModuleHelper::getModule($getinstalledmodule->element))) ? JText::_('COM_REDSHOP_NOT_INSTALLED') : JText::_('COM_REDSHOP_INSTALLED');?></td>

					<td align="center"><?php echo ($getinstalledmodule->enabled) ? "<img src='../administrator/components/com_redshop/assets/images/tick.png' />" : "<img src='../administrator/components/com_redshop/assets//images/publish_x.png' />";?></td>

				</tr>
			<?php
			}
		}?>
		</tbody>
	</table>
</div>

