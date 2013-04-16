<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';

$option = JRequest::getVar('option');
$model = $this->getModel('rating');
$config = new Redconfiguration();
$url = JUri::base();
$order_functions = new order_functions();
$comment = JRequest::getVar('comment_filter');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'fv_publish') || (pressbutton == 'fv_unpublish')) {
			form.view.value = "rating_detail";
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
		form.comment_filter.value = "";
		form.submit();
	}
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_RATING_FILTER'); ?>:
					<input type="text" name="comment_filter" id="comment_filter" value="<?php echo $comment; ?>">
					<input type="reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
					       onclick="return clearreset();">
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th>
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->ratings); ?>);"/>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_RATING_TITLE'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_RATING_USERNAME', 'userid', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_RATING_DATE', 'time', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDSHOP_RATING'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FAVOURED', 'favoured', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'rating_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->ratings); $i < $n; $i++)
			{
				$row = & $this->ratings[$i];
				$row->id = $row->rating_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=rating_detail&task=edit&cid[]=' . $row->rating_id);
				$prodlink = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				$row->published = $row->favoured;
				$favoured = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'fv_');

				if ($row->userid)
					$username = $order_functions->getUserFullname($row->userid);
				else
					$username = "";

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="1%">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td width="1%">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td width="15%">
						<a href="<?php echo $prodlink; ?>"><?php echo  $row->product_name; ?></a>
					</td>
					<td width="35%">
						<a href="<?php echo $link; ?>"><?php echo $title = substr($row->title, 0, 50); ?></a>
					</td>
					<td width="15%">
						<?php if ($username != "") echo $username; ?>
					</td>
					<td width="15%">
						<a href="<?php echo $link; ?>"><?php echo $config->convertDateFormat($row->time);    ?></a>
					</td>

					<td class="order" width="12%">
						<img
							src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/<?php echo $row->user_rating; ?>.gif"
							border="0">
					</td>
					<td align="center" width="8%">
						<?php echo $favoured;?>
					</td>
					<td align="center" width="8%">
						<?php echo $published;?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->rating_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="rating"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>