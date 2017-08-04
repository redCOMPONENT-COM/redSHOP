<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$app = JFactory::getApplication();
$showbuttons = $app->input->getInt('showbuttons', 0);

?>

<?php if (!$showbuttons) : ?>
<script type="text/javascript">
	window.parent.SqueezeBox.close();
</script>
<?php endif; ?>

<script language="javascript" type="text/javascript">

	function showclicktellbox() {
		if (document.adminForm.template_section.value == "clickatell") {
			document.getElementById('clicktellbox').style.display = 'block';
		} else {
			document.getElementById('clicktellbox').style.display = 'none';
		}
	}

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.template_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_TEMPLATE_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.template_section.value == "0") {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_SELECT_SECTION', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}

	jQuery(document).ready(function($) {
		jQuery("input:radio[name='templateMode']").click(function(event) {

			document.adminForm.action += '&tmodeClicked=1';
			Joomla.submitbutton('apply');
		});
	});
</script>
<?php if ($showbuttons):	?>
	<p class="well">
		<button class="btn btn-success" onclick="submitbutton('save');">
			<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
		</button>
		<button class="btn btn-danger pull-right" type="button" onclick="window.parent.SqueezeBox.close();">
			<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
		</button>
	</p>
	<style type="text/css">
		legend
		{
			border-style:none;
			margin-bottom:0;
			width:auto;
		}
	</style>
<?php endif; ?>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TEMPLATE_NAME'); ?>
					<span class="star text-danger"> *</span>:</td>
					<td><input class="text_area" type="text" name="template_name" id="template_name" size="32"
					           maxlength="250" value="<?php echo $this->detail->template_name; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TEMPLATE_SECTION'); ?><span class="star text-danger"> *</span>
						:
					</td>
					<td><?php echo $this->lists['section']; ?></td>
				</tr>

				<?php if ('clicktell_sms_message' == $this->detail->template_section) :	?>
				<tr>
					<td colspan="2">
						<div id="clicktellbox">
							<table class="admintable table">
								<tr>
									<td width="100" align="right" class="key">
										<?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD'); ?>:
									</td>
									<td><?php echo $this->lists['payment_methods']; ?><?php echo $this->lists['shipping_methods']; ?>
									</td>
								</tr>

								<tr>
									<td valign="top" align="right"
									    class="key"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?>:
									</td>
									<td><?php echo $this->lists['order_status']; ?></td>
								</tr>

							</table>
						</div>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50" id="editor">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>
			<div class="col-sm-12">
				<div class="col-sm-7">
					<div class="row-fluid">
						<label class="text-info" for="templateMode">
							<?php echo JText::_('COM_REDSHOP_TEMPLATE_SWITCH_METHOD'); ?>
						</label>
						<?php
						$option = array();
						$option[] = JHTML::_('select.option', 'codemirror', JText::_('COM_REDSHOP_TEMPLATE_CODE_MIRRONR'));
						$option[] = JHTML::_('select.option', 'joomladefault', JText::_('COM_REDSHOP_TEMPLATE_JOOMLA_DEFAULT'));

						$templateMode = $app->input->getCmd('templateMode', 'codemirror');

						echo JHTML::_(
							'redshopselect.radiolist',
							$option,
							'templateMode',
							null,
							'value',
							'text',
							$templateMode
						);
						?>
					</div>
					<div class="row-fluid">
						<?php if ('codemirror' != $templateMode): ?>
							<?php $templateMode = null; ?>
						<?php endif; ?>
						<?php
						echo JFactory::getEditor($templateMode)
								->display(
									"template_desc",
									$this->detail->template_desc,
									'800px',
									'500px',
									'100',
									'20'
								);
						?>
					</div>
					<div class="row-fluid">
						<fieldset class="adminform">
							<legend><?php echo JText::_('COM_REDSHOP_AVAILABLE_TEXTLIBRARY_ITEMS'); ?></legend>
							<?php echo $this->loadTemplate('library_items');?>
						</fieldset>
					</div>
				</div>
				<div class="col-sm-5">
					<?php echo $this->loadTemplate('dynamic_fields');?>
				</div>
			</div>
		</fieldset>
	</div>
	<input type="hidden" name="template_id" value="<?php echo $this->detail->template_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="template_detail"/>
</form>
