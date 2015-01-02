<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JLoader::load('RedshopHelperProduct');
$producthelper = new producthelper;

$user = JFactory::getUser();
$option = JRequest::getVar('option');
$start = $this->pagination->limitstart;
$end = $this->pagination->limit;
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
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
		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th align="center"><?php echo JText::_('COM_REDSHOP_HASH'); ?></th>
				<?php if ($this->filteroption)
				{ ?>
					<th width="60%" align="center"><?php echo JText::_('COM_REDSHOP_DATE'); ?></th>
				<?php }    ?>
				<th width="40%" align="center"><?php echo JText::_('COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER'); ?></th>
			</tr>
			</thead>
			<?php for ($i = $start, $j = 0; $i < ($start + $end) && isset($this->avgorderamount[$i]) && is_object($this->avgorderamount[$i]); $i++, $j++)
			{
				$row = $this->avgorderamount[$i]; ?>
				<tr>
					<td align="center"><?php echo $i + 1;?></td>
					<?php if ($this->filteroption)
					{ ?>
						<td align="center"><?php echo $row->viewdate;?></td>
					<?php }        ?>
					<td align="center"><?php echo $producthelper->getProductFormattedPrice($row->avg_order);//CURRENCY_SYMBOL.number_format($row->avg_order,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR);?></td>
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
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="layout" value="<?php echo $this->layout; ?>"/>
</form>
