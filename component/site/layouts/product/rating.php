<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid', 0);
$product_id = $app->input->getInt('product_id', 0);
$category_id = $app->input->getInt('category_id', 0);
$rate = $app->input->getInt('rate', 0);
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('redshopjquery.radiobutton');

$user = JFactory::getUser();
$form = $displayData['form'];

if ($user->id)
{
	$fullname = '';

	/*if (isset($this->userinfo) && $this->userinfo->firstname != "")
	{
		$fullname = $this->userinfo->firstname . " " . $this->userinfo->lastname;
	}*/

	$form->setValue('username', null, $form->getValue('username', null, $user->name));
}
?>
<script type="text/javascript" language="javascript">
	function validate() {
		var form = document.adminForm;
		var flag = 0;

		var selection = document.adminForm.user_rating;

		for (i = 0; i < selection.length; i++) {
			if (selection[i].checked == true) {
				flag = 1;
			}
		}

		if (flag == 0) {
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_RATE_THE_PRODUCT'); ?>');
			return false;
		}
		else if (form.comment.value == "") {
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_COMMENT_ON_PRODUCT'); ?>');
			return false;
		}
		else
		{
			return true;
		}

	}
</script>
<script type="text/javascript" language="javascript">
	Joomla.submitbutton = function (task) {
		var productRatingForm = document.getElementById('productRatingForm');

		if (document.formvalidator.isValid(productRatingForm)) {
			Joomla.submitform(task, productRatingForm);
		}
	};
</script>
<form name="productRatingForm" action="<?php echo JRoute::_('index.php?option=com_redshop'); ?>" method="post"
	  id="productRatingForm" class="form-validate form-vertical">
	<table cellpadding="3" cellspacing="3" border="0" width="100%">
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_HEADER_TEXT'); ?></td>
		</tr>
		<tr>
			<td valign="top" align="left" width="100">
				<?php echo $form->getLabel('user_rating'); ?>
			</td>
			<td>
				<table cellpadding="3" cellspacing="0" border="0">
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_GOOD'); ?></td>
						<td align="center">
							<?php echo $form->getInput('user_rating'); ?>
						</td>
						<td><?php echo JText::_('COM_REDSHOP_EXCELLENT'); ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<?php echo $form->getLabel('username'); ?>
			</td>
			<td colspan="8">
				<?php echo $form->getInput('username'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<?php echo $form->getLabel('title'); ?>
			</td>
			<td colspan="8">
				<?php echo $form->getInput('title'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<?php echo $form->getLabel('comment'); ?>
			</td>
			<td colspan="8">
				<?php echo $form->getInput('comment'); ?>
			</td>
		</tr>
		<?php if (SHOW_CAPTCHA && $user->guest): ?>
			<tr>
				<td>
					<label for="jform_security_code" id="jform_security_code-lbl" class="required">
						<?php JText::_('COM_REDSHOP_CAPTCHA'); ?><span class="star">&nbsp;*</span>
					</label>
				</td>
				<td>
					<div class="questionCaptcha">
						<div class="captchaImage">
							<img src="<?php echo JURI::base(true) . '/index.php?option=com_redshop&view=registration&task=captcha&tmpl=component&captcha=security_code&width=100&height=40&characters=5'; ?>" />
						</div>
						<div class="captchaField">
							<input class="inputbox required" required="required" id="jform_security_code" name="jform[security_code]" type="text" />
						</div>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td></td>
			<td colspan="8">
				<input type="submit" name="submit" class="btn validate"
				   value="<?php echo JText::_('COM_REDSHOP_SEND_REVIEW'); ?>"
				   onclick="Joomla.submitbutton('product_rating.save')">
			</td>
		</tr>
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_FOOTER_TEXT'); ?></td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="save"/>
	<input type="hidden" name="product_id" value="<?php echo $product_id ?>"/>
	<input type="hidden" name="category_id" value="<?php echo $category_id ?>"/>
	<input type="hidden" name="userid" value="<?php echo $user->id ?>"/>
	<input type="hidden" name="published" value="0"/>
	<input type="hidden" name="rate" value="<?php echo $rate ?>"/>
	<input type="hidden" name="time" value="<?php echo time() ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>