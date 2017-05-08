<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();

JHTML::_('behavior.tooltip');
$model = $this->getModel('newslettersubscr_detail');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.user_id.value == 0) {
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

			<table class="admintable table">
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_NEWSLETTER_SELECT_USER'); ?>
						<span class="star text-danger"> *</span>:
					</td>
					<td>
						<?php
						$userObject = new stdClass;
						$userObject->text = ($this->detail->user_id != 0) ? $model->getuserfullname2($this->detail->user_id) : $this->detail->name;
						$userObject->value = ($this->detail->subscription_id != 0) ? $this->detail->user_id : '';
						echo JHTML::_('redshopselect.search', $userObject, 'user_id',
							array(
								'select2.ajaxOptions' => array('typeField' => ', user:1'),
								'select2.options' => array(
									'placeholder' => JText::_('COM_REDSHOP_NEWSLETTER_SELECT_USER'),
									'events' => array('select2-selecting' => 'function(e) {document.getElementById(\'email\').value = e.object.volume;}')
								)
							)
						);
						?>
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
