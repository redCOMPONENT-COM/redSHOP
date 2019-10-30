<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::_('redshopjquery.framework');
/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/ajaxupload.min.js', false, true);

/**
 * $displayData extract
 *
 * @var   array   $displayData   Layout data.
 * @var   object  $rowData       Extra field data
 * @var   string  $required      Extra field required
 * @var   string  $uniqueId      Extra field unique Id
 * @var   array   $fieldCheck    Extra field check
 */
extract($displayData);
$http_referer = JFactory::getApplication()->input->server->getString('HTTP_REFERER', '');
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

<?php if (strpos($http_referer, 'administrator') !== false
		&& (strpos($http_referer, 'view=order_detail') !== false
			|| strpos($http_referer, 'view=addorder_detail') !== false
			|| strpos($http_referer, 'view=quotation') !== false
			|| strpos($http_referer, 'view=quotation_detail') !== false
			|| strpos($http_referer, 'view=addquotation_detail') !== false)
		): ?>
<script type="text/javascript" id="inner-ajax-script_<?php echo $uniqueId ?>">
	(function($) {
		new AjaxUpload(
			"file<?php echo $rowData->name . '_' . $uniqueId; ?>",
			{
				action: "<?php echo JUri::root() ?>index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
				data: {
					mname: 	   "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
					fieldName: "<?php echo $rowData->name ?>",
					uniqueOl:  "<?php echo $rowData->name . '_' . $uniqueId; ?>"
				},
				name: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
				onSubmit: function(file, ext){
					jQuery('#<?php echo $rowData->name ?>').text("<?php echo JText::_('COM_REDSHOP_UPLOADING') . 'file'; ?>");
				},
				onComplete: function(file, response){
					jQuery("#ol_<?php echo $rowData->name; ?> li.error").remove();
					jQuery('#ol_<?php echo $rowData->name; ?>').append(response);
					var uploadfiles = jQuery('#ol_<?php echo $rowData->name; ?> li').map(function(){
						return jQuery(this).find('span').text();
					}).get().join(',');
					jQuery('#<?php echo $rowData->name . '_' . $uniqueId; ?>').val(uploadfiles);
					jQuery('#<?php echo $rowData->name; ?>').val(uploadfiles);
					this.enable();
				}
			}
		);
	})(jQuery);
</script>
<?php else: ?>
<script type="text/javascript">
	jQuery.noConflict();
	new AjaxUpload(
		"file<?php echo $rowData->name . '_' . $uniqueId; ?>",
		{
			action: "index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
			data: {mname: "file<?php echo $rowData->name . '_' . $uniqueId; ?>"},
			name: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
			onSubmit: function(file, ext){
				jQuery('#<?php echo $rowData->name ?>').text("<?php echo JText::_('COM_REDSHOP_UPLOADING') . 'file'; ?>");
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
<?php endif; ?>
