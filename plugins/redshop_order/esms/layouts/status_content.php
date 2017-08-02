<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$tagList = array(
		'order_id',
		'shop_name',
		'status'
	);

extract($displayData);
$status = '';

foreach ($statusList as $key => $value)
{
	$status .= '<option value="' . $value->value . '">' . $value->text . '</option>';
}

$i = 0;
?>
<a class="btn btn-success" href="javascript:generateInput();"><?php echo JText::_('PLG_REDSHOP_ORDER_ESMS_ADD_BUTTON'); ?></a>
<div class="row">
	<div class="span4">
		<div class="well">
			<h3><?php echo JText::_('PLG_REDSHOP_ORDER_ESMS_TAG_LIST'); ?></h3>
			<?php foreach ($tagList as $key => $value) : ?>
				{<?php echo $value; ?>}: <?php echo JText::_('PLG_REDSHOP_ORDER_ESMS_' . strtoupper($value)); ?><br/>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="span8" id="input-wrapper">
		<?php if (!empty($values)): ?>
			<?php foreach ($values as $key => $value): ?>
				<div class="control-group" id="wrapper-<?php echo $i; ?>">
					<div class="control-label">
						<select name="<?php echo $name ?>[<?php echo $i ?>][status]">
							<?php foreach ($statusList as $statusValue) : ?>
								<option 
								<?php if ($statusValue->value == $value['status']) : ?>
									selected="selected"
								<?php endif; ?>
								value="<?php echo $statusValue->value; ?>">
									<?php echo $statusValue->text; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="controls">
						<textarea name="<?php echo $name ?>[<?php echo $i ?>][content]"><?php echo $value['content']; ?></textarea>
						<a href="javascript:removeInput(<?php echo $i; ?>)" class="btn btn-danger"><?php echo JText::_('PLG_REDSHOP_ORDER_ESMS_REMOVE_BUTTON'); ?></a>
					</div>
				</div>
				<?php $i++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
<input type="hidden" name="count" value="<?php echo $i; ?>"/>
<script type="text/javascript">
	function generateInput()
	{
		var count = jQuery('input[name="count"]').val();
		var option = '<?php echo $status; ?>';
		var name = '<?php echo $name; ?>';
		var html = '<div class="control-group" id="wrapper-'+count+'">'
		+'<div class="control-label">'
		+'<select name="'+name+'['+count+'][status]">'
		+option
		+'</select>'
		+'</div>'
		+'<div class="controls">'
		+'<textarea name="'+name+'['+count+'][content]"></textarea>'
		+'<a href="javascript:removeInput('+count+');" class="btn btn-danger">Remove</a>'
		+'</div>'
		+'</div>';
		jQuery('#input-wrapper').append(html);
		jQuery('select[name="'+name+'['+count+'][status]"]').trigger('chosen:updated');
		count++;
		jQuery('input[name="count"]').val(count);
	}
	function removeInput(idx)
	{
		jQuery('#wrapper-'+idx).remove();
	}
</script>
