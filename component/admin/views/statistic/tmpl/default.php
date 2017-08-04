<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$user   = JFactory::getUser();
$start  = $this->pagination->limitstart;
$end    = $this->pagination->limit;
?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_FILTER') . ": " . $this->lists['filteroption'];?></td>
			</tr>
		</table>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="60%" align="center"><?php
					if ($this->filteroption != 0)
					{
						echo JText::_('COM_REDSHOP_DATE');
					}
					else
					{
						echo JText::_('COM_REDSHOP_HASH');
					}
					?>
				</th>
				<th width="40%" align="center"><?php echo JText::_('COM_REDSHOP_TOTAL_VISITORS'); ?></th>
			</tr>
			</thead>
			<?php
			for ($i = $start, $j = 0; $i < ($start + $end); $i++, $j++)
			{
				if (isset($this->redshopviewer[$i]) === false)
				{
					continue;
				}

				$row = $this->redshopviewer[$i];

				if (!is_object($row))
				{
					break;
				}
			?>
				<tr>
					<td align="center">
					<?php
						if ($this->filteroption != 0 && isset($row->viewdate) === true)
						{
							echo $row->viewdate;
						}
						else
						{
							echo JText::_('COM_REDSHOP_HASH');
						}
					?>
					</td>
					<td align="center"><?php echo !empty($row->viewer) ? $row->viewer : 0;?></td>
				</tr>
			<?php
			}
			?>
			<tfoot>
			<td colspan="2">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?></td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="statistic"/>
	<input type="hidden" name="layout" value="<?php echo $this->layout; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
