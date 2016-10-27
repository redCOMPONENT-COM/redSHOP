<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$uri = JURI::getInstance();
$url = $uri->root();
?>
<script>
	function getState2Code()
	{
		var filterData = {};
		filterData['<?php echo JSession::getFormToken(); ?>'] = 1;
		filterData['country_code'] = jQuery('#jform_country_code').val();

		console.log(filterData);

		jQuery.ajax({
			url: 'index.php?option=com_redshop&task=zipcode.ajaxGetState2Code',
			data: filterData,
			type: 'POST',
			dataType: 'text',
			beforeSend: function (xhr) {
				jQuery('#stateCodeBox').addClass('opacity-40');
				jQuery('#stateCodeBox .spinner').show();
			}
		}).done(function (data) {
			jQuery('#stateCodeWrapper').html(data);
			jQuery('#jform_state_code').select2({ width: 'resolve' });
		});
	}
</script>
<form action="index.php?option=com_redshop&task=zipcode.edit&id=<?php echo $this->item->id; ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data">
	<fieldset class="adminform">
		<?php foreach ($this->form->getFieldset('details') as $field) : ?>
			<?php if ($field->hidden) : ?>
				<?php echo $field->input;?>
			<?php elseif ($field->type == "RState2Code"): ?>
				<div id="stateCodeWrapper">
					<div class="control-group">
						<div class="control-label"><?php echo $field->label;?></div>
						<div class="controls" id="stateCodeBox"><?php echo $field->input;?></div>
					</div>
				</div>
			<?php else: ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label;?></div>
				<div class="controls"><?php echo $field->input;?></div>
			</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
