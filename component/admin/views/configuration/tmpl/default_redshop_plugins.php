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
		<?php if (count($this->getinstalledplugins) > 0)
		{
			foreach ($this->getinstalledplugins as $getinstalledplugins)
			{

				?>
				<tr>
					<td><strong><?php echo JText::_($getinstalledplugins->name);?></strong></td>
					<td><?php echo (JFile::exists(JPATH_PLUGINS . '/redshop_payment/' . $getinstalledplugins->element . DS . $getinstalledplugins->element . '.php')) ? JText::_('COM_REDSHOP_INSTALLED') : JText::_('COM_REDSHOP_NOT_INSTALLED');?></td>

					<td align="center"><?php echo ($getinstalledplugins->enabled) ? "<img src='../administrator/components/com_redshop/assets/images/tick.png' />" : "<img src='../administrator/components/com_redshop/assets/images/publish_x.png' />";?></td>

				</tr>
			<?php
			}
		}?>
		</tbody>
	</table>
</div>

