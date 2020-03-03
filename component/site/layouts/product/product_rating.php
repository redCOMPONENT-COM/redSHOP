<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $form        A JForm object
 * @param   int     $productId  Id current product
 * @param   int     $modal       Flag use form in modal
 */
extract($displayData);

$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid', 0);
$category_id = $app->input->getInt('cid', 0);

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('redshopjquery.radiobutton');

$user = JFactory::getUser();

if ($user->id)
{
	if ($userInfo = RedshopHelperUser::getUserInformation($user->id))
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
	  id="productRatingForm" class="form-validate form-vertical" enctype="multipart/form-data">
	<div class="redshop-productrating">
		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('user_rating'); ?></label>
			<div class="col-xs-9">
				<div class="row"><?php echo $form->getInput('user_rating'); ?></div>
				</div>
			</div>

		<div class="row">
			<label class="col-xs-3"><?php echo $form->getLabel('images'); ?></label>
			<div class="col-xs-9"><?php echo $form->getInput('images'); ?></div>
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

		<?php if ($user->guest): ?>
		    <?php echo RedshopLayoutHelper::render('registration.captcha') ?>
        <?php endif; ?>
	</div>

	<div class="product_rating">
		<input type="submit" class="btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_SEND_REVIEW'); ?>" onclick="ratingSubmitButton('product_rating.submit')">
	</div>

	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="product_rating"/>
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="modal" value="<?php echo $displayData['modal']; ?>"/>
	<input type="hidden" name="product_id" value="<?php echo $productId ?>"/>
	<input type="hidden" name="category_id" value="<?php echo $category_id ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
