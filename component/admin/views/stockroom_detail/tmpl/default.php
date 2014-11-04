<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTMLBehavior::modal();
$editor = JFactory::getEditor();

$date = JFactory::getDate();
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.stockroom_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_STOCKROOM_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}

</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm"
      id="adminForm">
	<?php

	// Get JPaneTabs instance
	$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
	$output = '';

	// Create Pane
	$output .= $myTabs->startPane('pane');
	// Create 1st Tab
	echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_STOCKROOM_INFO'), 'tab1');
	?>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="stockroom_name" id="stockroom_name" size="32"
						       maxlength="250" value="<?php echo $this->detail->stockroom_name; ?>"/>
					</td>
				</tr>

				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_MINIMUM_STOCK_AMOUNT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="min_stock_amount" id="min_stock_amount" size="32"
						       maxlength="250" value="<?php echo $this->detail->min_stock_amount; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_DELIVERY_TIME_IN'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->booleanlist; ?>
					</td>
				</tr>
				<?php

				if ($this->detail->delivery_time == 'Weeks')
				{
					$this->detail->min_del_time = (int) $this->detail->min_del_time / 7;
					$this->detail->max_del_time = (int) $this->detail->max_del_time / 7;
				}

				?>

				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_MINIMUM_DELIVERY_TIME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="min_del_time" id="min_del_time"
						       value="<?php echo $this->detail->min_del_time; ?>" size="32" maxlength="250"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_DELIVERY_TIME'), JText::_('COM_REDSHOP_MINIMUM_DELIVERY_TIME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_MAXIMUM_DELIVERY_TIME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="max_del_time" id="max_del_time"
						       value="<?php echo $this->detail->max_del_time; ?>" size="32" maxlength="250"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_DELIVERY_TIME'), JText::_('COM_REDSHOP_MAXIMUM_DELIVERY_TIME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_SHOW_ON_STOCKLIST'); ?>:
					</td>
					<td>
						<?php echo $this->lists['show_in_front']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>

			</table>
		</fieldset>
	</div>
	<div class="col50">

	</div>

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>

			<table class="admintable">
				<tr>
					<td>
						<?php echo $editor->display("stockroom_desc", $this->detail->stockroom_desc, '$widthPx', '$heightPx', '100', '20', '1');    ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php
	echo $myTabs->endPanel();

	echo $myTabs->endPane();
	?>
	<div class="clr"></div>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->stockroom_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="stockroom_detail"/>
</form>

<script type="text/javascript">
	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&stockroom_id=<?php echo $this->detail->stockroom_id;?>&alert=stoockroom&",
		varname: "input",
		json: true,
		shownoresults: false,
		callback: function (obj) {
			var selTo = document.adminForm.container_product;
			var chk_add = 1;
			for (var i = 0; i < selTo.options.length; i++) {
				if (selTo.options[i].value == obj.id) {
					chk_add = 0;
				}
			}
			if (chk_add == 1) {
				var newOption = new Option(obj.value, obj.id);
				selTo.options[selTo.options.length] = newOption;
			}
		}
	};
	var as_json = new bsn.AutoSuggest('input', options);


</script>
