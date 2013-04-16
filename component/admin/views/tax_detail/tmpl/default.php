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
$editor = JFactory::getEditor();
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
jimport('joomla.html.pane');

$tmpl = JRequest::getVar('tmpl');

if ($tmpl == "component")
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="window.parent.SqueezeBox.close();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_RATES'); ?></div>
	</fieldset>
<?php
}
?>


<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.tax_rate.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_TAX_RATE_MUST_HAVE_VALUE', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">

				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_TAX_COUNTRY'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['tax_country'];?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_COUNTRY'), JText::_('COM_REDSHOP_TAX_COUNTRY'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_TAX_STATE'); ?>:
					</td>
					<td>
						<?php echo $this->lists['tax_state'];?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_STATE'), JText::_('COM_REDSHOP_TAX_STATE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_EU_COUNRYT_LBL'); ?>:
					</td>
					<td>
						<?php echo $this->lists['is_eu_country'];?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_EU_COUNRYT'), JText::_('COM_REDSHOP_EU_COUNRYT_LBL'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_TAX_RATE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="tax_rate" id="tax_rate" size="10" maxlength="10"
						       value="<?php echo $this->detail->tax_rate; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_RATE'), JText::_('COM_REDSHOP_TAX_RATE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>

			</table>
		</fieldset>

	</div>

	<div class="clr"></div>

	<input type="hidden" name="tax_group_id" value="<?php echo $this->detail->tax_group_id; ?>"/>
	<input type="hidden" name="tax_rate_id" value="<?php echo $this->detail->tax_rate_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="tax_detail"/>
	<?php
	if ($tmpl == "component")
	{
		?>
		<input type="hidden" name="tmpl" value="component"/>
	<?php }?>
</form>
