<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::_('redshopjquery.framework');
JHtml::script('com_redshop/ajaxupload.js', false, true);

/**
 * $displayData extract
 *
 * @param   object  $rowData          Extra field data
 * @param   string  $required         Extra field required
 * @param   string  $uniqueId         Extra field unique Id
 * @param   string  $fieldCheck       Extra field check
 */
extract($displayData);
?>

<div class="userfield_input">
	<input 
		type="button"
		class="<?php echo $rowData->class; ?>"
		id="file<?php echo $rowData->name . '_' . $uniqueId; ?>"
		name="file<?php echo $rowData->name . '_' . $uniqueId; ?>"
		value="<?php echo JText::_('COM_REDSHOP_UPLOAD'); ?>"
		size="<?php echo $rowData->size; ?>"
		userfieldlbl="<?php echo $rowData->title; ?>"
		<?php echo $required; ?>
	/>
	<p>
		<?php echo JText::_('COM_REDSHOP_UPLOADED_FILE'); ?>
		<ol id="ol_<?php echo $rowData->name; ?>"></ol>
	</p>
</div>
<input 
	type="hidden"
	name="extrafieldname<?php echo $uniqueId ?>[]"
	id="<?php echo $rowData->name . '_' . $uniqueId; ?>"
	userfieldlbl="<?php echo $rowData->title; ?>"
	<?php echo $required; ?>
/>

<script type="text/javascript">
	jQuery.noConflict();
	new AjaxUpload(
		"file<?php echo $rowData->name . '_' . $uniqueId; ?>",
		{
			action: "index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
			data: {mname: "file<?php echo $rowData->name . '_' . $uniqueId; ?>"},
			name: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
			onSubmit: function(file, ext){
				jQuery('<?php echo $rowData->name ?>').text("<?php echo JText::_('COM_REDSHOP_UPLOADING') . 'file'; ?>");
				this.disable();
			},
			onComplete: function(file, response){
				jQuery('<li></li>').appendTo(jQuery('#ol_<?php echo $rowData->name; ?>')).text(response);
				var uploadfiles = jQuery('#ol_<?php echo $rowData->name; ?> li').map(function(){
					return jQuery(this).text();
				}).get().join(',');
				jQuery('#<?php echo $rowData->name . '_' . $uniqueId; ?>').val(uploadfiles);
				jQuery('#<?php echo $rowData->name; ?>').val(uploadfiles);
				this.enable();
			}
		}
	);
</script>
