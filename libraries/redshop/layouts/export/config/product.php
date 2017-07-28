<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$language = JFactory::getLanguage();
$language->load('com_redshop');

extract($displayData);

$categoriesHtml    = array();

if (!$categories->isEmpty())
{
	foreach ($categories as $category)
	{
		$categoriesHtml[] = JHtml::_('select.option', $category->getId(), $category->get('name'), 'value', 'text');
	}
}

$manufacturersHtml       = array();

foreach ($manufacturers as $manufacturer)
{
	$manufacturersHtml[] = JHtml::_('select.option', $manufacturer->value, $manufacturer->text, 'value', 'text');
}

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
	<label class="col-md-2 control-label"><?php echo JText::_('COM_REDSHOP_EXPORT_PRODUCT_CONFIG_FILE_TYPE'); ?></label>
	<div class="col-md-10">
		<select name="export_file_type">
			<option value="xls">Excel5</option>
			<option value="xlsx">Excel2007</option>
			<option value="html">HTML</option>
			<option value="csv">CSV</option>
			<option value="pdf">PDF</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_CATEGORIES'); ?></label>
	<div class="col-md-10">
		<?php
		echo JHtml::_(
			'select.genericlist', $categoriesHtml, 'product_categories[]',
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
		echo JHtml::_(
			'select.genericlist', $manufacturersHtml, 'product_manufacturers[]',
			'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_MANUFACTURERS_PLACEHOLDER') . '"',
			'value',
			'text'
		);
		?>
	</div>
</div>