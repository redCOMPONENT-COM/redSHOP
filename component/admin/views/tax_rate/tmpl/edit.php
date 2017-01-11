<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
?>

<script type="text/javascript">
	function updateState(countryValue)
	{
		(function($){
			var url = 'index.php?option=com_redshop&view=state&task=ajaxGetState';

			$.ajax({
				url: url,
				data: {
					country: countryValue,
					"<?php echo JSession::getFormToken() ?>": 1
				},
				method: "POST",
				cache: false
			})
			.success(function(data){
				if ($("#jform_tax_state").length)
				{
					$("#jform_tax_state").parent().html(data);
					$("#jform_tax_state").select2({width:"auto", dropdownAutoWidth:"auto"});
				}
				else if ($("#rs_state_jform_state_code").length)
				{
					$("#rs_state_jform_state_code").parent().html(data);
					$("#rs_state_jform_state_code").select2({width:"auto", dropdownAutoWidth:"auto"});
				}
			});
		})(jQuery);
	}
</script>

<form
	action="index.php?option=com_redshop&task=tax_rate.edit&id=<?php echo $this->item->id ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data"
>
	<fieldset class="adminform">
		<div class="row">
			<div class="col-sm-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
					</div>
					<div class="box-body">
						<?php echo $this->form->renderField('name') ?>
						<?php echo $this->form->renderField('tax_country') ?>
						<?php echo $this->form->renderField('tax_state') ?>
						<?php echo $this->form->renderField('tax_rate') ?>
						<?php echo $this->form->renderField('tax_group_id') ?>
						<?php echo $this->form->renderField('is_eu_country') ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value=""/>
	<?php echo $this->form->getInput('id') ?>
</form>
