<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$editor = JFactory::getEditor();
JHTML::_('behavior.tooltip');
$model = $this->getModel('newsletter_detail');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_NEWSLETTER_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable table">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_NAME'); ?>
							<span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250"
						       value="<?php echo $this->detail->name; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_SUBJECT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="subject" id="subject" size="75" maxlength="250"
						       value="<?php echo $this->detail->subject; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SUBJECT'), JText::_('COM_REDSHOP_SUBJECT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_NEWSLETTER_BODY'); ?>:
						</label>
					</td>
					<td>
						<?php echo $editor->display("body", $this->detail->body, '$widthPx', '$heightPx', '100', '20');    ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_TEMPLATE'); ?>:
					</td>
					<td>
						<?php echo $this->lists['newsletter_template']; ?>&nbsp;
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TEMPLATE'), JText::_('COM_REDSHOP_TEMPLATE'), 'tooltip.png', '', '', false); ?>
						<?php
						if ($this->detail->template_id != 0)
						{
							?>
							<span style="width:10%;">
					<a class="joom-box"
					   href="index.php?tmpl=component&option=com_redshop&view=template_detail&task=edit&showbuttons=1&cid[]=<?php echo $this->detail->template_id; ?>"
					   rel="{handler: 'iframe', size: {x: 900, y: 500}}">
						<?php echo JText::_('COM_REDSHOP_EDIT_TEMPLATE'); ?>
					</a>
				</span>
						<?php } ?>
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
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER_FIXED_TAGS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td><?php echo Redtemplate::getTemplateValues('newsletter', 'mail'); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php
	$tags = $model->getnewslettertexts();
	if (count($tags) > 0)
	{
		?>
		<div class="col50">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER_TAGS_HINT'); ?></legend>
				<table class="admintable table">
					<?php
					//Geeting the Text library texts for the newsletter section
					for ($i = 0, $in = count($tags); $i < $in; $i++)
					{
						?>
						<tr>
							<td width="100" align="right" class="key">
								<?php echo $tags[$i]->text_desc; ?>:
							</td>
							<td>
								<?php echo "{" . $tags[$i]->text_name . "}"; ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			</fieldset>
		</div>
	<?php } ?>
	<div class="clr"></div>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->newsletter_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="newsletter_detail"/>
</form>
