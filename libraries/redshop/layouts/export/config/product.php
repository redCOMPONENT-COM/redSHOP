<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);
?>

<!-- Radio for load extra fields -->
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_EXTRA_FIELDS'); ?></label>
	<div class="col-md-10">
		<label class="radio-inline">
			<input name="product_extrafields" value="1" type="radio"/><?php echo JText::_('JYES'); ?>
		</label>
		<label class="radio-inline">
			<input name="product_extrafields" value="0" type="radio" checked/><?php echo JText::_('JNO'); ?>
		</label>
	</div>
</div>

<!-- Radio for load extra fields -->
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_ATTRIBUTES_DATA'); ?></label>
	<div class="col-md-10">
		<label class="radio-inline">
			<input name="include_attributes" value="1" type="radio"/><?php echo JText::_('JYES'); ?>
		</label>
		<label class="radio-inline">
			<input name="include_attributes" value="0" type="radio" checked/><?php echo JText::_('JNO'); ?>
		</label>
	</div>
</div>

<!-- File format -->
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_FILE_TYPE'); ?></label>
	<div class="col-md-10">
		<select name="export_file_type">
			<option value="Excel5">Excel5</option>
			<option value="Excel2007">Excel2007</option>
			<option value="HTML">HTML</option>
			<option value="CSV">CSV</option>
			<option value="PDF">PDF</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_CATEGORIES'); ?></label>
	<div class="col-md-10">
		<?php
		JHtml::_(
			'select.genericlist', $categories, 'product_categories[]',
			'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_CATEGORIES_PLACEHOLDER') . '"',
			'value',
			'text'
		);
		?>
	</div>
</div>


<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_MANUFACTURERS'); ?></label>
	<div class="col-md-10">
		<?php
		JHtml::_(
			'select.genericlist', $manufacturers, 'product_manufacturers[]',
			'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_MANUFACTURERS_PLACEHOLDER') . '"',
			'value',
			'text'
		);
		?>
	</div>
</div>
