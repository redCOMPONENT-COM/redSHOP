<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', '.joom-box');

$url = JURI::base();
$comment = JFactory::getApplication()->input->get('filter');
$model = $this->getModel('catalog');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit')
			|| (pressbutton == 'remove')) {
			form.view.value = "catalog_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
	function clearreset() {
		var form = document.adminForm;
		form.filter.value = "";
		form.submit();
	}
</script>

<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell" style="background-color: ">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_CATALOG_NAME', 'catalog_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_MEDIA'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'catalog_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->catalog); $i < $n; $i++)
			{
				$row = $this->catalog[$i];
				$row->id = $row->catalog_id;
				$link = JRoute::_('index.php?option=com_redshop&view=catalog_detail&task=edit&cid[]=' . $row->catalog_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);



				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo  $row->catalog_name; ?></a>
					</td>
					<td align="center">
						<?php $mediadetail = $model->MediaDetail($row->id);  ?>
						<a class="joom-box"
						   href="index.php?tmpl=component&option=com_redshop&amp;view=media&amp;section_id=<?php echo $row->id; ?>&amp;showbuttons=1&amp;media_section=catalog&amp;section_name=<?php echo $row->catalog_name; ?>"
						   rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><img
								src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
								alt="media">(<?php  echo count($mediadetail);?>)</a>
					</td>
					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->catalog_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="catalog"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
