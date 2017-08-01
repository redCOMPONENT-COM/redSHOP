<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$user = JFactory::getUser();
$model = $this->getModel('statistic');

$start = $this->pagination->limitstart;
$end = $this->pagination->limit;
?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_FILTER') . ": " . $this->lists['filteroption'];?></td>
			</tr>
			<?php /*<tr><td><?php echo JText::_('COM_REDSHOP_STARTDATE');?></td>
		<td><?php echo JHTML::_('calendar', $this->startdate , 'startdate', 'startdate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19'));?></td></tr>
	<tr><td><?php echo JText::_('COM_REDSHOP_ENDDATE');?></td>
		<td><?php echo JHTML::_('calendar', $this->enddate , 'enddate', 'enddate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19'));?></td></tr>
	<tr><td colspan="2"><input type="submit" name="filter" value=<?php echo JText::_('COM_REDSHOP_SUBMIT');?> /></td></tr><?php */?>
		</table>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th align="center"><?php echo JText::_('COM_REDSHOP_HASH'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_SECTION'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_TOTAL_PAGEVIEWERS'); ?></th>
			</tr>
			</thead>
			<?php    $disdate = "";
			for ($i = $start, $j = 0; $i < ($start + $end); $i++, $j++)
			{
				if (!isset($this->pageviewer[$i]) || !is_object($this->pageviewer[$i]) )
				{
					break;
				}

				$row = $this->pageviewer[$i];

				if ($this->filteroption && $row->viewdate != $disdate)
				{
					$disdate = $row->viewdate;    ?>
					<tr>
						<td colspan="3"><?php echo JText::_("COM_REDSHOP_DATE") . ": " . $disdate;?></td>
					</tr>
				<?php
				}
				$secinfo = $model->getSectionDetail($row->section, $row->section_id);
				if (count($secinfo) > 0)
				{
					$link = JRoute::_('index.php?option=com_redshop&view=' . $row->section . '_detail&task=edit&cid[]=' . $secinfo->id);
					$sectionname = "<a href='" . $link . "'>" . $row->section . " :: " . $secinfo->sname . "</a>";
				}
				else
				{
					$sectionname = $row->section;
				}    ?>
				<tr>
					<td align="center"><?php echo $i + 1; ?></td>
					<td><?php echo $sectionname;?></td>
					<td align="center"><?php echo $row->totalpage;?></td>
				</tr>
			<?php }    ?>
			<tfoot>
			<td colspan="3">
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
