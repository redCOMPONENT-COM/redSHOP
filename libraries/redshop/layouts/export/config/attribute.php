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

$options = array();

foreach ($products as $product)
{
	$options[] = JHtml::_('select.option', $product->product_id, $product->product_name, 'value', 'text');
}
?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_PRODUCTS'); ?></label>
	<div class="col-md-10">
		<?php
		echo JHtml::_(
			'select.genericlist', $options, 'products[]',
			'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_PRODUCTS_PLACEHOLDER') . '"',
			'value',
			'text'
		); ?>
	</div>
</div>