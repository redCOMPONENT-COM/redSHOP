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
$userHelper = RedshopSiteUser::getInstance();

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

	<p><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_HEADER_TEXT'); ?></p>
	<div class="redshop-productrating">
		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('user_rating'); ?></label>
			<div class="col-xs-9">
				<div class="row">
					<div class="col-xs-4"><?php echo JText::_('COM_REDSHOP_GOOD'); ?></div>
					<div class="col-xs-4"><?php echo $form->getInput('user_rating'); ?></div>
					<div class="col-xs-4"><?php echo JText::_('COM_REDSHOP_EXCELLENT'); ?></div>
				</div>
			</div>
		</div>

		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('username'); ?></label>
			<div class="col-xs-9"><?php echo $form->getInput('username'); ?></div>
		</div>

		<?php if ($user->guest): ?>
		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('email'); ?></label>
			<div class="col-xs-9"><?php echo $form->getInput('email'); ?></div>
		</div>
		<?php endif; ?>

		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('title'); ?></label>
			<div class="col-xs-9"><?php echo $form->getInput('title'); ?></div>
		</div>

		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('comment'); ?></label>
			<div class="col-xs-9"><?php echo $form->getInput('comment'); ?></div>
		</div>

		<?php if (SHOW_CAPTCHA && $user->guest): ?>
		<div class="row">
			<label for="jform_security_code" id="jform_security_code-lbl" class="required col-xs-3">
				<?php echo JText::_('COM_REDSHOP_CAPTCHA'); ?><span class="star">&nbsp;*</span>
			</label>
			<div class="questionCaptcha">
				<div class="captchaImage">
					<img src="<?php echo JURI::base(true) . '/index.php?option=com_redshop&view=registration&task=captcha&tmpl=component&captcha=security_code&width=100&height=40&characters=5'; ?>" />
				</div>
				<div class="captchaField">
					<input class="inputbox required" required="required" id="jform_security_code" name="jform[security_code]" type="text" />
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<div class="product_rating">
		<input type="submit" class="btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_SEND_REVIEW'); ?>" onclick="ratingSubmitButton('product_rating.submit')">
	</div>

	<p><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_FOOTER_TEXT'); ?></p>

	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="product_rating"/>
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="modal" value="<?php echo $displayData['modal']; ?>"/>
	<input type="hidden" name="product_id" value="<?php echo $product_id ?>"/>
	<input type="hidden" name="category_id" value="<?php echo $category_id ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
