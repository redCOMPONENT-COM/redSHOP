<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTMLBehavior::modal();
$model = $this->getModel('newslettersubscr_detail');
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

		if (form.username.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_SELECT_USER', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SELECT_USER'); ?>:
					</td>
					<td>
						<input type="text" name="username" id="username"
						       value="<?php $this->detail->user_id != 0 ? $uname = $model->getuserfullname2($this->detail->user_id) : $uname = $this->detail->name;        echo $uname; ?>"
						       size="75"/><input type="hidden" name="user_id" id="userid"
						                         value="<?php if ($this->detail->subscription_id != 0) echo $this->detail->user_id; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_SELECT_USER'), JText::_('COM_REDSHOP_NEWSLETTER_SELECT_USER'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SELECT_NEWSLETTER'); ?>:
					</td>
					<td>
						<?php echo $this->lists['newsletters']; ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_SELECT_NEWSLETTER'), JText::_('COM_REDSHOP_NEWSLETTER_SELECT_NEWSLETTER'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->date)
							$datee = date("d-m-Y", $this->detail->date);
						else
							$datee = null;

						echo JHTML::_('calendar', $datee, 'date', 'date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_EMAIL'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="email" id="email" value="<?php echo $this->detail->email; ?>"
						       size="75"/>
						<?php  echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_SUBSCR_EMAIL'), JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_EMAIL'), 'tooltip.png', '', '', false); ?>
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
	<div class="clr"></div>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->subscription_id; ?>"/>
	<input type="hidden" name="date" id="date" value="<?php echo time(); ?>">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="newslettersubscr_detail"/>
</form>
<script type="text/javascript">

	var options = {
		script: "index.php?tmpl=component&&option=com_redshop&view=search&user=1&json=true&",
		varname: "input",
		json: true,
		shownoresults: false,
		callback: function (obj) {
			document.getElementById('email').value = obj.volume;
			document.getElementById('userid').value = obj.id;
		}
	};
	var as_json = new bsn.AutoSuggest('username', options);

</script>
