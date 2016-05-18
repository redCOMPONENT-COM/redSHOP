<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $form        A JForm object
 * @param   int     $product_id  Id current product
 * @param   int     $modal       Flag use form in modal
 */
extract($displayData);

$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid', 0);
$category_id = $app->input->getInt('cid', 0);
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('redshopjquery.radiobutton');

$user = JFactory::getUser();
$userHelper = rsUserHelper::getInstance();

if ($user->id)
{
	if ($userInfo = $userHelper->getRedSHOPUserInfo($user->id))
	{
		$username = $userInfo->firstname . " " . $userInfo->lastname;
	}
	else
	{
		$username = $user->name;
	}

	$form->setValue('username', null, $form->getValue('username', null, $username));
	$form->setFieldAttribute('username', 'disabled', 'true', null);
	$form->setFieldAttribute('username', 'required', 'false', null);
	$form->setFieldAttribute('email', 'required', 'false', null);
}
?>
<script type="text/javascript" language="javascript">
	ratingSubmitButton = function (task) {
		var productRatingForm = document.getElementById('productRatingForm');

		if (document.formvalidator.isValid(productRatingForm)) {
			Joomla.submitform(task, productRatingForm);
		}
	};
</script>
<form name="productRatingForm" action="" method="post"
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
			<td>
				<?php echo $form->getInput('username'); ?>
			</td>
		</tr>
		<?php if ($user->guest): ?>
			<tr>
				<td valign="top" align="left">
					<?php echo $form->getLabel('email'); ?>
				</td>
				<td>
					<?php echo $form->getInput('email'); ?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td valign="top" align="left">
				<?php echo $form->getLabel('title'); ?>
			</td>
			<td>
				<?php echo $form->getInput('title'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<?php echo $form->getLabel('comment'); ?>
			</td>
			<td>
				<?php echo $form->getInput('comment'); ?>
			</td>
		</tr>
		<?php if (SHOW_CAPTCHA && $user->guest): ?>
			<tr>
				<td>
					<label for="jform_security_code" id="jform_security_code-lbl" class="required">
						<?php echo JText::_('COM_REDSHOP_CAPTCHA'); ?><span class="star">&nbsp;*</span>
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
			<td>
				<input type="submit" class="btn"
				   value="<?php echo JText::_('COM_REDSHOP_SEND_REVIEW'); ?>"
				   onclick="ratingSubmitButton('product_rating.submit')">
			</td>
		</tr>
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_FOOTER_TEXT'); ?></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="product_rating"/>
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="modal" value="<?php echo $displayData['modal']; ?>"/>
	<input type="hidden" name="product_id" value="<?php echo $product_id ?>"/>
	<input type="hidden" name="category_id" value="<?php echo $category_id ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
